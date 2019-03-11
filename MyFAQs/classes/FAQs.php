<?php
/**
 * Created by PhpStorm.
 * User: huimingdeng
 * Date: 2019/3/11
 * Time: 17:35
 */

namespace MyFAQs\Classes;


class FAQs
{
    private static $_instance = null;
    const FAQS_PAGE = 'faqs';
    private $view;

    private function __construct()
    {
        $this->view = new View();
        add_action('admin_menu', array($this, 'add_faqs_page'));
    }

    /**
     * @return false|string
     */
    public function add_faqs_page()
    {

        /*$slug = add_submenu_page(
            'options-general.php',
            __('MyFAQs Faqs list', 'myfaqs'),
            __('MyFAQs&middot;Faqs', 'myfaqs'),		// displayed in menu
            'manage_options',							// capability
            self::FAQS_PAGE,						// menu slug
            array($this, 'faqs_page')				// callback
        );
        return $slug;*/
    }

    /**
     *
     */
    public function faqs_page(){
//echo 'ok';
        $tabs = array(
            'categories' => array(
                'name' => __("MyFAQs Categories", 'myfaqs'),
                'title' => __('Setting Category for FAQs'),
                'icon' => 'glyphicon glyphicon-cog',
            ),
            'faqs' => array(
                'name' => __("MyFAQs&middot;Faqs", 'myfaqs'),
                'title' => __('FAQs list'),
                'icon' => 'glyphicon glyphicon-list-all',
            ),
            'used' => array(
                'name' => __("MyFAQs used trace", 'myfaqs'),
                'title' => __('Track FAQ usage for management'),
                'icon' => 'glyphicon glyphicon-th-list',
            ),
        );
        echo $this->view->make('faqs')->with('title','Faqs')->with('tabs', $tabs);

    }


    /**
     * @return FaqCategories|null
     */
    public static function getInstance(){
        if(self::$_instance===null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}