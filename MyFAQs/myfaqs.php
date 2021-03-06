<?php 
/**
 * Plugin Name: MyFAQs
 * Plugin URI: #
 * Description: FAQ 展示工具，后期将结合百度 AnyQ | RasaHQ 形成机器客服
 * Author: DHM(huimingdeng)
 * Version: 0.1.3
 */
namespace MyFAQs;

use MyFAQs\Classes\Admin;
use MyFAQs\Classes\Ajax;
use MyFAQs\Install\Activate;
use MyFAQs\Install\Deactivate;

class MyFAQs{
    private static $_instance = null;
    const VERSION = '0.1.3';
    const PLUGIN_NAME = 'MyFAQs';
    /**
     * Register to load class files, 
     * create ajax access hooks, 
     * and generate administrative pages
     */
    public function __construct()
    {
        spl_autoload_register(array($this,'autoload'));
        // activation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        add_action('wp_ajax_myfaqs', array($this, 'add_ajax_point')); // Add an ajax access endpoint
        add_action('plugins_loaded', array($this, 'plugins_loaded'), 1);
        add_action('wp_footer', array($this, 'forntCss'));
        if(is_admin()) {
            Admin::getInstance();
        }
    }

    /**
     * Automatic class loading implementation.
     * @param String $class
     */
    public function autoload($class){
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;

       // $class = strtolower($class);
        $classname = explode('\\',$class);// Namespace gets the last element as the filename.
       // print_r($classname);
        $class = array_pop($classname);

        $classfile = $path . $class . ".php";

        if(file_exists($classfile))
            require_once $classfile;
    }

    /**
     * The installation data table is activated for the first time.
     * @param bool $network
     */
    public function activate($network = FALSE){
        require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'Activate.php');
        $activate = new Activate();
        $activate->plugin_active($network);
    }

    /**
     * Perform a table cleanup operation if the plugin is uninstalled
     */
    public function deactivate(){
        require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'Deactivate.php');
        $deactivate = new Deactivate();
        $deactivate->plugin_deactivation();
    }

    /**
     * Converts serialized form data into arrays.
     * @param string $str serialized from data 
     * @return mixed
     */
    public static function str2arr ($str)
    {
        $arr = array();
        parse_str($str, $arr); 
        return $arr;
    }

    /**
     * Array conversion string.
     * @param  Array $arr 
     * @return String      
     */
    public static function arr2str($arr){
        $a = array();
        foreach ($arr as $k=>$v) {
            $a[] = '`'.$k . '` = ' . $v ;
        }
        $str = implode(',',$a);
        return $str;
    }

    /**
     * Add a plugin custom hook
     */
    public function plugins_loaded(){
        load_plugin_textdomain('myfaqs', FALSE, plugin_basename(dirname(__FILE__)) . '/languages');
        do_action('myfaqs_init');// Create a behavior hook
    }

    /**
     * Add Ajax action interfaces.
     */
    public function add_ajax_point(){
        if (defined('DOING_AJAX') && DOING_AJAX) {
            $ajax = new Ajax();
            $ajax->dispatch();
        }
    }

    /**
     * Short code templates implement functions.
     * @param $atts
     * @return mixed
     */
    public function AddShortCode($atts){
        $atts = shortcode_atts(
            array(
                'class'=>1,
                'title'=>''
            ),$atts);
        
        global $wpdb;

        $sql = sprintf("SELECT code_value FROM _faq_shortcode WHERE short_code = '%s'",$atts['class']);
        $code_value = $wpdb->get_row($sql, ARRAY_A);
        // print_r($code_value);
        $faq_sql = sprintf("SELECT title,answer FROM _faq_question WHERE id in(%s)", $code_value['code_value']);
        $faqs = $wpdb->get_results($faq_sql, ARRAY_A);
        ob_start();
        include('views'.DIRECTORY_SEPARATOR.'shortcode.php');
        $output = ob_get_clean();
        return $output;
    }
    
    /**
     * Quote styles and scripts at post or page
     * @return [type] [description]
     */
    public function forntCss(){
        wp_register_style('myfaqs.css', self::get_asset('css/myfaqs.css'), array(), self::VERSION);
        wp_register_script('myfaqs.js', self::get_asset('js/myfaqs.js'), array('jquery'), self::VERSION);
        if (is_page() || is_single()) {
            wp_enqueue_script('myfaqs.js');
            wp_enqueue_style('myfaqs.css');
        }
           
    }

    /**
     * Assemble the plugin resource path
     * @param $ref assets uri / file name
     * @return string
     */
    public static function get_asset($ref)
    {
        $ret = plugin_dir_url(__FILE__) . 'assets/' . $ref;
        return $ret;
    }

    /**
     * Get class instance
     * @return MyFAQs|null
     */
	public static function getInstance(){

        if(NULL === self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;

	}
}

MyFAQs::getInstance();
register_uninstall_hook(__FILE__, array('MyFAQs\MyFAQs', 'deactivate'));
add_shortcode('myfaqs', array('MyFAQs\MyFAQs','AddShortCode'));
