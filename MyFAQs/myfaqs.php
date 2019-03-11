<?php 
/**
 * Plugin Name: MyFAQs
 * Plugin URI: #
 * Description: FAQ 展示工具，后期将结合百度 AnyQ | RasaHQ 形成机器客服
 * Author: DHM(huimingdeng)
 * Version: 0.0.1
 */
namespace MyFAQs;


use MyFAQs\Classes\FaqCategories;
use MyFAQs\Classes\Model; // 数据库操作模型类

class MyFAQs{
    private static $_instance = null;

    private function __construct()
    {
        spl_autoload_register(array($this,'__autoload'));
        add_action('wp_ajax_spectrom_sync', array($this, 'check_ajax_query'));
        if(is_admin())
            FaqCategories::getInstance();
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

    private function check_ajax_query(){
        // if (defined('DOING_AJAX') && DOING_AJAX) {
        //     $ajax = new SyncAjax();
        //     $ajax->dispatch();
        // }
    }


	public static function getInstance(){

        if(NULL === self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;

	}
}

MyFAQs::getInstance();