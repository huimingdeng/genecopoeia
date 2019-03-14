<?php 
/**
 * Plugin Name: MyFAQs
 * Plugin URI: #
 * Description: FAQ 展示工具，后期将结合百度 AnyQ | RasaHQ 形成机器客服
 * Author: DHM(huimingdeng)
 * Version: 0.0.2
 */
namespace MyFAQs;

use MyFAQs\Classes\Admin;
use MyFAQs\Classes\Ajax;

class MyFAQs{
    private static $_instance = null;
    const VERSION = '0.0.2';
    const PLUGIN_NAME = 'MyFAQs';

    private function __construct()
    {
        spl_autoload_register(array($this,'__autoload'));
        add_action('wp_ajax_myfaqs', array($this, 'check_ajax_query'));
        if(is_admin()) {
            Admin::getInstance();
        }
    }

    public function __autoload($class){
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;

//        $class = strtolower($class);
        $classname = explode('\\',$class);//命名空间获取最后一个元素为文件名
//        print_r($classname);
        $class = array_pop($classname);

        $classfile = $path . $class . ".php";

        if(file_exists($classfile))
            require_once $classfile;
    }

    public function check_ajax_query(){
         if (defined('DOING_AJAX') && DOING_AJAX) {
             $ajax = new Ajax();
             $ajax->dispatch();
         }
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


	public static function getInstance(){

        if(NULL === self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;

	}
}

MyFAQs::getInstance();
