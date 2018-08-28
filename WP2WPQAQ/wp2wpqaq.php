<?php 
/**
 * Plugin Name:	WP2WPQAQ
 * Plugin URI:	#
 * Description: Synchronize articles between two WordPress sites.
 * Author: DHM(huimingdeng)
 * Author URI: http://blog.csdn.net/u014759763
 * Version: 0.0.2
 */
// Nothing in this plugin works outside of the admin area, so don't bother loading it if we're not looking at the admin panel...
if(is_admin()){

	define( 'WP_OPTIONS_VERSION', 1 );

	add_action( 'admin_menu', 'wp2wp_menu' );//Add Menu
	// add_action('admin_head','wp2wp_active_style');
	add_action( 'admin_init', 'wp2wp_register_settings' );
	add_action('add_meta_boxes','my_meta_box');
	add_filter( 'plugin_action_links', 'wp2wp_settings_link', 10, 2 );
	
	// add_action('add_meta_boxes', array('sync-details-container', 'wp2wp_view'));

	// Activate the style sheets and scripts.
	function wp2wp_active_style(){
		$css = plugins_url('assets/css/',__FILE__).'wp2wp-admin.css';
		$js = plugins_url('assets/js/',__FILE__).'wp2wp-sync.js';//echo $js;
		wp_register_script('wp2wp-sync',$js,array());
		wp_register_style('wp2wp-admin',$css,array());
		wp_enqueue_script('wp2wp-sync');
		wp_enqueue_style('wp2wp-admin');
	  	
	}
	// add a meta box...
	function my_meta_box(){
		$screen = get_current_screen();
		if('add' !== $screen->action){
			add_meta_box('wp2wp_view','WP2WPQAQ-synchronize','wp2wp_view','post','side','high');
			add_action('admin_enqueue_scripts','wp2wp_active_style');
		}
	}

	// Create a template...
	function wp2wp_view(){
		?>
		<div id="sync-details-container">
			<div class="sync-span-body"><span><?php _e('Push content to Target: <br>', 'wp2wpqaq');?><span title="配置页设置的同步对象."><b><?php $server=get_option("wp2wp_server_option"); echo $server['host'];?></b></span></span></div>
			<div id="sync-content">
			</div>
			<button type="button" class="btn button-primary" onclick="wp2wppush(<?php the_ID();?>)">Push to Target</button>
			<input type="hidden" name="ajaxurl" id="ajaxurl" value="<?php echo WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)) . '/wp2wp-ajax.php'; ?>">
		</div>
		<?php
	}
	// Add submenu...
	function wp2wp_menu() {
		add_submenu_page( 'options-general.php', 'WP2WPQAQ Settings', 'WP2WPQAQ', 'manage_options', 'wp2wpqaq', 'wp2wp_setting' );
	}
	// Setting page...
	function wp2wp_setting(){
		$css_uri=plugins_url('assets/css/wp2wp-admin.css',__FILE__);
	  	// wp_enqueue_style('wp2wp-admin.css',$css_uri);
		require_once( 'wp2wp-options.php' );
	}
	// Register valid admin options...
	function wp2wp_register_settings(){
		register_setting( "wp2wpqaq_options", "wp2wp_server_option" , "wp2wp_option_save");
	}
	// Save option...
	function wp2wp_option_save($options){
		if(!empty($options)){
			$remoteServer = trim( $options['host'] );
			if ( ( 'http://' != substr( $remoteServer, 0, 7 ) ) && ( 'https://' != substr( $remoteServer, 0, 8 ) ) ) {
				$remoteServer = 'http://'.$remoteServer;
			}
			if ( '/' != substr( $remoteServer, -1 ) ) {
				$remoteServer .= '/';
			}
			if ( include_once(  ABSPATH . WPINC . '/class-IXR.php' ) ) {
				if ( include_once(  ABSPATH . WPINC . '/class-wp-http-ixr-client.php' ) ) {
					$xmlrpc = new WP_HTTP_IXR_CLIENT( $remoteServer.'xmlrpc.php' );
					$xmlrpc->query( 'wp.getOptions', array( 0, $options['user'], $options['pass'], array( 'software_name', 'software_version', 'so_api' ) ) );
					$xmlrpcResponse = $xmlrpc->getResponse();
					if ( null == $xmlrpcResponse ) {
						if ( -32300 == $xmlrpc->getErrorCode() ) {
							$options['authenticated'] = false;
							$newOptions['group'][$groupId]['servers'][$serverKey]['api'] = 'API Unavailable';
						} else {
							$options['authenticated'] = false;
							$options['api'] = 'Unknown';
						}
					}else{
						if ( isset( $xmlrpcResponse['faultString'] ) ) {
							$options['authenticated'] = false;
							$options['api'] = __( trim( $xmlrpcResponse['faultString'], ' .' ), 'WP2WPQAQ' );
						}else{
							$options['authenticated'] = true;
							if ( isset( $xmlrpcResponse['so_api'] ) ) {
								$options['api'] = sprintf( __( 'WP2WPQAQ Synchronize API v%s', 'WP2WPQAQ' ), $xmlrpcResponse['so_api']['value'] );
							} else {
								$options['api'] = $xmlrpcResponse['software_name']['value'].' '.$xmlrpcResponse['software_version']['value'];
							}
						}
					}
				}
			}
		}
		return $options;
	}
	
	// Settings link on plugins page...
	function wp2wp_settings_link( $links, $file ) {
		if ( plugin_basename( __FILE__ ) == $file ) {
			array_push( $links, '<a href="options-general.php?page=wp2wpqaq">'.__( 'Settings', 'wp2wpqaq' ).'</a>' );
		}
		return $links;

	}
}


