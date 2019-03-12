<?php
/**
 * Created by PhpStorm.
 * User: huimingdeng
 * Date: 2019/3/12
 * Time: 10:41
 */

namespace MyFAQs\Classes;


use MyFAQs\MyFAQs;

class Admin
{
    private static $_instance = null;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

    }

    public function add_admin_menu(){
        add_menu_page(
            MyFAQs::PLUGIN_NAME,
            MyFAQs::PLUGIN_NAME,
            'edit_pages',
            strtolower(FaqCategories::MENU_NAME),
            array($this,'getCategoryPage')
        );
        add_submenu_page(
            strtolower(FaqCategories::MENU_NAME),
            FAQs::MENU_NAME,
            MyFAQs::PLUGIN_NAME.'&middot;'.FAQs::MENU_NAME,
            'edit_pages',
            strtolower(FAQs::MENU_NAME),
            array($this,'getFaqsPage')
        );
    }

    public function getCategoryPage(){
        return FaqCategories::getInstance()->categories_page();
    }

    public function getFaqsPage(){
        return FAQs::getInstance()->faqs_page();
    }

    public function admin_enqueue_scripts(){
        $screen = get_current_screen();
        wp_register_style('myfaqdef',MyFAQs::get_asset('css/bootstrap.min.css'), MyFAQs::VERSION);
        if($screen->id === 'myfaqs_page_faqs' || $screen->id === 'toplevel_page_categories'){
            wp_enqueue_style('myfaqdef');
        }
    }

    /**
     * @return Admin
     */
    public static function getInstance(){
        if(self::$_instance===null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}