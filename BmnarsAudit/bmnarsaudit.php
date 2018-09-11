<?php 
/**
 * Plugin Name: Bmnars Audit
 * Plugin URI: https://github.com/huimingdeng/genecopoeia/tree/master/BmnarsAudit
 * Description: 生命的奥秘——爬虫文章审核发布插件。
 * Version: 1.0.0
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
  add_submenu_page(
    'BmnarsAudit',
    'BmnarsRecheck',
    'Bmnars recheck',
    'manage_options',
    'BmnarsRecheck',
    'render_bmnars_recheck_page'
  );
  
}

function render_bmnars_list_page(){
	include 'Views/bmnars-list.php';
}

function render_bmnars_recheck_page(){
  include 'Views/bmnars-recheck.php';
}

add_action('admin_menu','add_Bmnars_menu');

function plugin_activation_cretable(){
	global $wpdb;
	$charset_collate = '';

    if (!empty($wpdb->charset)) {
      $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    }

    if (!empty( $wpdb->collate)) {
      $charset_collate .= " COLLATE {$wpdb->collate}";
    }

	$sql = <<<EOF
DROP TABLE IF EXISTS `_cs_audit_bmnars_status`;
CREATE TABLE `_cs_audit_bmnars_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crawler_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `audit_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset_collate;
EOF;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql ,true);
}

register_activation_hook(__FILE__, 'plugin_activation_cretable');

function plugin_uninstall_deltable(){
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS `_cs_audit_bmnars_status`;");
}
register_uninstall_hook( __FILE__, 'plugin_uninstall_deltable' );


function Bmnars_Audit_init() {
    load_plugin_textdomain( 'BmnarsAudit', false, dirname( plugin_basename( __FILE__ ) ) );
}
add_action( 'plugins_loaded', 'Bmnars_Audit_init' );
