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
    const CATEGORIES_PAGE = 'categories';
    private $view;

    private function __construct()
    {
        $this->view = new View();
        add_action('admin_menu', array($this, 'add_categories_page'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        
    }

    /**
     * @return false|string
     */
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

    /**
     *
     */
    public function admin_enqueue_scripts(){
        $screen = get_current_screen();
        wp_register_style('myfaqdef',MyFAQs::get_asset('css/bootstrap.min.css'), MyFAQs::VERSION);
        if($screen->id === 'settings_page_categories'){
            wp_enqueue_style('myfaqdef');
        }
    }

    /**
     *
     */
    public function categories_page(){
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
        echo $this->view->make('categories')->with('pluginname','MyFAQs Categories')->with('tabs', $tabs);

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