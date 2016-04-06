<?php

/*
 * 用户
 */

namespace Blog\Controller;

use \Common\webController;

class userController extends webController {
    /*
     * 用户登录
     */

    public function login() {
        if (session('user.uid')) {
            if (AJAX) {
                $this->ajaxReturn('已经登录了', 'reload', ture);
            } else {
                bheader(REFERER);
            }
        }
        if (AJAX) {
            $this->ajaxReturn('', $this->display('login', true), true);
        }

        $this->assign('pageTitle', '登录本站');
        $this->display();
    }

    /**
     * 执行登录
     */
    public function loginDo() {
        if(!AJAX || !input('loginbtn')){
            $this->ajaxReturn('非法操作', '', false);
        }
        $username = input('username');
        $pwd = input('pwd');
        $autologin = input('autologin');
        $res = Model('user')->userLoginCheck($username,$pwd);
        $cookietime=86400;
        if($autologin){
            $cookietime=2952000;
        }
        bsetcookie('authuser', authcode("{$username}\t{$pwd}", 'ENCODE'), $cookietime);
        $this->ajaxReturn($res['msg'], $res['data'], $res['status']);
    }
    
    /**
     * 退出
     */
    public function logoutDo(){
        foreach($_COOKIE as $k => $v) {
            bsetcookie($k);
        }
        session('user',NULL);
        bheader(REFERER);
    }
    /**
     * 注册
     */
    public function register(){
        if (session('user.uid')) {
            $this->showError('已经登录了',['url'=>REFERER]);
        }
        
        $this->assign('pageTitle', '注册本站');
        $this->display();
    }
    /**
     * 执行注册
     */
    public function registerDo(){
        if(!AJAX || !input('regbtn')){
            $this->ajaxReturn('非法操作', '', false);
        }
        $username = input('username');
        $email = input('email');
        $pwd = input('pwd');
        $pwd2 = input('pwd2');
        $autologin = input('autologin');
        
        if ($pwd != $pwd2) {
            $this->ajaxReturn('两次输入的密码不一致', 'pwd2', false);
        }
        if (!check_username($username)) {
            $this->ajaxReturn('昵称包含敏感字符', 'username', false);
        }
        if (!check_email($email)) {
            $this->ajaxReturn('Email 格式不对', 'email', false);
        }
        
        if (Model('user')->where(['username'=>$username])->fetch()) {
            $this->ajaxReturn('该昵称已被注册', 'username', false);
        }
        if (Model('user')->where(['email'=>$email])->fetch()) {
            $this->ajaxReturn('该 Email 已被注册', 'email', false);
        }
        //开始注册
        $salt = random(6);
        $md5pwd = md5($pwd);
        $realpwd = md5($md5pwd . $salt);
        $insert = array(
            'username' => $username,
            'password' => $realpwd,
            'email' => $email,
            'groupid' => 10,
            'regip' => REQUEST_IP,
            'regdate' => TIMESTAMP,
            'lastloginip' => REQUEST_IP,
            'lastlogintime' => TIMESTAMP,
            'salt' => $salt,
        );
        $uid = Model('user')->add($insert);
        if ($uid > 0) {
            $insert['uid'] = $uid;
            $cookietime = 86400;
            if ($autologin) {
                $cookietime = 2952000;
            }
            session('user', $insert);
            bsetcookie('user', $insert, $cookietime);
            
            $this->ajaxReturn('注册成功', 'success');
        } else {
            $this->ajaxReturn('注册失败，请刷新重试', 'error', false);
        }
            
    }
    /**
     * 验证
     */
    public function check(){
        if(!AJAX){
            $this->ajaxReturn('非法操作', '', false);
        }
        $type = $return['type'] = input('type','');
        $data = $return['data'] = input('data','');
        if(!$type || !$data || !in_array($type,array('username','email'))){
            $this->ajaxReturn('数据错误', $type, false);
        }
        $func=$type=='username' ? 'check_username' : 'check_email';
        if(!$func($data)){
            $msg = $type=='username' ? '昵称包含敏感字符' :  'Email 格式不对';
            $this->ajaxReturn($msg, $return, false);
        }
        if(Model('user')->where([$type=>$data])->fetch()){
            $msg = $type=='username' ? '该昵称已被注册' :  '该 Email 已被注册';
            $this->ajaxReturn($msg, $return, false);
        }
        $this->ajaxReturn('success',$return);
    }
}
    