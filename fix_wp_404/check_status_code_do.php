<?php
/**
 * 批量检查链接的状态
 */
require_once(dirname(__FILE__).'/../wp-blog-header.php');
if(!is_user_logged_in()){
	header("location:/wp-login.php?redirect_to=".urlencode($_SERVER['PHP_SELF']));
	exit();
}
require_once(dirname(__FILE__).'/functions.php');
header('HTTP/1.1 200 OK');


$fix_wp_404 = new FiX_WP_404();
// $fix_wp_404->change_config('DEBUG' , false);//强制改动配置，关闭调试状态

if($_REQUEST['check_url']){
	echo json_encode(array("status"=>"ok" , "status_code"=>$fix_wp_404->check_url_status_code($_REQUEST['check_url']) , "index"=>$_REQUEST['index']));
}else{
	echo json_encode(array("status"=>"fail" , "info"=>"No URL" , "index"=>$_REQUEST['index']));
}
?>