/*
Navicat MySQL Data Transfer

Source Server         : user-genec1-localhost@wamp-or-phpStudy2018
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : cn_web

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-01-17 17:18:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for _faq_shortcode
-- ----------------------------
DROP TABLE IF EXISTS `_faq_shortcode`;
CREATE TABLE `_faq_shortcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short_code` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL COMMENT '记录使用的wp_posts表的ID',
  `pubdate` datetime NOT NULL,
  `editdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
