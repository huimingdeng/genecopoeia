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
    const MENU_NAME  = 'faqs';
    private $view;

    private function __construct()
    {
        $this->view = new View();

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