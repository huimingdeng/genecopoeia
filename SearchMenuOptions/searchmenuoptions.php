<?php 
/**
 * Plugin Name: Search Menu Options
 * Plugin URI: https://github.com/huimingdeng/genecopoeia/tree/master/SearchMenuOptions
 * Description: genecopoeia 网站搜索页面的克隆产品筛选菜单管理插件。
 * Version: 1.0.0
 * Author: DHM (huimingdeng)
 * Author URI: #
 * License: Open source but the copyright is used by genecopoeia.
 */

class SearchMenuOptions
{
	const PLUGIN_VERSION = '1.0.0';
	private static $_instance = NULL;
	const DEBUG = TRUE;
	private static $_license = NULL;
	private static $_autoload_paths = array();

	private function __construct()
	{
		spl_autoload_register(array($this, 'autoload'));//自动加载的函数
		// activation hooks
		register_activation_hook(__FILE__, array($this, 'activate'));
		register_deactivation_hook(__FILE__, array($this, 'deactivate'));
		// 插件加载
		add_action('plugins_loaded', array($this, 'plugins_loaded'), 1);
		// AJAX 调用
		add_action('wp_ajax_searchmenuoptions', array($this, 'check_ajax_query'));
		if (is_admin())
			Admin::get_instance();
	}
	/*
	 * autoloading callback function
	 * @param string $class name of class to autoload
	 * @return TRUE to continue; otherwise FALSE
	 */
	public function autoload($class)
	{
		$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
		// setup the class name
		$classname = strtolower($class);
		// check each path
		$classfile = $path . $classname . '.class.php';
		if (file_exists($classfile)){
			require_once($classfile);
		}
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
	/**
	 * Returns the installation directory for this plugin.
	 * @return string The installation directory
	 */
	public static function get_plugin_path()
	{
		return plugin_dir_path(__FILE__);
	}
	/*
	 * Adds a directory to the list of autoload directories. Can be used by add-ons
	 * to include additional directories to look for class files in.
	 * @param string $dirname the directory name to be added
	 */
	public static function add_autoload_directory($dirname)
	{
		if (substr($dirname, -1) != DIRECTORY_SEPARATOR)
			$dirname .= DIRECTORY_SEPARATOR;

		self::$_autoload_paths[] = $dirname;
	}

	/*
	 * called on plugin first activation
	 */
	public function activate()
	{
		// load the installation code
		require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'activate.php');
	}

	/**
	 * Runs on plugin deactivation
	 */
	public function deactivate()
	{
		require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'deactivate.php');
	}

	/*
	 * return reference to asset, relative to the base plugin's /assets/ directory
	 * @param string $ref asset name to reference
	 * @return string href to fully qualified location of referenced asset
	 */
	// TOOD: move into utility class
	public static function get_asset($ref)
	{
		$ret = plugin_dir_url(__FILE__) . 'assets/' . $ref;
		return $ret;
	}

	/**
	 * Callback for the 'plugins_loaded' action. Load text doamin and notify other WPSiteSync add-ons that WPSiteSync is loaded.
	 */
	public function plugins_loaded()
	{
		load_plugin_textdomain('searchmenuoptions', FALSE, plugin_basename(dirname(__FILE__)) . '/languages');
		do_action('searchmenuoptions_init');//创建一个行为钩子
	}

	/**
	 * Checks for an AJAX request and initializes the AJAX class to dispatch any found action.
	 */
	public function check_ajax_query()
	{
		if (defined('DOING_AJAX') && DOING_AJAX) {
			$ajax = new Ajax();
			$ajax->dispatch();
		}
	}

}
// Initialize the plugin
SearchMenuOptions::get_instance();
// $test = SearchMenuOptions::get_instance();
