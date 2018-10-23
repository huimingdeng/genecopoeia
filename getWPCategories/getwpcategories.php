<?php 
/**
 * Plugin Name: Get Blog Categories
 * Plugin URI: #
 * Description: 用于获取博客的分类目录。 目前不采用，因为接口需要密码验证等才能调用
 * Version: 0.0.2
 * Author: DHM(huimingdeng)
 * Author URI: #
 * License: GPL2
 */
class GetWPCategories
{
	private static $_instance = null; // object instace
	private $id_a = array(); // posts ID array
	const PLUGIN_VERSION = '0.0.2';

	private function __construct()
	{
		spl_autoload_register(array($this, 'autoload')); // Autoload function
		// 插件加载
		add_action('plugins_loaded', array($this, 'plugins_loaded'), 1);
		// AJAX 调用
		add_action('wp_ajax_getwpcatoptions', array($this, 'check_ajax_query'));
		if (is_admin())
			Export::getInstance();
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
		load_plugin_textdomain('getwpcatoptions', FALSE, plugin_basename(dirname(__FILE__)) . '/languages');
		do_action('getwpcategories_init');//创建一个行为钩子
	}

	/**
	 * Checks for an AJAX request and initializes the AJAX class to dispatch any found action.
	 */
	public function check_ajax_query()
	{
		if (defined('DOING_AJAX') && DOING_AJAX) {
			$operation = new Operation();
		}
	}

	/**
	 * return object instance
	 * @return object 
	 */
	public static function getInstance()
	{
		if(NULL===self::$_instance)
			self::$_instance = new self();
		return self::$_instance;
	}
}

GetWPCategories::getInstance();