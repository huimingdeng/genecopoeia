/*
Navicat MySQL Data Transfer

Source Server         : user-root@localhost@phpStudy2018
Source Server Version : 50725
Source Host           : localhost:3306
Source Database       : cn_web

Target Server Type    : MYSQL
Target Server Version : 50725
File Encoding         : 65001

Date: 2019-03-25 16:45:09
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _faq_categories
-- ----------------------------
INSERT INTO `_faq_categories` VALUES ('1', 'CRISPR', 'crispr', '2019-03-18 01:09:07', '2019-03-18 05:54:28', '8', '0');
INSERT INTO `_faq_categories` VALUES ('2', 'Lentivirus', 'lentivirus', '2019-03-18 01:09:19', '2019-03-18 05:55:11', '1', '0');
INSERT INTO `_faq_categories` VALUES ('3', 'AAV', 'aav', '2019-03-18 02:26:03', '2019-03-18 09:19:19', '0', '0');
INSERT INTO `_faq_categories` VALUES ('4', 'FAQs', 'faqs', '2019-03-18 02:43:57', '2019-03-18 02:43:57', '10', '0');
INSERT INTO `_faq_categories` VALUES ('5', 'CLONE', 'clone', '2019-03-18 03:23:18', '2019-03-18 03:23:18', '0', '0');
INSERT INTO `_faq_categories` VALUES ('6', 'orf clone', 'orf-clones', '2019-03-18 05:55:45', '2019-03-18 09:19:31', '2', '5');
INSERT INTO `_faq_categories` VALUES ('7', 'TALE', 'talen', '2019-03-21 07:07:58', '2019-03-21 07:07:58', '1', '0');
INSERT INTO `_faq_categories` VALUES ('8', 'Safe Harbor', 'safe-harbor', '2019-03-21 07:16:04', '2019-03-21 07:16:04', '1', '0');
INSERT INTO `_faq_categories` VALUES ('9', 'Basic', 'basic', '2019-03-22 08:43:35', '2019-03-22 08:43:35', '0', '0');
INSERT INTO `_faq_categories` VALUES ('10', 'Luciferase Assays', 'luciferase-assays', '2019-03-22 08:44:39', '2019-03-22 08:44:39', '0', '0');
INSERT INTO `_faq_categories` VALUES ('11', 'qPCR arrays and reagents', 'qpcr-arrays-and-reagents', '2019-03-22 08:45:42', '2019-03-22 08:45:42', '0', '0');
