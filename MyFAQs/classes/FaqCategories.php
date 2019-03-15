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

    public function __construct()
    {
        $this->view = new View();
    }

    /**
     *
     */
    public function categories_page(){
        $categories = new Model('categories');
        $categories->setFiles('id,name,slug,sumfaq,editdate,parent');
        $data = $categories->getList();
        echo $this->view->make('categories')->with('title','Categories')->with('actived',strtolower(self::MENU_NAME))->with('data',$data);

    }

    public function add($data){
        $category = new Model('categries');
        $msg = $category->addOne($data);
        return $msg;
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