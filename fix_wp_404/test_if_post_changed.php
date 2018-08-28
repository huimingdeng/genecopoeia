<?php 
/**
 * 检查post的备份文章是否还是最新的
 * post被改坏了，需要从备份文件中还原；
 * 如果post在wordpress后台有修改，会产生历史版本；
 * 如果没有历史版本，那么我们可以还原这个post
 * 否则需要标记出来
 */
$time_s = microtime(true);
require_once(dirname(__FILE__).'/functions.php');
require_once(dirname(__FILE__).'/problem_post.php');
$fix_wp_404 = new FIX_WP_404();
$fix_wp_404->change_config('DEBUG' , false);//强制改动配置，关闭调试状态

$checktime = '2017-12-15 01:29:00';

if(!file_exists('revision')){
	mkdir('revision' , 0777 , true);
	mkdir('revision/before' , 0777 , true);
	mkdir('revision/after' , 0777 , true);
}

$i=0;
$sum = count($problem_post_id_list);
foreach ($problem_post_id_list as $post_id) {


	$old_content = $fix_wp_404->get_post_copy_content_from_post_id($post_id);
	$new_content = $fix_wp_404->get_post_content_from_post_id($post_id);
	file_put_contents('revision/before/' . $post_id . '.html' , $fix_wp_404->tidy_content( $old_content[0]['post_content']));
	file_put_contents('revision/after/' . $post_id . '.html' , $fix_wp_404->tidy_content( $new_content[0]['post_content']));

	$data = $fix_wp_404->get_post_new_revisions($post_id , $checktime);

	//save revision to file
	foreach ($data as $key => $value) {
		if($value){

			$post_content = $fix_wp_404->get_post_content_from_post_id($value['post_id'] );
			$revision_content = $fix_wp_404->get_post_content_from_post_id($value['revision_id'] );
			file_put_contents( 'revision/' . $value['post_id'].'.html' ,  $fix_wp_404->tidy_content( $post_content[0]['post_content']) );
			file_put_contents( 'revision/' . $value['post_id'].'_'.$value['revision_id'].'.html' ,  $fix_wp_404->tidy_content( $revision_content[0]['post_content']) );
		}
	}

	$message = $fix_wp_404->_2d_array_to_string($data , "\n" , ',');
	$fix_wp_404->save_message_to_file( $message );
	$fix_wp_404->work_status(++$i , $sum);
}

$time_e = microtime(true);
echo "Time use : " . ($time_e - $time_s) ."\n\n";
?>