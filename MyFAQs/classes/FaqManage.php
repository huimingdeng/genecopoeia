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
     * @return  string
     */
    public function traces_page(){
        echo $this->view->make('traces')->with('title','Traces')->with('actived',strtolower(self::MENU_NAME));

    }

    public function getShortCode($postid){
        if(!empty($postid)){
            $sql = sprintf("SELECT short_code FROM %s WHERE location = %s ORDER BY short_code DESC", $this->shortcode->getTable(),$postid);
            $info = $this->shortcode->query($sql);
            if(!empty($info)){
                $ht = array();
                foreach($info as $short_code){
                    $ht[] = self::Buttons($short_code['short_code']);
                }
                $msg = array(
                    'status' => 200,
                    'code' => implode("\n", $ht)
                );
            }

        }else{
            $msg = array('status'=>201);
        }

        return $msg;
    }

    public static function Buttons($name){
        $button = "<a href=\"javascript:void(0);\" class=\"btn\" onclick=\"Traces.editList('{$name}')\">[myfaqs class='{$name}']</a>";
        return $button;
    }

    /**
     * Call the metabox template.
     */
    public function metaBox(){
        $postid = get_the_ID();
        echo $this->view->make('metabox')->with('postid', $postid);
    }

    /**
     * FAQs list
     * @param  integer $page current page
     * @return string
     */
    public function getPopup($page=1){
        $offset = 10;
        $sql = "SELECT count(*) AS total FROM _faq_question order by editdate DESC";
        $total = $this->shortcode->getCount($sql);
        $start = ($page-1)*$offset;
        $sql = "SELECT id,title FROM _faq_question order by editdate DESC limit {$start},{$offset};";
        $faqs = $this->shortcode->query($sql);
        $html = $this->getSimPage($page, $offset, $total['total']);
        echo $this->view->make('faqlist')->with("faqs", $faqs)->with("pght", $html)->with('operate','add');
    }

    /**
     * Simple paging
     * @param  integer $current_page     current page
     * @param  integer $offset           Page number offset
     * @param  integer $total            The total number of data
     * @param  integer $show             Number of pages displayed, not used
     * @param  integer $adjacents        [description]
     * @param  integer $adjacents_offset [description]
     * @return string                    [description]
     */
    private function getSimPage($current_page = 1, $offset = 10, $total =0, $show = 5, $adjacents = 2, $adjacents_offset = 2){
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

        if($last_page>$pmax){
            $html.= "<li><a href=\"javascript:void(0);\">...</a></li>";
        }elseif($pmin >= 2){
            $html = "<li><a href=\"javascript:void(0);\">...</a></li>" . $html;
        }
        
        $html = $the_first. $html .$the_last;
        
        return $html;
    }
    /**
     * 
     * @param [type] $data [description]
     */
    public function add($data){
        $save = array();
        $is_save = false;
        if(!empty($data['postid'])&&!empty($data['ids'])){
            $sql = sprintf("SELECT count(*) AS total FROM %s WHERE location = %s", $this->shortcode->getTable(), $data['postid']);
            $total = $this->shortcode->getCount($sql);
            $no = $total['total']+1;
            $save['location'] = $data['postid'];
            $save['short_code'] = $data['postid'].'-'.$no;
            $save['code_value'] = implode(',', $data['ids']);
            $save['pubdate'] = $save['editdate'] = date('Y-m-d H:i:s',time());
            $is_save = true;
        }

        if($is_save){
            $msg = $this->shortcode->addOne($save);
            if($msg['status']==200){
                $msg['code'] = self::Buttons($save['short_code']);
            }
        }else{
            $msg = array( 'status'=>500, 'msg'=>__('Data addition error.','myfaqs'));
        }
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