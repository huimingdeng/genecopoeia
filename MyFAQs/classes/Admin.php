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

    /**
     * Admin constructor: setting WordPress action hook
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('add_meta_boxes',array($this, 'add_manage_metabox'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }

    /**
     * Setting Admin Pages.
     */
    public function add_admin_menu(){
        add_menu_page(
            MyFAQs::PLUGIN_NAME,
            MyFAQs::PLUGIN_NAME,
            'edit_pages',
            strtolower(FaqCategories::MENU_NAME),// menu_slug
            array($this,'getCategoryPage'),
            MyFAQs::get_asset('fonts/question-circle-fill.svg')
        );
        add_submenu_page(
            strtolower(FaqCategories::MENU_NAME),
            FAQs::MENU_NAME,
            MyFAQs::PLUGIN_NAME.'&middot;'.FAQs::MENU_NAME,
            'edit_pages',
            strtolower(FAQs::MENU_NAME),
            array($this,'getFaqsPage')
        );
        add_submenu_page(
            strtolower(FaqCategories::MENU_NAME),
            FaqManage::MENU_NAME,
            MyFAQs::PLUGIN_NAME.'&middot;'.FaqManage::MENU_NAME,
            'edit_pages',
            strtolower(FaqManage::MENU_NAME),
            array($this, 'getTracesPage')
        );
    }

    /**
     * Get the instance of the Categories list page.
     * @return mixed
     */
    public function getCategoryPage(){
        $page = (isset($_GET['p']))?$_GET['p']:1;
        return FaqCategories::getInstance()->categories_page($page);
    }

    /**
     * Get the Faqs list page instance.
     * @return mixed
     */
    public function getFaqsPage(){
        $page = (isset($_GET['p']))?$_GET['p']:1;
        return FAQs::getInstance()->faqs_page($page);
    }

    /**
     * Gets a short code trace page instance.
     * @return mixed
     */
    public function getTracesPage(){
        return FaqManage::getInstance()->traces_page();
    }

    /**
     * Add meta box
     */
    public function add_manage_metabox(){
        add_meta_box(
            'myfaqsid',
            'MyFAQs',
            array($this,'metaBox'),
            array('post','page')
        );
    }

    /**
     * add_meta_box callback
     */
    public function metaBox(){
        FaqManage::getInstance()->metaBox();
    }

    /**
     * Setting Admin Page Style and Scripts
     */
    public function admin_enqueue_scripts(){
        $screen = get_current_screen();
        wp_register_style('myfaqdef', MyFAQs::get_asset('css/bootstrap.min.css'), array(), MyFAQs::VERSION);
        wp_register_style('metabox', MyFAQs::get_asset('css/metabox.css'), array(), MyFAQs::VERSION);
        wp_register_script('jq1123', MyFAQs::get_asset('js/jquery-1.12.3.min.js'), array(), MyFAQs::VERSION);
        wp_register_script('bootstrapv3', MyFAQs::get_asset('js/bootstrap.min.js'), array('jquery'), MyFAQs::VERSION);
        wp_register_script('category', MyFAQs::get_asset('js/category.js'), array('jquery'), MyFAQs::VERSION);
        wp_register_script('faqsjs', MyFAQs::get_asset('js/faqs.js'), array('jquery'), MyFAQs::VERSION);
        wp_register_script('traced', MyFAQs::get_asset('js/traces.js'), array('jquery'), MyFAQs::VERSION);

        if($screen->id === 'myfaqs_page_faqs' || $screen->id === 'toplevel_page_categories' || $screen->id === 'myfaqs_page_traces'){
            wp_enqueue_style('myfaqdef');
            wp_enqueue_script('jq1123');
            wp_enqueue_script('bootstrapv3');
            if($screen->id === 'toplevel_page_categories'){
                wp_enqueue_script('category');
            }
            if($screen->id === 'myfaqs_page_faqs'){
                wp_enqueue_script('faqsjs');
            }
            if($screen->id === 'myfaqs_page_traces'){
                wp_enqueue_script('traced');
            }
        }elseif($screen->id === 'post' || $screen->id === 'page'){
            wp_enqueue_style('metabox');
            wp_enqueue_script('traced');
        }
    }

    /**
     * Get an instance of the Admin class
     * @return Admin
     */
    public static function getInstance(){
        if(self::$_instance===null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}