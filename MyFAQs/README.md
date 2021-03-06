# FAQ 插件 #
实现日常FAQ的小插件，可在文章中使用短代码（不过若在使用了AMP后，最好不用).

## FAQ 数据表设计 ##
FAQ 数据表设置，使用 INNODB 引擎。

### FAQ 分类表 ###
创建 FAQ 的分类表 `_faq_categories`。用于记录 FAQ 的分类，统计当前分类下有多少 FAQ。

	CREATE TABLE `_faq_categories` (
		`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
		`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '分类名' ,
		`slug`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '别名,必须英文' ,
		`pubdate`  datetime NOT NULL COMMENT '发布时间' ,
		`editdate`  datetime NOT NULL COMMENT '修改时间' ,
		`sumfaq`  int(10) UNSIGNED NOT NULL COMMENT '统计当前分类faq数量' ,
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
		INDEX `category_id` (`category`) USING BTREE 
	)
	ENGINE=InnoDB
	DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
	ROW_FORMAT=COMPACT;

### FAQ 短代码管理表 ###
管理，追踪生成的短代码所在地方。

参考插件：
1. [Ultimate FAQ](https://wordpress.org/plugins/ultimate-faqs/ "Ultimate FAQ")
2. [Accordion FAQ](https://wordpress.org/plugins/responsive-accordion-and-collapse/ "Accordion FAQ")

创建数据表（该表可能会重新修改，因为该边设计理念有些模糊）

	CREATE TABLE `_faq_shortcode` (
		`id`  int NOT NULL AUTO_INCREMENT ,
		`short_code`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
		`code_value`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
		`location`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '记录使用的wp_posts表的ID' ,
		`pubdate`  datetime NULL ,
		`editdate`  datetime NULL ,
		PRIMARY KEY (`id`)
	)
	ENGINE=InnoDB
	DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;

## Ultimate FAQ 插件使用和分析说明 ##
安装使用后对插件的分析结果，决定是否使用插件还是重新开发。

### Ultimate FAQ 数据表 ### 
1. 插件设置 FAQ 分类目录保存在 `wp_terms` 表。
2. 插件的 FAQ 信息使用 `wp_posts` 表存储。

设置后效果图：
![Ultimate FAQs 插件设置效果图](https://i.imgur.com/Rq4vo49.png)

### 插件弊端： ###
1. FAQ 保存到 `wp_posts` 中，生成的文章链接无法打开，404 not found。
2. 文章使用短代码 `[ultimate-faqs]` 中对内容进行评论提交后，跳转的路径 404 not found ： 因为跳转的路径为 `<http://host>/ufaqs/what-delivery-formats-do-you-offer-for-your-orf-clones/#comment-11` ,而这条 FAQ 保存到 `wp_posts` 中的字段 `post_type` 的值为 `ufaq`，所以评论后，跳转的链接，WordPress 无法打开。
3. 文章使用短代码 `[ultimate-faqs]` 会将所有 FAQs 信息全部在文章中显示，暂未发现按照特定分类显示。高级功能需要付费。 `$4.00`
4. 因为使用 `wp_terms` 表存储分类目录，对获取FAQ的分类信息关系和FAQ的关系不是很友好。
5. P.S. 重点是未能体验 VIP 版本功能，不想付费。


## Accordion FAQ 插件使用和分析 ##
因 `Ultimate FAQ` 存在各种问题，功能不满意，现分析 `Accordion FAQ`。

### Accordion FAQ 数据表 ### 
1. 插件设置 FAQ 分类目录保存在 `wp_terms` 表。
2. 插件的 FAQ 信息使用 `wp_posts` 表存储。

设置后效果图：
![Accordion FAQs 插件设置效果](https://i.imgur.com/TisqxBS.png)

### 插件优缺点 ###
1. FAQ 的创建和 Ultimate FAQ 类似，但比 Ultimate FAQ 更友好，不过创建的界面太过花俏。
2. 存在的缺点和前一个一样，数据存储存在缺陷，无法快速那需求导出组装需要的数据。


取长补短，学习两个插件优点进行开发。


## 插件结构设计 ##
插件主入口文件`myfaqs.php`，定义插件常用方法.

	|-- myfaqs.php 插件主程序
	|---- assets/ css|js 等资源
	|---- classes/ 类文件目录
	|------ FaqsCategories.php faq 分类文件，管理列表，添加弹窗等
	|------ Faqs.php faq 内容管理
	|------ FaqManage.php faq 短代码使用情况
	|------ Admin.php 主程序，用于设置页面目录，样式引入等
	|---- install/ 插件安装目录
	|------ Activate.php 插件首次激活，创建相关的数据表
	|------ Deactivate.php 插件卸载，删除相关数据表
	|---- languages/   语言包目录，用于国际化
	|---- views/ 视图目录
	|---- Sql/ 存储所需数据表的SQL语句，目前用于测试，发布版删除该目录

### 版本说明 ###
版本记录信息：正在开发或未来需要添加的功能。

- v0.0.x : 开发分类和 `faqs` 列表等信息 （Category&Faq）
	- ... ...
	- v0.0.11 : 修复部分 SQL 过滤，js 等技术问题
	- v0.0.12 : 要实现列表分页, html 页码
	- v0.0.13 : 增加插件安装生成数据表和卸载删除数据表功能
	- v0.0.14 : 要实现 faqs 筛选搜索，排序等
	- v0.0.15 : post,page 设置 metaBox，进行测试，为开发短代码设置工具测试
	- v0.0.16 : 要实现 category 列表父子分类效果 -- 参考 posts 的 category 列表显示
	- ... ... 
	- v0.0.&lt;latest> : ... ...
- ... ...
- v0.1.x : 开发短代码使用问题 （Traces）
	- v0.1.0 : 修改数据表，增加字段 `code_value`
	- v0.1.1 : 模板短代码效果实现
	- v0.1.2 : post | page 设置短代码 metabox 界面及添加功能
	- v0.1.3 : 实现短代码实现的数据查询与组装
	- v0.1.4 : metabox 修改、删除功能实现
	- v0.1.5 : 管理后台 Traces 实现
	- ... ...
	- v0.1.&lt;latest> : 实现短代码使用兼容 WAP 端（非常重要）
- ... ...
- v0.2.x : 客户端弹窗交换设计 API 调用等。
	- ... ...
- ... ...
- v1.x.x : 正式使用版本
- ... ...

### 功能需求 ###
基本增删改查功能，需要增加导出`JSON`文件的功能--faq 列表；或者需要编写`API`调用返回所有的 `title`,`answer`信息。（当前功能较为重要，需导出数据到`AnyQ`或`RasaHQ`）

MyFAQs 分类和问题列表页中附带增加搜索功能。

客户端（机器客服）实现需依赖 `AnyQ` 或 `RasaHQ` 实现前端界面即可。
