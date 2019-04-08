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
     * @param  [type] $page [description]
     * @return [type]       [description]
     */
    public function getPopup($page=1){
        $offset = 10;
        $sql = "SELECT count(*) AS total FROM _faq_question order by editdate DESC";
        $total = $this->shortcode->getCount($sql);
        $start = ($page-1)*$offset;
        $sql = "SELECT id,title FROM _faq_question order by editdate DESC limit {$start},{$offset};";
        $faqs = $this->shortcode->query($sql);
        $html = $this->getSimPage($page, $offset, $total['total']);
        echo $this->view->make('faqlist')->with("faqs", $faqs)->with("pght", $html);
    }

    /**/
    private function getSimPage($current_page = 1, $offset = 10, $total =0, $show = 5, $adjacents = 3, $adjacents_offset = 2){
        $html = '';
        $last_page = ceil($total/$offset);

        $the_first = ($current_page==1)?"<li><a href=\"javascript:void(0);\">" . __('first', 'myfaqs') . "</a></li>":"<li><a href=\"javascript:void(0);\" onclick=\"Traces.page(". 1 .")\" >" . __('first', 'myfaqs') . "</a></li>";
        $the_last = ($current_page == $last_page)?("<li><a href=\"javascript:void(0);\">" . __('last', 'myfaqs') . "</a></li>"):("<li><a href=\"javascript:void(0);\" onclick=\"Traces.page(". $last_page .")\" >" . __('last', 'myfaqs') . "</a></li>");

        $pmin = ($current_page > $adjacents) ? ($current_page-$adjacents) : 1;
        $pmax = ($current_page < ($last_page - $adjacents)) ? ($current_page+$adjacents) : $last_page;

        for ($page=$pmin; $page<=$pmax; $page++){
            if($current_page==$page){
                $html.= "<li class=\"active\"><a href=\"javascript:void(0);\">" . $page . "</a></li>";
            }else{
                $html.= "<li><a href=\"javascript:void(0);\" onclick=\"Traces.page(". $page .")\" >" . $page . "</a></li>";
            }
        }

        
        $html = $the_first. $html .$the_last;
        
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