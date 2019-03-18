<?php
/**
 * Created by PhpStorm.
 * User: huimingdeng
 * Date: 2019/3/14
 * Time: 15:11
 */

namespace MyFAQs\Classes;

use MyFAQs\Classes\FAQs;
use MyFAQs\Classes\FaqCategories;
use MyFAQs\Classes\FaqManage;
use MyFAQs\MyFAQs;

class Ajax extends Input
{
    /**
     * Setting Ajax point
     */
    public function dispatch(){
        $operation = $this->post('operation');
        $data = $this->post('data');
        $type = $this->post('type');
        // set headers
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Encoding: ajax');
        header('Cache-Control: private, max-age=0');
        header('Expires: -1');
        switch ($operation){
            case 'add':
                $data2 = MyFAQs::str2arr(urldecode($data));
                $obj = $this->getObject($type);
                $msg = $obj->add($data2);
                echo json_encode($msg);
                exit(0);
                break;
            case 'edit':
                $data2 = MyFAQs::str2arr(urldecode($data));
                $obj = $this->getObject($type);
                $msg = $obj->edit($data2);
                echo json_encode($msg);
                exit(0);
                break;
            case 'delete':
                break;
            case 'popup':
                $obj = $this->getObject($type);
                echo $obj->getPopup($data);
                exit(0);
                break;
            default:
                
                break;
        }
         exit(0);
    }

    /**
     * @param $type
     * @return \MyFAQs\Classes\FaqCategories|\MyFAQs\Classes\FaqManage|\MyFAQs\Classes\FAQs
     */
    public function getObject($type){
        switch ($type){
            case 'category':
                $class = new FaqCategories();
                break;
            case 'faqs':
                $class = new FAQs();
                break;
            case 'traces':
                $class = new FaqManage();
                break;
        }
        return $class;
    }
}