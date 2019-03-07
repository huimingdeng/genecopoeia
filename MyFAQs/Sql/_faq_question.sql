/*
Navicat MySQL Data Transfer

Source Server         : user-genec1-localhost@wamp-or-phpStudy2018
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : cn_web

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-01-17 17:19:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for _faq_question
-- ----------------------------
DROP TABLE IF EXISTS `_faq_question`;
CREATE TABLE `_faq_question` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT 'issue',
  `answer` text NOT NULL COMMENT 'answer',
  `pubdate` datetime NOT NULL,
  `editdate` datetime NOT NULL,
  `catagory` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `issue` (`title`),
  KEY `catagory_id` (`catagory`),
  CONSTRAINT `catagory_id` FOREIGN KEY (`catagory`) REFERENCES `_faq_catagories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
