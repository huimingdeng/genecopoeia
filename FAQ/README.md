# FAQ 插件 #
实现日常FAQ的小插件，可在文章中使用短代码（不过若在使用了AMP后，最好不用).

## FAQ 数据表设计 ##
FAQ 数据表设置，使用 INNODB 引擎。

### FAQ 分类表 ###
创建 FAQ 的分类表 `_faq_catagories`。用于记录 FAQ 的分类，统计当前分类下有多少 FAQ。

	CREATE TABLE `_faq_catagories` (
		`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
		`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '分类名' ,
		`slug`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '别名,必须英文' ,
		`pubdate`  datetime NOT NULL COMMENT '发布时间' ,
		`editdate`  datetime NOT NULL COMMENT '修改时间' ,
		`sumfaq`  int(10) UNSIGNED ZEROFILL NOT NULL COMMENT '统计当前分类faq数量' ,
		`parent`  int(10) NULL DEFAULT NULL COMMENT '父级分类' ,
		PRIMARY KEY (`id`),
		UNIQUE INDEX `name` (`name`) USING BTREE 
	)
	ENGINE=InnoDB
	DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
	AUTO_INCREMENT=1
	ROW_FORMAT=COMPACT;

### FAQ 信息记录表 ###
创建 FAQ 信息记录表 `_faq_question`。用于记录日常的 FAQ 。

	CREATE TABLE `_faq_question` (
		`id`  int(11) NOT NULL ,
		`title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'issue' ,
		`answer`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'answer' ,
		`pubdate`  datetime NOT NULL ,
		`editdate`  datetime NOT NULL ,
		`catagory`  int(10) UNSIGNED NULL DEFAULT NULL ,
		PRIMARY KEY (`id`),
		FOREIGN KEY (`catagory`) REFERENCES `_faq_catagories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
		UNIQUE INDEX `issue` (`title`) USING BTREE ,
		INDEX `catagory_id` (`catagory`) USING BTREE 
	)
	ENGINE=InnoDB
	DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
	ROW_FORMAT=COMPACT;

### FAQ 短代码管理表 ###
管理，追踪生成的短代码所在地方。


暂停开发，考虑使用插件 [Ultimate FAQ](https://wordpress.org/plugins/ultimate-faqs/ "Ultimate FAQ")
