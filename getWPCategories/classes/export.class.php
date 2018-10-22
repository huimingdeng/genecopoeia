<?php 

/**
 * Mount to the export page in setting
 */
class Export
{
	private static $_instance = null;
	const SETTINGS_PAGE = 'GenerateAJson';

	private function __construct()
	{
		add_action('admin_menu', array($this, 'add_configuration_page'));
	}
	/**
	 * setting page 
	 */
	public function add_configuration_page()
	{

		$slug = add_submenu_page(
			'options-general.php',
			__('get WP Categories', 'getwpcategories'), // page title
			__('Get WP Categories', 'getwpcategories'),		// displayed in menu
			'manage_options',							// capability
			self::SETTINGS_PAGE,						// menu slug
			array($this, 'settings_page')				// callback
		);
		return $slug;
	}

	public function settings_page()
	{
		$cssref = GetWPCategories::get_asset('css/getwpcategories.css');
		$jsref = GetWPCategories::get_asset('js/getwpcategories.js');
		wp_enqueue_style('getwpcategories', $cssref, '', GetWPCategories::PLUGIN_VERSION);
		wp_enqueue_script('getwpcategories', $jsref, '', GetWPCategories::PLUGIN_VERSION);
		View::load_view('generation');
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