/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : blog

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-01-07 17:58:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_article`
-- ----------------------------
DROP TABLE IF EXISTS `pre_article`;
CREATE TABLE `pre_article` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `subject` char(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `content` mediumtext CHARACTER SET utf8 NOT NULL,
  `authorid` int(10) unsigned NOT NULL DEFAULT '0',
  `author` char(15) CHARACTER SET utf8 NOT NULL,
  `like` int(10) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL,
  `image` tinyint(8) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `from` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of pre_article
-- ----------------------------
INSERT INTO `pre_article` VALUES ('2', '0', '恻恻恻恻恻恻', '[div]\n[attach]25[/attach][/div][div]\n[/div]啊死擦死擦死擦死擦洒洒[div]\n[/div][quote]擦撒擦拭[/quote][h3]擦撒擦拭[/h3][div][b]撒擦拭擦拭从[/b][i]萨斯擦拭擦拭擦[/i][u]拭擦拭擦拭擦[/u]拭擦拭擦[/div][div]\n[/div][div][url=https://www.baidu.com/]链接[/url]&nbsp; &nbsp; csaas&nbsp;[/div][hr][div]\n[/div][div][/div][div]cash曾经撒茶水间[/div][div]\n[/div][div]\n[/div][div]啊擦死[/div]\n[code=php]if($attach){\n		$resattachs=J::t(&#039;image&#039;)-&gt;fetch_all(&#039;id,path,thumbH&#039;,&quot;aid=$attach AND type=&#039;article&#039;&quot;);\n		if($resattachs){\n			$find=$replace=array();\n			foreach($resattachs as $k=&gt;$v){\n				$find[]=&quot;/[attach]&quot;.$v[&#039;id&#039;].&quot;[/attach]/i&quot;;\n				$width=$suff=&#039;&#039;;\n				if($v[&#039;thumbH&#039;]){\n					$width=&#039;width=&quot;600px&quot;&#039;;\n					$suff=&#039;.thumb.jpg&#039;;\n				}\n				$imgurl=$_B[&#039;siteurl&#039;].$v[&#039;path&#039;].$suff;\n				if($type==&#039;update&#039;){\n					$replace[]=&#039;&lt;img src=&quot;&#039;.$imgurl.&#039;&quot; &#039;.$width.&#039;&gt;&#039;;\n				}else{\n					$replace[]=&#039;&lt;img src=&quot;&#039;.IMGDIR.&#039;l.gif&quot; class=&quot;lazy&quot; data-original=&quot;&#039;.$imgurl.&#039;&quot; &#039;.$width.&#039;&gt;&#039;;\n				}\n			}\n			$content = preg_replace($find,$replace,$content);\n		}\n	}[/code]', '1', 'admin', '1', '8', '0', '25', '1445840107', '1446009942', '1', '1');
INSERT INTO `pre_article` VALUES ('3', '0', 'cececcececeeeeee', '[div]\n[attach]25[/attach][/div][div]\n[/div]啊死擦死擦死擦死擦洒洒[div]\n[/div][quote]擦撒擦拭[/quote][h3]擦撒擦拭[/h3][div][b]撒擦拭擦拭从[/b][i]萨斯擦拭擦拭擦[/i][u]拭擦拭擦拭擦[/u]拭擦拭擦[/div][div]\n[/div][div][url=https://www.baidu.com/]链接[/url]&nbsp; &nbsp; csaas&nbsp;[/div][hr][div]\n[/div][div][/div][div]cash曾经撒茶水间[/div][div]\n[/div][div]\n[/div][div]啊擦死[/div]', '1', 'admin', '0', '0', '0', '0', '1436427142', '0', '1', '1');
INSERT INTO `pre_article` VALUES ('6', '0', '测试测试', '[div]&lt;script&gt;alert(\'OK\')&lt;/script&gt;[/div][div]啊哈哈哈哈啊[/div]', '1', 'admin', '0', '0', '0', '0', '1436511170', '0', '1', '1');
INSERT INTO `pre_article` VALUES ('7', '0', '测试测试', '[div]&lt;script&gt;alert(\'OK\')&lt;/script&gt;[/div][div]啊哈哈哈哈啊[/div]', '1', 'admin', '0', '0', '0', '0', '1436511180', '0', '1', '1');
INSERT INTO `pre_article` VALUES ('8', '0', 'csascacascas', '&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;测测试数据测试数据测试数据测试数据试数据。。。。。。。。。。。。。', '1', 'admin', '0', '0', '0', '0', '1436517058', '0', '1', '1');
INSERT INTO `pre_article` VALUES ('9', '0', 'ascascasas', ' ascasacascas', '1', 'admin', '0', '0', '0', '0', '1436517275', '0', '1', '1');
INSERT INTO `pre_article` VALUES ('10', '0', '测试测试测试测试测试测试测试测试测试测试测试接啊纠结啊睡觉', '[div]cececececeeeeeee[/div][div]\n[/div][div]记得记得记得记得你当年的\n[url=http://192.168.1.227/guide/attachments/answer/origin/201505/29/556836086aa4d.jpg][img=http://192.168.1.227/guide/attachments/answer/600/201505/29/556836086aa4d.jpg][/url]￼&nbsp;\n活动记得记得记得\n喜欢很多很多\n[/div]', '1', 'admin', '0', '0', '0', '0', '1436518343', '0', '1', '1');
INSERT INTO `pre_article` VALUES ('11', '0', '擦拭擦拭擦拭', '记得记得记得记得你当年的\n[url=http://192.168.1.227/guide/attachments/answer/origin/201505/29/556836086aa4d.jpg][img=http://192.168.1.227/guide/attachments/answer/600/201505/29/556836086aa4d.jpg][/url]￼&nbsp;\n活动记得记得记得\n喜欢很多很多', '1', 'admin', '0', '0', '0', '0', '1436518620', '0', '1', '1');
INSERT INTO `pre_article` VALUES ('12', '0', '测试发图测试', '[div]\n[attach]26[/attach][/div][div]\n[/div][div]擦撒撒擦拭从啊[/div][div]\n[/div][div]\n[attach]27[/attach][/div][div]a撒擦拭擦拭[/div][div]\n[/div][div]\n[attach]28[/attach][/div][div]擦拭擦拭擦拭[/div][div]啊死擦死啊[/div][div]\n[/div]', '1', 'admin', '0', '1', '0', '26', '1438414983', '0', '1', '1');
INSERT INTO `pre_article` VALUES ('13', '0', '擦拭擦拭', '[div]啊擦死擦死发生[attach]29[/attach][/div][div]\n[/div][div]\n[/div][div]撒擦拭从[/div][div][attach]30[/attach][/div][div]擦拭长撒as[/div]', '1', 'admin', '0', '0', '0', '29', '1438415108', '0', '1', '1');
INSERT INTO `pre_article` VALUES ('14', '0', '恻恻恻恻恻恻', '[div][div]\n[img=data/attachment/article/201507/04/173352g13raaxtxl6syzlx.jpg.thumb.jpg][/div][div]\n[/div]啊死擦死擦死擦死擦洒洒[div]\n[/div][quote]擦撒擦拭[/quote][h3]擦撒擦拭[/h3][div]撒擦拭擦拭从萨斯擦拭擦拭擦拭擦拭擦拭擦拭擦拭擦[/div][div]\n[/div][div][url=https://www.baidu.com/]链接[/url]&nbsp; &nbsp; csaas&nbsp;[/div][hr][div]\n[/div][div][/div][div]cash曾经撒茶水间[/div][div]\n[/div][div]\n[/div][div]啊擦死[/div]\n[code=$2]if($attach){\n$resattachs=J::t(\'image\')-&gt;fetch_all(\'id,path,thumbH\',\"aid=$attach AND type=\'article\'\");\nif($resattachs){\n$find=$replace=array();\nforeach($resattachs as $k=&gt;$v){\n$find[]=\"/[attach]\".$v[\'id\'].\"[/attach]/i\";\n$width=$suff=\'\';\nif($v[\'thumbH\']){\n$width=\'width=\"600px\"\';\n$suff=\'.thumb.jpg\';\n}\n$imgurl=$_B[\'siteurl\'].$v[\'path\'].$suff;\nif($type==\'update\'){\n$replace[]=\'&lt;img src=\"\'.$imgurl.\'\" \'.$width.\'&gt;\';\n}else{\n$replace[]=\'&lt;img src=\"\'.IMGDIR.\'l.gif\" class=\"lazy\" data-original=\"\'.$imgurl.\'\" \'.$width.\'&gt;\';\n}\n}\n$content = preg_replace($find,$replace,$content);\n}\n}[/code][/div][div]\n[/div][div]测试结束[/div]', '1', 'admin', '0', '1', '0', '0', '1445837162', '0', '1', '1');
INSERT INTO `pre_article` VALUES ('15', '0', '恻恻恻恻恻恻', '[div][div]\n[img=data/attachment/article/201507/04/173352g13raaxtxl6syzlx.jpg.thumb.jpg][/div][div]\n[/div]啊死擦死擦死擦死擦洒洒[div]\n[/div][quote]擦撒擦拭[/quote][h3]擦撒擦拭[/h3][div]撒擦拭擦拭从萨斯擦拭擦拭擦拭擦拭擦拭擦拭擦拭擦[/div][div]\n[/div][div][url=https://www.baidu.com/]链接[/url]&nbsp; &nbsp; csaas&nbsp;[/div][hr][div]\n[/div][div][/div][div]cash曾经撒茶水间[/div][div]\n[/div][div]\n[/div][div]啊擦死[/div]\n[code=$2]if($attach){\n$resattachs=J::t(\'image\')-&gt;fetch_all(\'id,path,thumbH\',\"aid=$attach AND type=\'article\'\");\nif($resattachs){\n$find=$replace=array();\nforeach($resattachs as $k=&gt;$v){\n$find[]=\"/[attach]\".$v[\'id\'].\"[/attach]/i\";\n$width=$suff=\'\';\nif($v[\'thumbH\']){\n$width=\'width=\"600px\"\';\n$suff=\'.thumb.jpg\';\n}\n$imgurl=$_B[\'siteurl\'].$v[\'path\'].$suff;\nif($type==\'update\'){\n$replace[]=\'&lt;img src=\"\'.$imgurl.\'\" \'.$width.\'&gt;\';\n}else{\n$replace[]=\'&lt;img src=\"\'.IMGDIR.\'l.gif\" class=\"lazy\" data-original=\"\'.$imgurl.\'\" \'.$width.\'&gt;\';\n}\n}\n$content = preg_replace($find,$replace,$content);\n}\n}[/code][/div][div]\n[/div][div]\n[/div][div]策划不是[/div][div]\n[/div]', '1', 'admin', '0', '1', '0', '0', '1445837257', '0', '1', '1');
INSERT INTO `pre_article` VALUES ('16', '0', '恻恻恻恻恻恻', '[div][div]\n[img=data/attachment/article/201507/04/173352g13raaxtxl6syzlx.jpg.thumb.jpg][/div][div]\n[/div]啊死擦死擦死擦死擦洒洒[div]\n[/div][quote]擦撒擦拭[/quote][h3]擦撒擦拭[/h3][div]撒擦拭擦拭从萨斯擦拭擦拭擦拭擦拭擦拭擦拭擦拭擦[/div][div]\n[/div][div][url=https://www.baidu.com/]链接[/url]&nbsp; &nbsp; csaas&nbsp;[/div][hr][div]\n[/div][div][/div][div]cash曾经撒茶水间[/div][div]\n[/div][div]\n[/div][div]啊擦死[/div]\n[code=$2]if($attach){\n$resattachs=J::t(\'image\')-&gt;fetch_all(\'id,path,thumbH\',\"aid=$attach AND type=\'article\'\");\nif($resattachs){\n$find=$replace=array();\nforeach($resattachs as $k=&gt;$v){\n$find[]=\"/[attach]\".$v[\'id\'].\"[/attach]/i\";\n$width=$suff=\'\';\nif($v[\'thumbH\']){\n$width=\'width=\"600px\"\';\n$suff=\'.thumb.jpg\';\n}\n$imgurl=$_B[\'siteurl\'].$v[\'path\'].$suff;\nif($type==\'update\'){\n$replace[]=\'&lt;img src=\"\'.$imgurl.\'\" \'.$width.\'&gt;\';\n}else{\n$replace[]=\'&lt;img src=\"\'.IMGDIR.\'l.gif\" class=\"lazy\" data-original=\"\'.$imgurl.\'\" \'.$width.\'&gt;\';\n}\n}\n$content = preg_replace($find,$replace,$content);\n}\n}[/code][/div][div]\n[/div][div]\n[/div][div]策划不是[/div][div]\n[/div]', '1', 'admin', '0', '2', '0', '0', '1445837347', '0', '1', '1');
INSERT INTO `pre_article` VALUES ('17', '0', '阿斯达撒打算发生', '[div]电风扇地方都是[kbd]粉色[/kbd]的方式[/div]', '1', 'admin', '0', '3', '0', '0', '1447132249', '0', '1', '1');
INSERT INTO `pre_article` VALUES ('18', '0', '阿斯达撒打算发生', '[div]as刹那间才能卡死擦拭擦[/div][div]\n[/div][code=php]if(!$aid || !is_numeric($aid)){\n    shownotice(&#039;该文章不存在&#039;,array(&#039;referer&#039;=&gt;$_B[&#039;referer&#039;]));\n}\n[/code]', '1', 'admin', '16', '42', '0', '0', '1447132383', '1447138222', '1', '1');

-- ----------------------------
-- Table structure for `pre_article_like`
-- ----------------------------
DROP TABLE IF EXISTS `pre_article_like`;
CREATE TABLE `pre_article_like` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `username` char(15) NOT NULL,
  `aid` int(8) unsigned NOT NULL,
  `dateline` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_article_like
-- ----------------------------
INSERT INTO `pre_article_like` VALUES ('7', '1', 'admin', '18', '1451112579');
INSERT INTO `pre_article_like` VALUES ('11', '1', 'admin', '2', '1451124427');

-- ----------------------------
-- Table structure for `pre_comment`
-- ----------------------------
DROP TABLE IF EXISTS `pre_comment`;
CREATE TABLE `pre_comment` (
  `cid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rcid` int(10) unsigned NOT NULL DEFAULT '0',
  `ruid` int(10) unsigned NOT NULL DEFAULT '0',
  `username` char(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `aid` int(10) unsigned NOT NULL DEFAULT '0',
  `authorid` int(10) unsigned NOT NULL DEFAULT '0',
  `author` char(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `content` text CHARACTER SET utf8 NOT NULL,
  `dateline` int(10) NOT NULL,
  `like` int(10) unsigned NOT NULL DEFAULT '0',
  `classify` char(10) CHARACTER SET utf8 NOT NULL DEFAULT 'article',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of pre_comment
-- ----------------------------
INSERT INTO `pre_comment` VALUES ('1', '0', '0', '', '2', '1', 'admin', '阿斯报出卡死不参加as，阿塞[b]拜疆啊[/b]', '1439094966', '0', 'article', '1');
INSERT INTO `pre_comment` VALUES ('2', '1', '1', 'admin', '2', '2', 'test', '啊世博会曾[code]经卡萨诺[/code]', '1439097774', '0', 'article', '1');
INSERT INTO `pre_comment` VALUES ('3', '0', '0', '', '2', '1', 'admin', '啊死[kbd]擦死[/kbd]', '1439099614', '0', 'article', '1');
INSERT INTO `pre_comment` VALUES ('4', '0', '0', '', '2', '1', 'admin', 'asfa短发[quote]阿斯擦[/quote]啊是大是大[b]阿斯达[/b]\n啊死擦死[code]阿斯达斯夫妇[/code]', '1439101745', '0', 'article', '1');
INSERT INTO `pre_comment` VALUES ('5', '0', '0', '', '2', '1', 'admin', '撒旦撒打算', '1439101828', '0', 'article', '1');
INSERT INTO `pre_comment` VALUES ('6', '0', '0', '', '2', '1', 'admin', '阿斯达斯飞洒发送\n参赛健康\n阿斯擦你看见啊岁', '1439101868', '0', 'article', '1');
INSERT INTO `pre_comment` VALUES ('7', '2', '2', 'test', '2', '1', 'admin', '测试回复，哈哈哈哈哈', '1439261356', '0', 'article', '1');
INSERT INTO `pre_comment` VALUES ('8', '2', '2', 'test', '2', '1', 'admin', '再测一下，回复 test', '1439261551', '0', 'article', '1');
INSERT INTO `pre_comment` VALUES ('9', '0', '0', '', '2', '1', 'admin', '[code=php]\nfunction test($code,$content){\n	if(in_array($code,array(\'html\',\'php\',\'javascript\',\'js\'))){\n		return \'\'.$content.\'\';\n	}else{\n		return &quot;$content&quot;;\n	}\n}\n[/code]', '1444375200', '0', 'article', '1');
INSERT INTO `pre_comment` VALUES ('10', '0', '0', '', '2', '1', 'admin', '[code=php]\nfunction _code($code,$content){\n	$content=htmlspecialchars($content,ENT_QUOTES);\n	if(in_array($code,array(\'html\',\'php\',\'javascript\',\'js\'))){\n		return \'\'.$content.\'\';\n	}else{\n		return &quot;$content&quot;;\n	}\n}\n[/code]', '1444376414', '0', 'article', '1');
INSERT INTO `pre_comment` VALUES ('11', '0', '0', '', '2', '1', 'admin', '[code=php]\nfunction _code($code,$content){\n	$content=htmlspecialchars($content,ENT_QUOTES);\n	if(in_array($code,array(\'html\',\'php\',\'javascript\',\'js\'))){\n		return \'\'.$content.\'\';\n	}else{\n		return &quot;$content&quot;;\n	}\n}\n[/code]', '1444376520', '0', 'article', '1');
INSERT INTO `pre_comment` VALUES ('12', '0', '0', '', '2', '1', 'admin', '[code=php]\nfunction _code($code,$content){\n	$content=htmlspecialchars($content,ENT_QUOTES);\n	if(in_array($code,array(\'html\',\'php\',\'javascript\',\'js\'))){\n		return \'\'.$content.\'\';\n	}else{\n		return &quot;$content&quot;;\n	}\n}\n[/code]', '1444376572', '0', 'article', '1');
INSERT INTO `pre_comment` VALUES ('13', '0', '0', '', '2', '1', 'admin', '[code=php]\nfunction _code($code,$content){\n	$content=htmlspecialchars($content,ENT_QUOTES);\n	if(in_array($code,array(\'html\',\'php\',\'javascript\',\'js\'))){\n		return \'&lt;pre class=&quot;brush:\'.$code.\'; toolbar: false; auto-links: false;&quot;&gt;\'.$content.\'&lt;/pre&gt;\';\n	}else{\n		return &quot;&lt;pre&gt;$content&lt;/pre&gt;&quot;;\n	}\n}\n[/code]', '1444376704', '0', 'article', '1');

-- ----------------------------
-- Table structure for `pre_image`
-- ----------------------------
DROP TABLE IF EXISTS `pre_image`;
CREATE TABLE `pre_image` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `aid` mediumint(8) unsigned NOT NULL,
  `path` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `size` int(10) unsigned NOT NULL DEFAULT '0',
  `width` smallint(6) unsigned NOT NULL DEFAULT '0',
  `height` smallint(6) unsigned NOT NULL DEFAULT '0',
  `thumbH` smallint(6) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `aid` (`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_image
