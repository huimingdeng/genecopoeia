<?php
/**
 * Created by PhpStorm.
 * User: huimingdeng
 * Date: 2019/3/7
 * Time: 16:21
 */

namespace MyFAQs\Classes;


class FaqManage
{
    private static $_instance = null;
    const MENU_NAME  = 'traces';
    private $view;

    public function __construct()
    {
        $this->view = new View();

    }


    /**
     *
     */
    public function traces_page(){
        echo $this->view->make('traces')->with('title','Traces')->with('actived',strtolower(self::MENU_NAME));

    }

    /**
     * @param $atts
     */
    public function shortcode($atts){
        echo $this->view->make('shortcode');
    }

    /**
     * Call the metabox template.
     */
    public function metaBox(){
        echo $this->view->make('metabox');
    }

    public function getPopup(){

        // 测试查询10条faq数据
        global $wpdb;
        $sql = "SELECT id,title FROM _faq_question order by editdate DESC limit 1,10;";
        $faqs = $wpdb->get_results($sql, ARRAY_A);

        echo $this->view->make('faqlist')->with("faqs", $faqs);
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