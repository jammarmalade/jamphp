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
            <!-- article info -->
            <div class="row content-area">
                <div class="article-tag clearfix">
                    <div class="tag-show-area">
                        <span>
                            <?php if(is_array($articleInfo['tags'])) foreach($articleInfo['tags'] as $k => $v) { ?>                            <a href="?m=tag&do=view&tid=<?php echo $v['tagid'];?>" data="<?php echo $v['tagid'];?>"><?php echo $v['tagname'];?></a>
                            <?php } ?>
                        </span>
                        <?php if(session('user.uid')==$articleInfo['authorid'] || IS_ADMIN) { ?>
                        <a href="javascript:;" class="tag-edit">修改</a>
                        <?php } ?>
                    </div>
                    <div class="tag-edit-area clearfix">
                        <div id="tags_item_add">
                            <?php if(is_array($articleInfo['tags'])) foreach($articleInfo['tags'] as $k => $v) { ?>                            <div><span><?php echo $v['tagname'];?></span><a href="javascript:;" data="<?php echo $v['tagid'];?>" class="t-rem" name="removetag"></a></div>
                            <?php } ?>
                        </div>
                        <div class="">
                            <div class="tags-search-area"><input type="text" id="tags_ipt_add" placeholder="搜索标签" autocomplete="off"></div>
                            <a class="a-btn" href="javascript:;">完成</a>
                        </div>
                    </div>
                </div>
                <h3 id="article_subject" data="<?php echo $articleInfo['aid'];?>"><?php echo $articleInfo['subject'];?></h3>
                <div class="list-tip">
                    <div><span class="glyphicon glyphicon-time"></span><span title="<?php echo $articleInfo['time'];?>"><?php echo $articleInfo['formattime'];?></span></div>
                    <div><span class="glyphicon glyphicon-user"></span><?php echo $articleInfo['author'];?></div>
                    <?php if($articleInfo['like']) { ?><div><span class="glyphicon glyphicon-thumbs-up"></span><?php echo $articleInfo['like'];?></div><?php } ?>
                    <?php if($articleInfo['comments']) { ?><div><span class="glyphicon glyphicon-comment"></span><?php echo $articleInfo['comments'];?></div><?php } ?>
                    <div><span class="glyphicon glyphicon-eye-open"></span><?php echo $articleInfo['views'];?></div>
                    <?php if(session('user.uid')==$articleInfo['authorid'] || IS_ADMIN) { ?>
                    <div><span class="glyphicon glyphicon-edit"></span> <a href="?m=blog&c=article&a=update&aid=<?php echo $articleInfo['aid'];?>">修改</a></div>
                    <?php } ?>
                </div>
                <div class="content">
                    <?php echo $articleInfo['content'];?>
                </div>
                <!-- like -->
                <div class="text-center extend" style="margin-top:50px;">
                    <button type="button" class="btn btn-primary like-btn"><span class="glyphicon glyphicon-thumbs-up"></span>
                        <?php if($articleInfo['like']) { ?>
                        <span class="article-like"><?php echo $articleInfo['like'];?></span>
                        <?php } ?>
                    </button>
                </div>
            </div>
            <!-- / article info -->

            <!-- comments list-->
            <div class="row" style="margin-top:50px;border-top:3px solid #428BCA;">
                <h2 class="title">精彩评论</h2>
                <div class="comments-list language-php">
                    <?php if($commentList) { ?>
                    <?php include display('_comment'); ?>                    <?php } else { ?>
                    <div class="no-conmment">
                        <p class="text-center"><span style="color:silver;">暂无评论</span></p>
                    </div>
                    <?php } ?>
                </div>
                <!-- comment page -->
                <?php if($next) { ?>
                <button type="button" class="btn btn-primary btn-block loadmore" data="?m=blog&c=comment&a=getList&aid=<?php echo $articleInfo['aid'];?>&page=2"  style="margin-top:10px;">加载更多</button>
                <?php } ?>
            </div>
            <!-- code js -->
            <?php includeJSCSS('codePrism');?>            <div class="row" style="margin-top:10px;border-top:3px solid #428BCA;">
                <h2 class="title"><a name="comment" id="comment"></a>发表评论<span id="recomment" style="color:#c1c1c1;margin-left:10px;"></span></h2>

                <?php $areaid="comment_edit";$editid="addcomment";$codeList=code_language('show');?>                <?php include display('common/_editor'); ?>            </div>


        </div>
        <?php include display('_sidebar'); ?>    </div>
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