-- ----------------------------
INSERT INTO `pre_image` VALUES ('1', '1', '0', 'data/attachment/article/201506/29/161324mu94ec8u5p5eu60g.jpg', 'article', '93043', '1920', '1080', '338', '0', '1435565604');
INSERT INTO `pre_image` VALUES ('2', '1', '0', 'data/attachment/article/201507/04/161228ballgg2ignyfdgdg.jpg', 'article', '93043', '1920', '1080', '338', '0', '1435997548');
INSERT INTO `pre_image` VALUES ('3', '1', '0', 'data/attachment/article/201507/04/161228gldnr62zxf0ek0fc.jpg', 'article', '395934', '1600', '1000', '375', '0', '1435997548');
INSERT INTO `pre_image` VALUES ('4', '1', '0', 'data/attachment/article/201507/04/163605fysuqrqjs2v2cus3.jpg', 'article', '395934', '1600', '1000', '375', '0', '1435998965');
INSERT INTO `pre_image` VALUES ('5', '1', '0', 'data/attachment/article/201507/04/163753khpohxevjpzjsxvj.jpg', 'article', '395934', '1600', '1000', '375', '0', '1435999073');
INSERT INTO `pre_image` VALUES ('6', '1', '0', 'data/attachment/article/201507/04/164110zktvw5rvvzhv3v3k.jpg', 'article', '395934', '1600', '1000', '375', '0', '1435999270');
INSERT INTO `pre_image` VALUES ('7', '1', '0', 'data/attachment/article/201507/04/164138cfv3kpg0gritht1p.jpg', 'article', '395934', '1600', '1000', '375', '0', '1435999298');
INSERT INTO `pre_image` VALUES ('8', '1', '0', 'data/attachment/article/201507/04/164245zhxh1phxik6xap1h.jpg', 'article', '395934', '1600', '1000', '375', '0', '1435999365');
INSERT INTO `pre_image` VALUES ('9', '1', '0', 'data/attachment/article/201507/04/164647dmmn770pzlv7wn85.jpg', 'article', '395934', '1600', '1000', '375', '0', '1435999607');
INSERT INTO `pre_image` VALUES ('10', '1', '0', 'data/attachment/article/201507/04/164850btzo3ps31s3vkh0p.jpg', 'article', '395934', '1600', '1000', '375', '0', '1435999730');
INSERT INTO `pre_image` VALUES ('11', '1', '0', 'data/attachment/article/201507/04/165008ypiiddj329ed0jss.jpg', 'article', '395934', '1600', '1000', '375', '0', '1435999808');
INSERT INTO `pre_image` VALUES ('12', '1', '0', 'data/attachment/article/201507/04/170312yvubhgjhgvkpdbyy.jpg', 'article', '395934', '1600', '1000', '375', '0', '1436000592');
INSERT INTO `pre_image` VALUES ('13', '1', '0', 'data/attachment/article/201507/04/170349b89ll0o5qqd8q58o.jpg', 'article', '395934', '1600', '1000', '375', '0', '1436000629');
INSERT INTO `pre_image` VALUES ('14', '1', '0', 'data/attachment/article/201507/04/170704ne4izrro9k7o8fer.jpg', 'article', '395934', '1600', '1000', '375', '0', '1436000824');
INSERT INTO `pre_image` VALUES ('15', '1', '0', 'data/attachment/article/201507/04/170742wncyddjcdllco3nl.jpg', 'article', '395934', '1600', '1000', '375', '0', '1436000862');
INSERT INTO `pre_image` VALUES ('16', '1', '0', 'data/attachment/article/201507/04/170833z26p8siwuuipuubv.jpg', 'article', '395934', '1600', '1000', '375', '0', '1436000913');
INSERT INTO `pre_image` VALUES ('17', '1', '0', 'data/attachment/article/201507/04/171108koynmko19im23b31.jpg', 'article', '395934', '1600', '1000', '375', '0', '1436001068');
INSERT INTO `pre_image` VALUES ('18', '1', '0', 'data/attachment/article/201507/04/171449k6rkysmq6zharenh.jpg', 'article', '395934', '1600', '1000', '375', '0', '1436001289');
INSERT INTO `pre_image` VALUES ('19', '1', '0', 'data/attachment/article/201507/04/171502szmp4swe9khhh946.jpg', 'article', '395934', '1600', '1000', '375', '0', '1436001302');
INSERT INTO `pre_image` VALUES ('20', '1', '0', 'data/attachment/article/201507/04/171534wsmvq4pkiqo28g8m.jpg', 'article', '395934', '1600', '1000', '375', '0', '1436001334');
INSERT INTO `pre_image` VALUES ('21', '1', '0', 'data/attachment/article/201507/04/171601s9potjm3xw3i0iep.jpg', 'article', '395934', '1600', '1000', '375', '0', '1436001361');
INSERT INTO `pre_image` VALUES ('22', '1', '0', 'data/attachment/article/201507/04/172621a2z5fpck7t0pfz5h.jpg', 'article', '395934', '1600', '1000', '375', '0', '1436001981');
INSERT INTO `pre_image` VALUES ('23', '1', '0', 'data/attachment/article/201507/04/172838nkkleexkdvzxl00l.jpg', 'article', '395934', '1600', '1000', '375', '0', '1436002118');
INSERT INTO `pre_image` VALUES ('24', '1', '0', 'data/attachment/article/201507/04/173321hnn3z3knnlz153ao.jpg', 'article', '395934', '1600', '1000', '375', '0', '1436002401');
INSERT INTO `pre_image` VALUES ('25', '1', '2', 'data/attachment/article/201507/04/173352g13raaxtxl6syzlx.jpg', 'article', '395934', '1600', '1000', '375', '1', '1436002432');
INSERT INTO `pre_image` VALUES ('26', '1', '12', 'data/attachment/article/201508/01/154220pl9xdialy9d9ell7.jpg', 'article', '99863', '1002', '762', '457', '1', '1438414940');
INSERT INTO `pre_image` VALUES ('27', '1', '12', 'data/attachment/article/201508/01/154231qt18s1j7s14fzzts.jpg', 'article', '99863', '1002', '762', '457', '1', '1438414951');
INSERT INTO `pre_image` VALUES ('28', '1', '12', 'data/attachment/article/201508/01/154240ymcyy5njgzmgpgz0.jpg', 'article', '99863', '1002', '762', '457', '1', '1438414960');
INSERT INTO `pre_image` VALUES ('29', '1', '13', 'data/attachment/article/201508/01/154445lf1omrgs85moshfc.jpg', 'article', '99863', '1002', '762', '457', '1', '1438415085');
INSERT INTO `pre_image` VALUES ('30', '1', '13', 'data/attachment/article/201508/01/154451f6uf3fqqhwqwbvo0.jpg', 'article', '99863', '1002', '762', '457', '1', '1438415091');
INSERT INTO `pre_image` VALUES ('31', '1', '0', 'data/attachment/article/201510/26/135208wvavibvrhrvhvaoo.jpg', 'article', '283723', '1366', '768', '338', '0', '1445838727');

