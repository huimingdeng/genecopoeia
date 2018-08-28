<?php
/**
 * 由于运行时间较长，请在CLI模式下运行
 * 从problem_post.php文件加载需要检查的post_id。（如果没有，则从wp_post中取出全部的post_id）
 * 本程序会从revision/after中找到post_id对应的文件，读取内容并更新wp_posts表
 * 
 * 本程序貌似没有什么用了
 */
$time_s = microtime(true);
require_once(dirname(__FILE__).'/functions.php');
require_once(dirname(__FILE__).'/problem_post.php');

$fix_wp_404 = new FIX_WP_404();

//read file to content
$i=0;
$sum = count($problem_post_id_list);
foreach ($problem_post_id_list as $post_id) {
	$content = file_get_contents('revision/after/' . $post_id . '.html');

	if($content){
		$fix_wp_404->update_post_content( array(array('ID'=> $post_id, 'post_content'=>$content , 'is_different'=>true) ) );
	}

	$fix_wp_404->work_status(++$i , $sum);
	// sleep(3);
}

$time_e = microtime(true);
echo "Time use : " . ($time_e - $time_s) ."\n\n";