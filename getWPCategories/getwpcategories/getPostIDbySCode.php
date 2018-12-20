<?php 
error_reporting(255);
/**
 * Gets the plugin short code article ID
 * 获取文章中含有以下短代码的文章ID，用于设计新的 list_manager 插件，用于追踪管理 list_* 类插件的使用情况，便于以后设置后台修改对应文章信息（eg. _cs_misc_product_class 表和 _cs_misc_product）
 * 查询获取必须字段：
 * 		ID：用于链接生成和确定短代码使用位置
 * 	 	post_content：用于正则统计一篇文章中有多少个短代码
 *    	post_title:方便人员追踪
 *
 * [list_kits class='?'] => list_kits_and_reagents/
 * [list_misc class='?'] => list_misc_product/
 * [list_misc2 class='?'] => list_misc_product2/
 * [list_misc_product_plus class='?'] => list_misc_product_plus/ ==> 该插件已经有管理后台，可以不查询，如果设计的 list_manager 插件需要总览则需要查询
 * [list_particles class='?'] => list_particles/
 * [list_anti class='?' discountPrice='?'] => list_product_anti_tag/
 * ... ...
 *
 * 该脚本用于分析，具体功能暂没有实现
 */
require_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."wp-config.php");
global $wpdb;
$plugins_short_code_names = array(
	"list_kits",
	"list_misc",
	"list_misc2",
	"list_misc_product_plus",
	"list_particles",
	"list_anti",
	"list_avitag",
	"list_proteins",//[list_proteins] no class
	"list_qpcr_arrays",
	"list_reagent",
	"list_synthesis_kit",
	"list_vector",//该短代码实现查询的数据表和上面不一样，查的是 vector 表
);

$short_code_name = 'list_misc';
// 排除 list_proteins 之外的SQL语句
$query_posts = "SELECT ID,post_title,post_content FROM wp_posts WHERE `post_status` = 'publish' AND `post_type` = 'post' AND `post_content` LIKE '%[{$short_code_name} %class%' ORDER BY post_date DESC";
// list_proteins:
// $query_posts = "SELECT ID,post_title,post_content FROM wp_posts WHERE `post_status` = 'publish' AND `post_type` = 'post' AND `post_content` LIKE '%[list_proteins]%' ORDER BY post_date DESC";
	

// 获取到的 post_content 需要 preg_match_all() 函数进行正则匹配获取：eg. list_misc
$sql = $query_posts;
$results = $wpdb->get_results($sql,ARRAY_A);
print_r($results);
