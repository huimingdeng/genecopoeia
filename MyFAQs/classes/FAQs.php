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
        echo $this->view->make('faqs')->with('title','Faqs')->with('actived',strtolower(self::MENU_NAME));

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