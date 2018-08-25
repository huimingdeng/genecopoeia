<?php 
/**
 * Plugin Name: Bmnars Audit
 * Plugin URI: https://github.com/huimingdeng/genecopoeia/tree/master/BmnarsAudit
 * Description: 生命的奥秘——爬虫文章审核发布插件。
 * Version: 0.0.3
 * Author: DHM (huimingdeng)
 * Author URI: https://github.com/huimingdeng/
 */
global $wpdb;

function add_Bmnars_menu(){
	add_menu_page(
      'BmnarsAudit',
      'Bmnars Audit',
      'edit_pages',
      'BmnarsAudit',
      'render_bmnars_list_page'
    );
}

function render_bmnars_list_page(){
	include 'Views/bmnars-list.php';
}

add_action('admin_menu','add_Bmnars_menu');

function Bmnars_Audit_init() {
    load_plugin_textdomain( 'BmnarsAudit', false, dirname( plugin_basename( __FILE__ ) ) );
}
add_action( 'plugins_loaded', 'Bmnars_Audit_init' );
