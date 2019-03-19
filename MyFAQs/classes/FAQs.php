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
    private $faqs;

    /**
     * FAQs constructor.
     */
    public function __construct()
    {
        $this->view = new View();
        $this->faqs = new Model('question');
    }

    /**
     *
     */
    public function faqs_page(){

         $this->faqs->setUnion('categories','category','id');
         $data = $this->faqs->getList();
//        print_r($data);
        $categories = FaqCategories::getInstance()->getAllCategories();
        echo $this->view->make('faqs')->with('title','Faqs')->with('actived',strtolower(self::MENU_NAME))->with('data',$data)->with('categories', $categories);
    }

    /**
     * @param $data
     * @return bool
     */
    public function add($data){
        $msg = $this->faqs->addOne($data);
        return $msg;
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