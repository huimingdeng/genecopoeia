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
    private $shortcode;

    public function __construct()
    {
        $this->view = new View();
        $this->shortcode = new Model('shortcode');
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

    /**
     * 
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function getPopup($data){

        $sql = "SELECT id,title FROM _faq_question order by editdate DESC limit 1,10;";
        $faqs = $this->shortcode->query($sql);
        $sql = "SELECT count(*) AS total FROM _faq_question order by editdate DESC";
        $total = $this->shortcode->getCount($sql);
        $page = isset($data['p'])?$data['p']:1;
        $html = $this->getSimPage($page, 10, $total['total']);
        echo $this->view->make('faqlist')->with("faqs", $faqs)->with("pght", $html);
    }

    /**/
    private function getSimPage($current_page = 1, $offset = 10, $total =0){
        $html = '';
        $last_page = ceil($total/$offset);
        for ($page=1;$page<=$last_page;$page++){
            if($current_page==$page){
                $html.=         "<li class=\"active\"><a href=\"javascript:void(0);\">" . $page . "</a></li>";
            }elseif($page==1){
                $html.=         "<li><a href=\"javascript:void(0);\" onclick=\"Traces.page(". $page .")\" >" . $page . "</a></li>";
            }else{
                $html.=         "<li><a href=\"javascript:void(0);\" onclick=\"Traces.page(". $page .")\" >" . $page . "</a></li>";
            }
        }
        return $html;
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