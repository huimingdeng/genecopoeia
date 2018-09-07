<?php 
/**
 * Search Menu Options Admin
 * 搜索菜单选项管理
 */
class Admin
{
	private static $_instance = NULL;
	private static $plugin_path;

	private function __construct()
	{
		self::$plugin_path = SearchMenuOptions::get_plugin_path();
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
		add_action('admin_menu',array($this,'addMenuPage'));
	}

	public function addMenuPage()
	{
		add_menu_page(
	      	'SearchMenuOptions',
		    'Search Menu Options',
		    'edit_pages',
		    'SearchMenuOptions',
		    array($this,'SearchMainPage')
	    );
	    add_submenu_page(
	    	'SearchMenuOptions',
	    	'SearchTestOptions',
	      	'Search Test Options',
	      	'edit_pages',
	      	'SearchTest',
	      	array($this,'SearchTestPage')
	    );
	}

	public function SearchMainPage($pagename)
	{

		include_once (self::$plugin_path.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'search-main.page.php');
	}

	public function SearchTestPage($pagename)
	{

		include_once (self::$plugin_path.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'search-test.page.php');
	}

	public function admin_enqueue_scripts($hook_suffix)
	{
		// echo ($hook_suffix);
		wp_register_script('seachmenu-bootstrap-js', SearchMenuOptions::get_asset('js/bootstrap.min.js'), array(), SearchMenuOptions::PLUGIN_VERSION, TRUE);
		wp_register_script('seachmenu-layer-js', SearchMenuOptions::get_asset('js/layer-v2.3/layer.js'), array(), SearchMenuOptions::PLUGIN_VERSION, TRUE);
		wp_register_script('seachmenu-operation-js', SearchMenuOptions::get_asset('js/operation.js'), array(), SearchMenuOptions::PLUGIN_VERSION, TRUE);
		wp_register_style('seachmenu-bootstrap-css', SearchMenuOptions::get_asset('css/bootstrap.min.css'), array(), SearchMenuOptions::PLUGIN_VERSION, 'all');
		wp_register_style('seachmenu-css', SearchMenuOptions::get_asset('css/searchmenuoptions.css'), array(), SearchMenuOptions::PLUGIN_VERSION, 'all');
		wp_enqueue_script('seachmenu-bootstrap-js');
		wp_enqueue_script('seachmenu-layer-js');
		wp_enqueue_script('seachmenu-operation-js');
		wp_enqueue_style('seachmenu-bootstrap-css');
		wp_enqueue_style('seachmenu-css');
	}
	/*
	 * retrieve singleton class instance
	 * @return instance reference to plugin
	 */
	public static function get_instance()
	{
		if (NULL === self::$_instance)
			self::$_instance = new self();
		return self::$_instance;
	}
}