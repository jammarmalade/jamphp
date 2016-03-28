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
        bheader('location: '.REFERER);
    }
}
    