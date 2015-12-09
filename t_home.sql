/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-12-08 10:15:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `t_home`
-- ----------------------------
DROP TABLE IF EXISTS `t_home`;
CREATE TABLE `t_home` (
  `label_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label_name` varchar(20) NOT NULL,
  `id` int(8) unsigned NOT NULL,
  `arrt_type` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`label_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_home
-- ----------------------------
INSERT INTO `t_home` VALUES ('1', '羽毛球', '15', '1');
INSERT INTO `t_home` VALUES ('2', '篮球', '14', '0');
INSERT INTO `t_home` VALUES ('3', '足球', '13', '1');
INSERT INTO `t_home` VALUES ('4', '乒乓球', '12', '1');
INSERT INTO `t_home` VALUES ('5', '网球', '0', '0');
