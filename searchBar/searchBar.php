<?php 
/*
 Plugin Name: searchBar
 Plugin URI: #
 Description: Search Bar using [list_searchBar class='1' type='all'], type include product,format,species,all.
 Version: 0.5
 Author: DHM
 Author URI: #
 License: none
 */

function searchBar($atts){
	global $wpdb;
	//type = product,format,species,all
	$atts = shortcode_atts(
		array(
			'class'=>1,
			'type'=>'product'
		),$atts);
	$sql = "SELECT sn,classify_name,item_display_name FROM `left_menu_option` WHERE menu_name='search3' ORDER BY classify_order, item_order;";
	$menu = $wpdb->get_results($sql);
	$product_type = $format = $species = $type = array();
	foreach ($menu as $k => $v) {
		if($v->classify_name=='Product Type'){
			$product_type[$v->sn] = $v->item_display_name;
		}
		if($v->classify_name=='Format'){
			$format[$v->sn] = $v->item_display_name;
		}
		if($v->classify_name=='Species'){
			$species[$v->sn] = $v->item_display_name;
		}
	}
	ob_start();
	include ('searchBar_tmplate.php');
	$output = ob_get_clean();
	return $output;
}

// add_action('wp_footer','searchBar');
add_shortcode('list_searchBar', 'searchBar');