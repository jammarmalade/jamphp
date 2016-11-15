<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $pageTitle;?></title>
        <meta name="keywords" content="<?php echo $pageKeywords;?>">
        <meta name="description" content="<?php echo $pageDescription;?>">

        <!--[if lt IE 9]>
          <script src="http://libs.useso.com/js/html5shiv/3.7/html5shiv.min.js"></script>
          <script src="http://libs.useso.com/js/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php includeJSCSS();?>    </head>
    <body>

        <div class="container page-header">
            <h2><?php echo $setting['blog']['blogName'];?><small style="margin-left:10px;"><?php echo $setting['blog']['blogSubhead'];?></small></h2>
        </div>
        <nav class="navbar navbar-inverse navbar-fixed-top bgrgba" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand active" href="<?php echo SITE_URL;?>">首页</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <?php if(is_array($setting['nav'])) foreach($setting['nav'] as $k => $v) { ?>                        <?php if($v['status']!=0) { ?>
                        <?php if(isset($v['downnav']) && count($v['downnav'])) { ?>
                        <li class="dropdown">
                            <a href="<?php echo $v['link'];?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><b><?php echo $v['name'];?></b><span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <?php if(is_array($v['downnav'])) foreach($v['downnav'] as $ck => $cv) { ?>                                <?php if($v['status']!=0) { ?>
                                <li><a href="<?php echo $cv['link'];?>"><b><?php echo $cv['name'];?></b></a></li>
                                <?php } ?>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } else { ?>
                        <li><a href="<?php echo $v['link'];?>"><b><?php echo $v['name'];?></b></a></li>
                        <?php } ?>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if(session('user.uid')) { ?>
                        <li>
                            <a href="#" class=""><span class="glyphicon glyphicon-bell span-margin-left"></span>
                                <?php if(session('user.notice')) { ?>
                                <span class="badge span-margin-left msg-color brs"><?php echo session('user.notice');; ?></span>
                                <?php } else { ?>
                                提醒
                                <?php } ?>
                            </a>
                        </li>
                        <li>
                            <a href="#"><span class="glyphicon glyphicon-envelope span-margin-left"></span>
                                <?php if(session('user.pm')) { ?>
                                <span class="badge span-margin-left msg-color"><?php echo session('user.pm');; ?></span>
                                <?php } else { ?>
                                消息
                                <?php } ?>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <?php echo session('user.username');; ?><span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="?m=blog&c=article&a=add" target="_blank">写文章</a></li>
                                <li class="divider"></li>
                                <li><a href="admin.php" target="_blank" target="_blank">后台管理</a></li>
                                <li><a href="?m=blog&c=user&a=logoutDo">退出 <span class="glyphicon glyphicon-log-out span-margin-left"></span> </a></li>
                            </ul>
                        </li>
                        <?php } else { ?>
                        <li><a href="javascript:;" class="login"><span class="glyphicon glyphicon-user"></span> 登录</a></li>
                        <li><a href="?m=blog&c=user&a=register"><span class="glyphicon glyphicon-piggy-bank"></span> 注册</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">注册本站</div>
        <div class="panel-body">

            <div class="row">

                <form class="form-horizontal" role="form" action="index.php?m=user&do=reg" method="post" id="regfrom">
                    <input type="hidden" id="referer" value="<?php echo REFERER;?>">
                    <div class="form-group">
                        <label for="username" class="hidden-xs col-sm-4 col-md-4 control-label">昵称</label>
                        <div class="col-xs-10 col-sm-4 col-md-4">
                            <input type="text" class="form-control" id="username" placeholder="昵称" autocomplete="off"><span class="label label-danger"></span>
                            <input type="hidden" id="authusername" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="hidden-xs col-sm-4 col-md-4 control-label">Email</label>
                        <div class="col-xs-10 col-sm-4 col-md-4">
                            <input type="email" class="form-control" id="email" placeholder="Email" autocomplete="off"><span class="label label-danger"></span>
                            <input type="hidden" id="authemail" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pwd" class="hidden-xs col-sm-4 col-md-4 control-label">密码</label>
                        <div class="col-xs-10 col-sm-4 col-md-4">
                            <input type="password" class="form-control" id="pwd" placeholder="Password"><span class="label label-danger"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pwd2" class="hidden-xs col-sm-4 col-md-4 control-label">确认密码</label>
                        <div class="col-xs-10 col-sm-4 col-md-4">
                            <input type="password" class="form-control" id="pwd2" placeholder="确认密码"><span class="label label-danger"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-offset-1 col-sm-offset-4 col-sm-5">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="autologin" checked="true"> 自动登录
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-offset-1 col-sm-offset-4 col-sm-5">
                            <button type="submit" class="btn btn-default" id="regbtn">注册</button>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>



        <div class="jumbotron">
            <div class="container">
                <div class="row text-center">
                    <b>404 <span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span> 2015</b>
                    <span style="margin:0px 10px;">|</span>
                    <a href="mailto:<?php echo $setting['blog']['adminEmail'];?>"><?php echo $setting['blog']['adminEmail'];?></a>
                    <br>
                    <?php echo FORMATTIME;?>

                </div>
            </div>
        </div>
        <script type="text/javascript">
            //回车登录
            <?php if(!session('user.uid')) { ?>
            document.onkeydown = function (e) {
                var ev = document.all ? window.event : e;
                if (ev.keyCode == 13 && $('#login-area').length!=0) {
                    loginDo();
                }
            }
            <?php } ?>
        </script>
    </body>
</html>