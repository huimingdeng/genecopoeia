<?php
/**
 * Created by PhpStorm.
 * User: huimingdeng
 * Date: 2019/3/11
 * Time: 14:35
 */

namespace MyFAQs\Classes;


class FaqCategories
{
    private static $_instance = null;
    const CATEGORIES_PAGE = 'categories';
    private $view;

    private function __construct()
    {
        $this->view = new View();
        add_action('admin_menu', array($this, 'add_categories_page'));
    }

    public function add_categories_page()
    {

        $slug = add_submenu_page(
            'options-general.php',
            __('MyFAQs Categories', 'myfaqs'),
            __('MyFAQs&middot;Categories', 'myfaqs'),		// displayed in menu
            'manage_options',							// capability
            self::CATEGORIES_PAGE,						// menu slug
            array($this, 'categories_page')				// callback
        );
        return $slug;
    }

    private function categories_page(){

        return $this->view->make('index');

    }



    public static function getInstance(){
        if(self::$_instance===null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}