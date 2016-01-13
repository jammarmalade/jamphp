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
        if(session('user.uid')){
            
        }
        
        $this->assign('pageTitle', '登录本站');
        $this->display();
    }

}
