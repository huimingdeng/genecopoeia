<?php
/**
 * 由于运行时间较长，请在CLI模式下运行
 * 从problem_post.php文件加载需要检查的post_id。（如果没有，则从wp_post中取出全部的post_id）
 * 保存数据到文件logs/queued_post.txt
 * 命令行使用：
 * php multi_init.php ：运行本程序
 */
$time_s = microtime(true);
require_once(dirname(__FILE__).'/functions.php');
// require_once(dirname(__FILE__).'/problem_post.php');

$fix_wp_404 = new FIX_WP_404();
//create some paths
$fix_wp_404->mk_check_path();


if(!isset($problem_post_id_list) || count($problem_post_id_list)==0){
	$problem_post_id_list = array();
	foreach ($fix_wp_404->get_post_content('all', 'all' ,array('ID') , false) as $value) {
		array_push($problem_post_id_list , $value['ID']);
	};

}
// print_r( $problem_post_id_list );
// print_r(implode("\n" , $problem_post_id_list));
$fix_wp_404->set_queued_post_id($problem_post_id_list);