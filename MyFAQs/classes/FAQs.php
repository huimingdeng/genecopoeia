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

    /**
     * FAQs constructor.
     */
    public function __construct()
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
     * @param String|Integer $id
     * @return string
     */
    public function getPopup($id = ''){
        if(!empty($id)){

            $data = array();
        }else{
            $data = array();
        }
        $categories = FaqCategories::getInstance()->getAllCategories();
        return (string)$this->view->make('faqpopup')->with('data', $data)->with('categories', $categories);
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