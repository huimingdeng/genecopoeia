<?php 
/**
 * Plugin Name: MyFAQs
 * Plugin URI: #
 * Description: FAQ 展示工具，后期将结合百度 AnyQ | RasaHQ 形成机器客服
 * Author: DHM(huimingdeng)
 * Version: 0.0.11
 */
namespace MyFAQs;

use MyFAQs\Classes\Admin;
use MyFAQs\Classes\Ajax;

class MyFAQs{
    private static $_instance = null;
    const VERSION = '0.0.11';
    const PLUGIN_NAME = 'MyFAQs';

    public function __construct()
    {
        spl_autoload_register(array($this,'autoload'));
        add_action('wp_ajax_myfaqs', array($this, 'add_ajax_point'));
        add_action('plugins_loaded', array($this, 'plugins_loaded'), 1);
        
        if(is_admin()) {
            Admin::getInstance();
        }
    }

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
     * Converts serialized form data into arrays.
     * @param string $str serialized from data
     * @param string $sp Connection separator
     * @param string $kv The key-value connection separator
     * @return mixed
     */
    public static function str2arr ($str,$sp="&",$kv="=")
    {
        $arr = str_replace(array($kv,$sp),array('"=>"','","'),'array("'.$str.'")');
        eval("\$arr"." = $arr;");
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
        do_action('myfaqs_init');//创建一个行为钩子
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
     * @param $atts
     * @return mixed
     */
    public function AddShortCode($atts){
        $atts = shortcode_atts(
            array(
                'class'=>1,
                'title'=>'Enter Gene Symbol or Accession No.',
                'width'=>300
            ),$atts);
        
        ob_start();
        include('views'.DIRECTORY_SEPARATOR.'shortcode.php');
        $output = ob_get_clean();
        return $output;
    }

    /**
     * @param $ref assets uri / file name
     * @return string
     */
    public static function get_asset($ref)
    {
        $ret = plugin_dir_url(__FILE__) . 'assets/' . $ref;
        return $ret;
    }

    /**
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


// add_shortcode('myfaqs', array('MyFAQs\MyFAQs','AddShortCode'));