-- ----------------------------
-- Table structure for `pre_loginfailed`
-- ----------------------------
DROP TABLE IF EXISTS `pre_loginfailed`;
CREATE TABLE `pre_loginfailed` (
  `ip` char(15) NOT NULL DEFAULT '',
  `count` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ip`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_loginfailed
-- ----------------------------
INSERT INTO `pre_loginfailed` VALUES ('127.0.0.1', '1', '1422023940');

-- ----------------------------
-- Table structure for `pre_nav`
-- ----------------------------
DROP TABLE IF EXISTS `pre_nav`;
CREATE TABLE `pre_nav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  `link` varchar(200) NOT NULL DEFAULT '',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_nav
-- ----------------------------
INSERT INTO `pre_nav` VALUES ('1', '0', '前端', '###', '0', '0', '1');
INSERT INTO `pre_nav` VALUES ('2', '1', 'jquery', 'http://127.0.0.1/blog/jq', '1', '0', '1');
INSERT INTO `pre_nav` VALUES ('3', '1', 'js', 'http://127.0.0.1/blog/js', '4', '0', '1');
INSERT INTO `pre_nav` VALUES ('4', '1', 'css', 'http://127.0.0.1/blog', '3', '0', '1');
INSERT INTO `pre_nav` VALUES ('5', '0', '后端', 'http://127.0.0.1/blog', '2', '0', '1');
INSERT INTO `pre_nav` VALUES ('6', '5', 'linux', '', '1', '0', '1');
INSERT INTO `pre_nav` VALUES ('7', '5', 'php', '', '2', '0', '1');
INSERT INTO `pre_nav` VALUES ('8', '5', 'nginx', '', '3', '0', '1');
INSERT INTO `pre_nav` VALUES ('9', '0', '关于博客', '', '3', '0', '1');
INSERT INTO `pre_nav` VALUES ('15', '0', '关于本人', '#', '4', '1422714147', '1');

-- ----------------------------
-- Table structure for `pre_setting`
-- ----------------------------
DROP TABLE IF EXISTS `pre_setting`;
CREATE TABLE `pre_setting` (
  `sname` varchar(50) NOT NULL,
  `svalue` text NOT NULL,
  PRIMARY KEY (`sname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_setting
-- ----------------------------
INSERT INTO `pre_setting` VALUES ('blog', '{\"blogName\":\"Jam\'s Blog\",\"blogSubhead\":\"Hakuna Matata\",\"blogDescription\":\"php,mysql,jquery,bootstrap,jam,web\\u540e\\u7aef\",\"adminEmail\":\"jam00@vip.qq.com\",\"icp\":\"\"}');
INSERT INTO `pre_setting` VALUES ('nav', '{\"1\":{\"id\":\"1\",\"pid\":\"0\",\"name\":\"\\u524d\\u7aef\",\"link\":\"###\",\"displayorder\":\"0\",\"dateline\":\"0\",\"status\":\"1\",\"downnav\":[{\"id\":\"2\",\"pid\":\"1\",\"name\":\"jquery\",\"link\":\"http:\\/\\/127.0.0.1\\/blog\\/jq\",\"displayorder\":\"1\",\"dateline\":\"0\",\"status\":\"1\"},{\"id\":\"4\",\"pid\":\"1\",\"name\":\"css\",\"link\":\"http:\\/\\/127.0.0.1\\/blog\",\"displayorder\":\"3\",\"dateline\":\"0\",\"status\":\"1\"},{\"id\":\"3\",\"pid\":\"1\",\"name\":\"js\",\"link\":\"http:\\/\\/127.0.0.1\\/blog\\/js\",\"displayorder\":\"4\",\"dateline\":\"0\",\"status\":\"1\"}]},\"5\":{\"id\":\"5\",\"pid\":\"0\",\"name\":\"\\u540e\\u7aef\",\"link\":\"http:\\/\\/127.0.0.1\\/blog\",\"displayorder\":\"2\",\"dateline\":\"0\",\"status\":\"1\",\"downnav\":[{\"id\":\"6\",\"pid\":\"5\",\"name\":\"linux\",\"link\":\"\",\"displayorder\":\"1\",\"dateline\":\"0\",\"status\":\"1\"},{\"id\":\"7\",\"pid\":\"5\",\"name\":\"php\",\"link\":\"\",\"displayorder\":\"2\",\"dateline\":\"0\",\"status\":\"1\"},{\"id\":\"8\",\"pid\":\"5\",\"name\":\"nginx\",\"link\":\"\",\"displayorder\":\"3\",\"dateline\":\"0\",\"status\":\"1\"}]},\"9\":{\"id\":\"9\",\"pid\":\"0\",\"name\":\"\\u5173\\u4e8e\\u535a\\u5ba2\",\"link\":\"\",\"displayorder\":\"3\",\"dateline\":\"0\",\"status\":\"1\"},\"15\":{\"id\":\"15\",\"pid\":\"0\",\"name\":\"\\u5173\\u4e8e\\u672c\\u4eba\",\"link\":\"#\",\"displayorder\":\"4\",\"dateline\":\"1422714147\",\"status\":\"1\"}}');

-- ----------------------------
-- Table structure for `pre_tag`
-- ----------------------------
DROP TABLE IF EXISTS `pre_tag`;
CREATE TABLE `pre_tag` (
  `tagid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tagname` varchar(100) NOT NULL,
  `uid` mediumint(8) unsigned NOT NULL,
  `username` char(15) NOT NULL DEFAULT '',
  `articles` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '有此标签文章总数',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tagid`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_tag
-- ----------------------------
INSERT INTO `pre_tag` VALUES ('2', 'PHP', '0', '', '0', '1', '1422181828');
INSERT INTO `pre_tag` VALUES ('3', 'MySql', '0', '', '0', '1', '1422182268');
INSERT INTO `pre_tag` VALUES ('4', 'Linux', '0', '', '0', '1', '1422182285');
INSERT INTO `pre_tag` VALUES ('5', 'Bootstrap', '0', '', '0', '1', '1422182323');
INSERT INTO `pre_tag` VALUES ('6', 'JQuery', '0', '', '0', '1', '1422182343');
INSERT INTO `pre_tag` VALUES ('7', 'Nginx', '0', '', '0', '1', '1422182366');
INSERT INTO `pre_tag` VALUES ('8', '留言区', '0', '', '0', '1', '1422182413');
INSERT INTO `pre_tag` VALUES ('9', 'js', '0', '', '0', '1', '1422182725');
INSERT INTO `pre_tag` VALUES ('10', '评论区', '0', '', '0', '1', '1422182753');
INSERT INTO `pre_tag` VALUES ('11', '交流区', '0', '', '0', '1', '1422182759');
INSERT INTO `pre_tag` VALUES ('12', '关于博客', '0', '', '0', '1', '1422182769');
INSERT INTO `pre_tag` VALUES ('13', '测试1', '0', '', '0', '1', '1422188328');
INSERT INTO `pre_tag` VALUES ('14', '测试2', '0', '', '0', '1', '1422188331');
INSERT INTO `pre_tag` VALUES ('15', '测试3', '0', '', '0', '1', '1422188334');
INSERT INTO `pre_tag` VALUES ('16', '测试4', '0', '', '0', '1', '1422188338');
INSERT INTO `pre_tag` VALUES ('17', '测试5', '0', '', '0', '1', '1422189251');
INSERT INTO `pre_tag` VALUES ('18', '测试6', '0', '', '0', '1', '1422189306');
INSERT INTO `pre_tag` VALUES ('19', '测试7', '1', 'admin', '0', '1', '1447140130');
INSERT INTO `pre_tag` VALUES ('20', '测试8', '1', 'admin', '0', '1', '1449742035');
INSERT INTO `pre_tag` VALUES ('21', '测试9', '1', 'admin', '1', '1', '1449742204');
INSERT INTO `pre_tag` VALUES ('22', '测试10', '1', 'admin', '0', '1', '1449742362');
INSERT INTO `pre_tag` VALUES ('23', '测试11', '1', 'admin', '0', '1', '1449742491');
INSERT INTO `pre_tag` VALUES ('24', '测试12', '1', 'admin', '0', '1', '1449742695');
INSERT INTO `pre_tag` VALUES ('25', '测试13', '1', 'admin', '0', '1', '1449742728');
INSERT INTO `pre_tag` VALUES ('26', '测试14', '1', 'admin', '0', '1', '1449742779');
INSERT INTO `pre_tag` VALUES ('27', '测试15', '1', 'admin', '0', '1', '1449742798');
INSERT INTO `pre_tag` VALUES ('28', '测试16', '1', 'admin', '0', '1', '1449742883');
INSERT INTO `pre_tag` VALUES ('29', '测试18', '1', 'admin', '0', '1', '1449803244');

-- ----------------------------
-- Table structure for `pre_tagid_aid`
-- ----------------------------
DROP TABLE IF EXISTS `pre_tagid_aid`;
CREATE TABLE `pre_tagid_aid` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` int(8) unsigned NOT NULL COMMENT '标签id',
  `aid` int(8) unsigned NOT NULL COMMENT '文章id',
  `uid` mediumint(8) unsigned NOT NULL,
  `username` char(15) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tagid` (`tagid`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_tagid_aid
-- ----------------------------
INSERT INTO `pre_tagid_aid` VALUES ('1', '13', '18', '1', 'admin', '1449743290');
INSERT INTO `pre_tagid_aid` VALUES ('5', '14', '18', '1', 'admin', '1449802073');
INSERT INTO `pre_tagid_aid` VALUES ('6', '15', '18', '1', 'admin', '1449802077');
INSERT INTO `pre_tagid_aid` VALUES ('10', '29', '18', '1', 'admin', '1449803244');

-- ----------------------------
-- Table structure for `pre_users`
-- ----------------------------
DROP TABLE IF EXISTS `pre_users`;
CREATE TABLE `pre_users` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(15) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `email` char(32) NOT NULL DEFAULT '',
  `notice` smallint(6) unsigned NOT NULL DEFAULT '0',
  `pm` smallint(6) unsigned NOT NULL DEFAULT '0',
  `groupid` smallint(2) unsigned NOT NULL DEFAULT '0',
  `regip` char(15) NOT NULL DEFAULT '',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `lastloginip` char(15) NOT NULL DEFAULT '',
  `lastlogintime` int(10) unsigned NOT NULL DEFAULT '0',
  `salt` char(6) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_users
-- ----------------------------
INSERT INTO `pre_users` VALUES ('1', 'admin', 'f77a37575d731c22a6986d7b4ef57602', 'jam00@vip.qq.com', '10', '18', '1', '127.0.0.1', '0', '127.0.0.1', '0', 'd7b50f');
INSERT INTO `pre_users` VALUES ('2', '测试1', '3430916bfea10b8851f24cae695aa54c', 'cs1@qq.com', '0', '0', '10', '127.0.0.1', '1422005439', '127.0.0.1', '1422005439', 'aNO7Z4');
