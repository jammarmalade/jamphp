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
                                <span class="badge span-margin-left msg-color">
                                    <?php if(session('user.pm')) { ?>
                                    <?php echo session('user.pm');; ?>                                    <?php } ?>
                                </span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <?php echo session('user.username');; ?><span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="?m=blog&c=article&a=new" target="_blank">写文章</a></li>
                                <li class="divider"></li>
                                <li><a href="admin.php" target="_blank" target="_blank">后台管理</a></li>
                                <li><a href="?m=blog&c=user&a=logoutDo">退出 <span class="glyphicon glyphicon-log-out span-margin-left"></span> </a></li>
                            </ul>
                        </li>
                        <?php } else { ?>
                        <li><a href="javascript:;" class="login"><span class="glyphicon glyphicon-user"></span> 登录</a></li>
                        <li><a href="?m=blog&c=user&a=reg"><span class="glyphicon glyphicon-piggy-bank"></span> 注册</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="container" >
    <div class="row" style="margin-top:20px;">
        <div class="col-xs-12 col-sm-6 col-md-8" style="padding:0px 30px;">
            <!-- article list -->
            <?php if(is_array($articleList)) foreach($articleList as $k => $v) { ?>            <div class="row row-bottom">
                <a href="<?php echo $v['link'];?>" target="_blank" class="list-link"><?php echo $v['subject'];?></a>
                <div class="list-content clearfix">
                    <?php if(isset($imgids[$v['aid']])) { ?>
                    <div class="list-img"><a href="<?php echo $v['link'];?>" target="_blank"><img src="<?php echo IMG_DIR;?>/l.gif" class="lazy" data-original="<?php echo $imgids[$v['aid']];?>"></a></div>
                    <?php } ?>
                    <div><?php echo $v['content'];?></div>
                </div>
                <div class="list-tip">
                    <div><span class="glyphicon glyphicon-time"></span><a href="<?php echo $v['link'];?>" target="_blank" class="list-tip-link" title="<?php echo $v['time'];?>"><?php echo $v['formattime'];?></a></div>
                    <div><span class="glyphicon glyphicon-user"></span><?php echo $v['author'];?></div>
                    <?php if($v['like']) { ?><div><span class="glyphicon glyphicon-thumbs-up"></span><?php echo $v['like'];?></div><?php } ?>
                    <?php if($v['comments']) { ?><div><span class="glyphicon glyphicon-comment"></span><?php echo $v['comments'];?></div><?php } ?>
                </div>
            </div>
            <?php } ?>
            <!-- / article list -->

            <!-- page -->
            <?php echo $pageHtml;?>

        </div>
        {display _sidebar article}

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
    </body>
</html>