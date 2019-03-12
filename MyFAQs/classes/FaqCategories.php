<?php
/**
 * Created by PhpStorm.
 * User: huimingdeng
 * Date: 2019/3/11
 * Time: 14:35
 */

namespace MyFAQs\Classes;


use MyFAQs\MyFAQs;

class FaqCategories
{
    private static $_instance = null;
    const MENU_NAME = 'Categories';
    private $view;

    private function __construct()
    {
        $this->view = new View();
    }

    /**
     *
     */
    public function categories_page(){

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
        echo $this->view->make('categories')->with('title','Categories')->with('tabs', $tabs);

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