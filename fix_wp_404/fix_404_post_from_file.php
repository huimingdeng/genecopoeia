<?php
/**
 * 由于运行时间较长，请在CLI模式下运行，请先运行check_404.php程序，程序会检查并生成比对文件。
 * 最好比对一下文件改动前后有没有什么问题，再运行本程序
 * 本程序从problem_post.php文件加载需要检查的post_id。（如果没有，则从wp_post中取出全部的post_id）
 * 检查check/after/目录下此post_id命名的html文件，并读入内容，然后更新到wp_posts数据表中
 */
$time_s = microtime(true);
require_once(dirname(__FILE__).'/functions.php');
require_once(dirname(__FILE__).'/problem_post.php');

$fix_wp_404 = new FIX_WP_404();
//create some paths
// $fix_wp_404->mk_check_path();

if(!isset($problem_post_id_list) || count($problem_post_id_list)==0){
	$problem_post_id_list = array();
	foreach ($fix_wp_404->get_post_content('all', 'all' ,array('ID') , true) as $value) {
		array_push($problem_post_id_list , $value['ID']);
	};

}


// $i=0;
// $sum = count($problem_post_id_list);
foreach ($problem_post_id_list as $post_id) {
	$filename = 'check/after/' . $post_id . '.html';
	if(file_exists($filename)){
		$content = file_get_contents($filename);//read file to content
		if($content){
			$fix_wp_404->update_post_content( array(array('ID'=> $post_id, 'post_content'=>$content , 'is_different'=>true) ) );
		}
	}else{
		printf( "File < %s > not exists.\n" ,$filename );
	}
	// $fix_wp_404->work_status(++$i , $sum);
}

$time_e = microtime(true);
echo "Time use : " . ($time_e - $time_s) ."\n\n";