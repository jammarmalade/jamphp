<?php

namespace Common\Model;

use \Framework\Model;

class userModel extends Model {

    public $tablename = 'user';

    public function __construct() {
        parent::__construct($this->tablename);
    }
    
    /** 用户登录验证    
     * @param string $name 用户昵称/邮箱
     * @param string $password 用户密码
     */
    public function userLoginCheck($name,$password){
        //失败次数 登录错误在十分钟内 且大于等于5次
        if ($failed = Model('loginfailed')->where(array('ip'=>REQUEST_IP))->fetch()) {
            if ((TIMESTAMP - $failed['lastupdate']) < 600 && $failed['count'] >= 5) {
                return jreturn(false,'错误次数太多，请 10 分钟后再试');
            }
        }
        if(check_email($name)){
            $where['email']=$name;
        }else{
            $where['name']=$name;
        }
        $userInfo = $this->field('uid,username,password,email,notice,pm,groupid,salt')->where($where)->find();
        if($userInfo){
            $pwd=md5(md5($password).$userInfo['salt']);
            if($userInfo['password']==$pwd){
                session('user',$userInfo);
                return jreturn(true);
            }else{
                //插入失败次数
                if($failed){
                    Model('loginfailed')->update(['id'=>$failed['ip']],[
                        'count'=>$failed['count']+1,
                        'lastupdate'=>TIMESTAMP,
                    ]);
                }else{
                    Model('loginfailed')->add([
                        'ip'=>REQUEST_IP,
                        'count'=>1,
                        'lastupdate'=>TIMESTAMP,
                    ]);
                }
                return jreturn(false,'密码错误');
            }
        }else{
            return jreturn(false,'用户不存在');
        }
    }
}
