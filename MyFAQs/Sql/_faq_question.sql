/*
Navicat MySQL Data Transfer

Source Server         : root@localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : cn_web

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-03-20 22:03:39
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
  `category` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `issue` (`title`),
  KEY `catagory_id` (`category`),
  CONSTRAINT `catagory_id` FOREIGN KEY (`category`) REFERENCES `_faq_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
