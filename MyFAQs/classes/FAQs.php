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
    public function faqs_page(){
        $sql = "SELECT q.id,q.title,q.answer,c.name,q.editdate FROM _faq_question as q LEFT JOIN _faq_categories as c ON q.category=c.id LIMIT 0,20 ";
        $data = $this->faqs->query($sql);

        $categories = FaqCategories::getInstance()->getAllCategories();
        echo $this->view->make('faqs')->with('title','Faqs')->with('actived',strtolower(self::MENU_NAME))->with('data',$data)->with('categories', $categories);
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
     * @return FaqCategories|null
     */
    public static function getInstance(){
        if(self::$_instance===null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}