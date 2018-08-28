<?php 
/*
 Plugin Name: Posts Search Bar
 Plugin URI: #
 Description: Search Bar using [list_search_bar_plugs class='1-1'], class is postID-number
 Version: 1.3.8
 Author: DHM
 Author URI: #
 License: none
 */

function list_search_bar_plugs_admin($content){
	//使用ajax方式引入管理文件
	wp_enqueue_script('list_search_bar_plugs_script',plugins_url('js/entrance.js.php?post='.$_GET['post'],__FILE__),'jquery');
}
add_action( 'load-post.php', 'list_search_bar_plugs_admin' ,100 );

function load_background_script_and_style_list_search_bar_plugs(){
	if(strpos($_SERVER['SCRIPT_NAME'] , "/wp-admin/post.php") !== false){
		wp_enqueue_style('list_search_bar_plugs_style',plugins_url('css/bootstrap.min.css',__FILE__),'jquery');
		wp_enqueue_style('list_search_bar_plugs_style_v1',plugins_url('css/list_search_bar_plugs_style.css',__FILE__),'jquery');
	}
}

add_action('admin_enqueue_scripts', 'load_background_script_and_style_list_search_bar_plugs');


function list_search_bar_plugs($atts){
	global $wpdb;
	//type = product,format,species,all
	$atts = shortcode_atts(
		array(
			'class'=>1,
			'title'=>'Enter Gene Symbol or Accession No.',
			'width'=>300
		),$atts);
	$list_search_bar_plugs_options = get_option('list_search_bar_plugs');
	$list_search_bar_plugs_options_array = json_decode($list_search_bar_plugs_options,true);
	$tags_arr = $list_search_bar_plugs_options_array['list_search_bar_plugs_'.$atts['class']];
	if(!empty($tags_arr)){
		$product_type = $tags_arr['product_type'];
		ob_start();
		include ('list_search_bar_plugs_tmplate.php');
		$output = ob_get_clean();
		return $output;
	}

}
// [list_search_bar_plugs class='?'] ?=post_ID
add_shortcode('list_search_bar_plugs', 'list_search_bar_plugs');
