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

    private function __construct()
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
     * @return FaqCategories|null
     */
    public static function getInstance(){
        if(self::$_instance===null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

}