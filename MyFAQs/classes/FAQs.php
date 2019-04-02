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
     * faqs model
     * @var [type]
     */
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
     * faqs list page
     * @return html
     */
    public function faqs_page($page = 1, $offset = 10){
        
        $where1 = (!empty($_GET['cat']))?' q.category ='.$_GET['cat']:'';

        $where2 = (!empty($_GET['s']))?" q.title LIKE'%".addslashes(htmlentities($_GET['s']))."%'":'';
        if($where2!='' && $where1!=''){
            $where = ' WHERE '. $where1. ' AND '. $where2;
        }elseif($where1!=''||$where2!=''){
            $where = ' WHERE '. $where1 . $where2;
        }else{
            $where = '';
        }


        $orderby = ( !empty($_GET['orderby']) && !empty($_GET['order']) )?sprintf(' ORDER BY %s %s', $_GET['orderby'], $_GET['order']):'';

        $start = ($page-1)*$offset;
        $sql = "SELECT q.id,q.title,q.answer,c.`name`,q.editdate FROM _faq_question as q LEFT JOIN _faq_categories as c ON q.category=c.id".$where.$orderby;
        // echo $sql;
        $count = $this->faqs->getCount($sql);
        $total = $count['total'];
        // echo $total;
        $sql .= ($total>$offset)?(" LIMIT {$start},{$offset} "):'';
        // echo $sql;
        $data = $this->faqs->query($sql);
        
        // Assemble the URI
        $uri = admin_url('admin.php').'?page=faqs';
        ($page>1)?$uri.= '&p='.$page:$uri;
        (!empty($_GET['s']))?$uri.= '&s='.$_GET['s']:$uri;
        (!empty($_GET['cat']))?$uri.= '&cat='.$_GET['cat']:$uri;

        $html = $this->faqs->getPage($page,$offset,$total, $uri);// Access to the paging
        $categories = FaqCategories::getInstance()->getAllCategories();
        
        echo $this->view->make('faqs')->with('title','Faqs')->with('actived',strtolower(self::MENU_NAME))->with('data',$data)->with('categories', $categories)->with('pages',$html)->with('p',$page)->with('total', $total)->with('cat',$_GET['cat'])->with('uri',$uri);
    }

    /**
     * @param $data
     * @return bool
     */
    public function add($data){
        $msg = $this->faqs->addOne($data);

        if($msg['status']==200){
            $category_id = $data['category'];
            $bool = $this->faqs->save("UPDATE `_faq_categories` SET sumfaq=sumfaq+1 WHERE id = {$category_id}");
            //print_r($bool);
            $msg['bool'] = $bool;
        }
        return $msg;
    }

    /**
     * @param $data
     * @return array
     */
    public function edit($data){
        $id = $data['id'];
        $new_category = $data['category'];
        $old_data = $this->faqs->getOne($id);
        $old_category = $old_data['category'];
        if($old_data['title']!=$data['title']||$old_data['answer']!=$data['answer']||$old_data['category']!=$data['category']){
            $msg = $this->faqs->editOne($data);
            // Modify the statistics if the category changes
            if($old_data['category']!=$data['category']){
                $this->faqs->save("UPDATE `_faq_categories` SET sumfaq=sumfaq-1 WHERE id = {$old_category}");
                $this->faqs->save("UPDATE `_faq_categories` SET sumfaq=sumfaq+1 WHERE id = {$new_category}");
            }
        }else{
            $msg = array("status" => 304, "msg" => __("The submitted information has not been modified.", "myfaqs"));
        }

        return $msg;
    }

    /**
     * @param $id
     * @return array
     */
    public function delete($id){
        $faq = $this->faqs->getOne($id);
        // $msg = array('status'=>205, 'msg'=> 'delete Test'.$faq); //Test
        $msg = $this->faqs->deleteOne($id);
        if($msg['status']==200){
            $category_id = $faq['category'];
            $this->faqs->save("UPDATE `_faq_categories` SET sumfaq=sumfaq-1 WHERE id = {$category_id}");
        }
        return $msg;
    }

    /**
     * @param String|Integer $id
     * @return string
     */
    public function getPopup($id = ''){
        if(!empty($id)){
            $data = $this->faqs->getOne($id);
        }else{
            $data = array();
        }
        $categories = FaqCategories::getInstance()->getAllCategories();
        return (string)$this->view->make('faqpopup')->with('data', $data)->with('categories', $categories);
    }

    /**
     * Generate json file. 
     * @return [type] [description]
     */
    public function exportJson(){
        $path = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR;
        if(!is_dir($path)){
            mkdir($path, 0777);
        }
        $filename = 'faqs.json';
        $file = $path . $filename;

        if(!file_exists($file)){
            $this->faqs->setFiles('id,title,answer');
            $res = $this->faqs->getList();
            $json = json_encode($res);
            file_put_contents($file, $json);
            $msg = array("status" => 200, "msg" => __("Generate Json File.",'myfaqs') );
        }else{
            $etime = filemtime($file);
            $time = $_SERVER['REQUEST_TIME'];
            // The interval of about 30 minutes allows the original file to be overwritten again.
            if($time>$etime && ($time-$etime) >= 1800 ){ 
                $this->faqs->setFiles('id,title,answer');
                $res = $this->faqs->getList();
                $json = json_encode($res);
                file_put_contents($file, $json);
                $msg = array("status" => 200, "msg" => __("Generate New Json File.", 'myfaqs') );
            }else{
                $newtime = 1800 - ($time - $etime);
                $msg = array( "status" => 500, "msg" => __("The new json file cannot be generated; please regenerate it after ", 'myfaqs').$newtime. __(" seconds", "myfaqs") );
            }
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