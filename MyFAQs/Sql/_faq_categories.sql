/*
Navicat MySQL Data Transfer

Source Server         : root@localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : cn_web

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-03-12 14:20:46
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for _faq_categories
-- ----------------------------
DROP TABLE IF EXISTS `_faq_categories`;
CREATE TABLE `_faq_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '分类名',
  `slug` varchar(255) NOT NULL COMMENT '别名,必须英文',
  `pubdate` datetime NOT NULL COMMENT '发布时间',
  `editdate` datetime NOT NULL COMMENT '修改时间',
  `sumfaq` int(10) unsigned NOT NULL COMMENT '统计当前分类faq数量',
  `parent` int(10) DEFAULT NULL COMMENT '父级分类',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _faq_categories
-- ----------------------------
INSERT INTO `_faq_categories` VALUES ('1', 'FAQ', 'faq', '2019-03-12 13:33:33', '2019-03-12 13:33:41', '0', null);
