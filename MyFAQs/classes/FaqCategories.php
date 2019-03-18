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
    private $categories;
    private $allcategories;

    /**
     * FaqCategories constructor.
     */
    public function __construct()
    {
        $this->view = new View();
        $this->categories = new Model('categories');
    }

    /**
     * Category list page.
     */
    public function categories_page(){

        if (!empty($this->categories)) {
            $this->categories->setFiles('id,name,slug,sumfaq,editdate,parent');
        }
        /** @var TYPE_NAME $data */
        $data = $this->categories->getList();

        echo $this->view->make('categories')->with('title','Categories')->with('actived',strtolower(self::MENU_NAME))->with('data',$data);

    }

    /**
     * @return mixed
     */
    public function getAllCategories(){
        if (!empty($this->categories)) {
            $this->allcategories = $this->categories->getList();
        }
        return $this->allcategories;
    }

    /**
     * @param $data
     * @return bool
     */
    public function add($data){
        $msg = $this->categories->addOne($data);
        return $msg;
    }

    /**
     * @param $data
     * @return array
     */
    public function edit($data){
        /** @var TYPE_NAME $this */
        $msg = $this->categories->editOne($data);
        return $msg;
    }

    /**
     * @param $id
     * @return array
     */
    public function delete($id){
        $msg = $this->categories->deleteOne($id);
        return $msg;
    }

    /**
     * @param $id
     * @return string
     */
    public function getPopup($id){
        $data = $this->categories->getOne($id);
        $category = $this->getAllCategories();
        return (string)$this->view->make('catpopup')->with('data', $data)->with('categories',$category);
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