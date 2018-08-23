<?php
/*
Plugin Name: PIO Related Posts
Plugin URI: http: #
Description: 从PIO中获取并展示相关产品的链接（相关产品文章推荐）
Version: 1.0
Author: dhm
Author URI: #
License: GPL2
*/

/* == 插件结构=================================================
 * pio-related-product.php：主文件，注册菜单，加载JS，显示浮动窗
 * pio-related-manager.php：前端，管理配置的页面
 * pio-related-ajax.php：后台，处理manager页面发出的ajax请求
 * ============================================================ */

//引入该核心文件才能使用wpdb类
require_once(dirname(__FILE__)."/../../../wp-config.php");
// wp中直接引入核心文件会提示404，需要此语句来强制标记提示200才能成功引入
header('HTTP/1.1 200 OK');

function testRelatedCurl() {
	//从数据库中取出配置选项num
	if(!$per_num){
		$per_num = get_option('pio_related_product_per_num',4);
	}
	//此处暂时用curl模拟向PIO服务器发起POST请求得到数据
	// $uri = "http://localhost:8000/queries.json";
	$uri = "http://192.168.8.4:8003/queries.json";
	if(preg_match("/genecopoeia.com/",$_SERVER['SERVER_NAME'])) {
		$uri = "http://othello.genecopoeia.com:8003/queries.json";
	}
	$header = "Content-Type: application/json";
	$data = '{ "item": "'.get_permalink().'","itembias":4, "num": 4 }';//  SPDA-D100
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $uri );
	curl_setopt ( $ch, CURLOPT_POST, 1 );
	curl_setopt ( $ch, CURLOPT_HEADER, $header );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
	$curl_output = curl_exec ( $ch );//拥有itemScores数组的json对象
	curl_close ( $ch );
	$output = json_decode($curl_output,true);//转换成数组
	$output = $output["itemScores"];//提取item和score
	return $output;
}

//通过JS获取Item
function getItemFromCheckboxs(){
	if(is_single()){
		$status = testRelatedCurl();//先判断pio服务器是否有响应
		// $status=true;
		if($status){//有响应才加载js
			wp_register_style('pio-related-css',plugins_url('pio-related-posts/css/pio-related-css.css')); 
			wp_register_script('pio-related-posts',plugins_url('pio-related-posts/js/pio-related-posts.js'));
		}
		wp_enqueue_style('pio-related-css');
		wp_enqueue_script('pio-related-posts');
	}
}

//加载JS
add_action('wp_enqueue_scripts', 'getItemFromCheckboxs');

//配置菜单
function pio_add_menu_items1(){
	add_menu_page(
		'PIO Related Url',//页面标题
		'PIO Related Url',//菜单标题
		'manage_options',//所需权限
		'PIO Related',//页面别名
		'pio_related_manager'//回调函数
	);
}

//注册菜单
add_action('admin_menu', 'pio_add_menu_items1');

//引入菜单界面
function pio_related_manager(){
	include_once('pio-related-manager.php');
}

//加载显示无序列表，显示相关连接
function getRelatedProductUrl(){
	$status = testRelatedCurl();//先判断pio服务器是否有响应
	if($status){
		$display_num = get_option('pio_related_product_display_num',4);
		$pioContent = "\r\n";
		$pioContent.= '<div id="related_posts" class="pioRelatedUrl" style="display:none">'."\r\n";
		$pioContent.= '     <h2>Related products</h2>'."\r\n";
		$pioContent.= '     <ul id="product-related-url">'."\r\n";
		
		$pioContent.= '     </ul>'."\r\n";
		$pioContent.= '</div>'."\r\n";
		if(is_single()){
			echo $pioContent;
		}
	}
}
	// add_action('wp_footer','getRelatedProductUrl');

?>