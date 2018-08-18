<?php 
/*
 Plugin Name: lentiManager
 Plugin URI: #
 Description: _cs_lenti_collection table management, If you have changed the price, please test and inform the IT department if there are any bugs.
 Version: 0.5.8
 Author: DHM
 Author URI: #
 License: none
 */
global $wpdb;

function add_lenti_menu(){
  global $user_login;
  $allowed_user = get_option('lenti_manager_permission');
  $allowed_user_options = json_decode($allowed_user,true);
  if(in_array($user_login, $allowed_user_options)){
  	add_menu_page(
      'lentiManager',
      'lenti Manager',
      'edit_pages',
      'lentiManager',
      'render_lenti_list_page'
    );
    add_submenu_page(
      'lentiManager',
      'lentiRecycled',
      'lenti Recycled',
      'edit_pages',
      'lentiRec',
      'render_lenti_recycled_page'
    );
    add_submenu_page(
      'lentiManager',
      'LogsManager',
      'Logs Manager',
      'edit_pages',
      'logsManager',
      'render_log_manager_page'
    );
  }
  if($user_login=='admin'){
    add_submenu_page(
      'lentiManager',
      'lentiAssign',
      'lenti Assign',
      'edit_pages',
      'lentiAssign',
      'render_lenti_assign_page'
    );
    add_submenu_page(
      'lentiManager',
      'FilesManager',
      'Files Manager',
      'edit_pages',
      'filesManager',
      'render_files_manager_page'
    );
  }
}
// 慢病毒管理页
function render_lenti_list_page(){
	include_once("Views/lenti-list.php");
}
// 权限分配页
function render_lenti_assign_page()
{
  include_once("Views/lenti-assign.php");
}
// 慢病毒回收站
function render_lenti_recycled_page(){
  include_once("Views/lenti-recycled.php");
}
// 文件管理页
function render_files_manager_page(){
  include_once("Views/files-list.php");
}
function render_log_manager_page(){
  include_once("Views/logs-list.php");
}

add_action('admin_menu','add_lenti_menu');

function lenti_manager_init() {
    load_plugin_textdomain( 'lentiManager', false, dirname( plugin_basename( __FILE__ ) ) );
}
add_action( 'plugins_loaded', 'lenti_manager_init' );


// 创建回收站和备份表
function plugin_activation_cretable() {
    global $wpdb;
    /*
     * We'll set the default character set and collation for this table.
     * If we don't do this, some characters could end up being converted 
     * to just ?'s when saved in our table.
     */
    $charset_collate = '';

    if (!empty($wpdb->charset)) {
      $charset_collate = "DEFAULT CHARACTER SET utf8";
    }

    if (!empty( $wpdb->collate)) {
      $charset_collate .= " COLLATE utf8_general_ci";
    }

    $sql = <<<EOF
DROP TABLE IF EXISTS `_cs_lenti_collection_delete_back`;
CREATE TABLE `_cs_lenti_collection_delete_back` (
  `catalog` varchar(32) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `volume` varchar(12) DEFAULT NULL,
  `titer` varchar(64) DEFAULT NULL,
  `titer_description` varchar(255) DEFAULT NULL,
  `purity` varchar(32) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `vector` varchar(32) DEFAULT NULL,
  `delivery_format` varchar(255) DEFAULT NULL,
  `delivery_time` varchar(32) DEFAULT NULL,
  `symbol_link` varchar(128) DEFAULT NULL,
  `pdf_link` varchar(128) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `priority` int(8) unsigned DEFAULT NULL,
  `del_time` datetime NOT NULL,
  PRIMARY KEY (`catalog`,`del_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOF;
    /*$sql.= <<<EOF
DROP TABLE IF EXISTS `_cs_lenti_collection_back`;
CREATE TABLE `_cs_lenti_collection_back` (
  `catalog` varchar(32) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `volume` varchar(12) DEFAULT NULL,
  `titer` varchar(64) DEFAULT NULL,
  `titer_description` varchar(255) DEFAULT NULL,
  `purity` varchar(32) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `vector` varchar(32) DEFAULT NULL,
  `delivery_format` varchar(255) DEFAULT NULL,
  `delivery_time` varchar(32) DEFAULT NULL,
  `symbol_link` varchar(128) DEFAULT NULL,
  `pdf_link` varchar(128) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `priority` int(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`catalog`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOF; version 0.5.6 above*/
    // 备份数据
    $sql.= <<<EOF
INSERT INTO _cs_lenti_collection_back SELECT * FROM _cs_lenti_collection;
EOF;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql ,true);
    $permission_a = array('admin','huimingdeng');
    $permission = json_encode($permission_a);
    update_option('lenti_manager_permission',$permission);
}
register_activation_hook(__FILE__, 'plugin_activation_cretable');

function plugin_deactivation_deltable(){
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS `_cs_lenti_collection_delete_back`;");
    /*DROP TABLE IF EXISTS `_cs_lenti_collection_back`; *///version 0.5.6 above
    delete_option('lenti_manager_permission');
}
// register_deactivation_hook(__FILE__, 'plugin_deactivation_deltable');
register_uninstall_hook( __FILE__, 'plugin_deactivation_deltable' );
