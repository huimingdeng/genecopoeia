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
    static $list = array();

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
     * @param  integer $page   [description]
     * @param  integer $offset [description]
     * @return  html
     */
    public function categories_page($page = 1,$offset = 10){

        if (!empty($this->categories)) {
            $this->categories->setFiles('id,name,slug,sumfaq,editdate,parent');
        }
        
        $start = ($page-1)*$offset;
        $this->categories->limitpage($start,$offset);
        $data = $this->categories->getList();
        $total = $this->categories->getCount();
        $html = $this->categories->getPage($page,$offset,$total['total'],'/wp-admin/admin.php?page=categories');
        $has_level_data = self::getTree($data);
        if(empty($has_level_data)) $has_level_data = $data;
        echo $this->view->make('categories')->with('title','Categories')->with('actived',strtolower(self::MENU_NAME))->with('data',$has_level_data)->with("page",$html);

    }
    /**
     * Recursive classification tree
     * @param  array  $array data
     * @param  integer $pid   
     * @param  integer $level 
     * @return array
     */
    public static function getTree($array, $pid=0, $level=0){
        
        foreach ($array as $key => $value){
            if ($value['parent'] == $pid){
                $value['level'] = $level;
                self::$list[] = $value;
                unset($array[$key]);
                self::getTree($array, $value['id'], $level+1);
            }
        }
        return self::$list;
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