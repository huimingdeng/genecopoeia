<?php
/**
 * 由于运行时间较长，请在CLI模式下运行
 * 从problem_post.php文件加载需要检查的post_id。（如果没有，则从wp_post中取出全部的post_id）
 * 检查每个post_id对应的post_content中的属于本域名下的链接（a标签的href属性），判断其是否失效（404状态）
 * 删除404状态的链接，使用tidy库整理html文本
 * 更新post_content到数据库
 */
$time_s = microtime(true);
require_once(dirname(__FILE__).'/functions.php');
require_once(dirname(__FILE__).'/problem_post.php');
$fix_wp_404 = new FIX_WP_404();

if(!isset($problem_post_id_list) || count($problem_post_id_list)==0){
	$problem_post_id_list = array();
	foreach ($fix_wp_404->get_post_content('all', 'all' ,array('ID')) as $value) {
		array_push($problem_post_id_list , $value['ID']);
	};

}

$i=0;
$sum = count($problem_post_id_list);
foreach ($problem_post_id_list as $post_id) {
	$id_content_array = $fix_wp_404->get_post_content_from_post_id($post_id);
	$new_id_content_array = $fix_wp_404->fix_post_content_404($id_content_array);
	$fix_wp_404->update_post_content($new_id_content_array);
	$fix_wp_404->work_status(++$i , $sum);
}

$time_e = microtime(true);
echo "Time use : " . ($time_e - $time_s) ."\n\n";