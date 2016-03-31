<?php if(is_array($commentList)) foreach($commentList as $k => $com) { ?><div class="media comment-item">
    <a class="media-left" href="#">
        <img src="<?php echo $com['avatar'];?>" alt="<?php echo $com['author'];?>" class="img-thumbnail">
    </a>
    <div class="media-body" style="width:100%;">
        <div class="media-heading"><a href="javascript:;" class="com-author"><?php echo $com['author'];?></a>
            <?php if($com['rcid']) { ?>
            回复 <a href="#comment"><?php echo $com['username'];?></a>
            <?php } ?>
        </div>
        <div class="com-content" style="padding:10px 0px;"><?php echo $com['content'];?></div>
        <div class="com-tip">
            <span title="<?php echo $com['time'];?>"><?php echo $com['formattime'];?></span>
            <?php if(session('user.uid') && session('user.uid')!=$com['authorid']) { ?>
            <span><a href="javascript:;" class="com-tip-like" data="<?php echo $com['cid'];?>">赞<span><?php if($com['like']) { ?><?php echo $com['like'];?><?php } ?></span></a></span>
            <span><a href="#comment" class="com-tip-recom" data="<?php echo $com['cid'];?>">回复</a></span>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>