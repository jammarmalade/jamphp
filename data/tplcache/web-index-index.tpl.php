<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="keywords" content="<?php echo $pageKeywords;?>" />
        <meta name="description" content="<?php echo $pageDescription;?>" />
        <title><?php echo $pageTitle;?></title>
    </head>
    <body>
		<div style="width:700px;margin:10px auto;">视图布局 main header </div>
        <?php printarr($data);?><?php if(is_array($data)) { if(is_array($data)) foreach($data as $k => $v) { ?><div style="margin:10px;">
			<span>id: <?php echo $v['label_id'];?></span>
			<br>
			<span>运动: <?php echo $v['label_name'];?></span>
		</div>
	<?php } ?>
	
<?php } ?>

<?php echo $content;?>

测试模板index

		<div style="width:700px;margin:200px auto;">视图布局 main footer </div>
    </body>
</html>