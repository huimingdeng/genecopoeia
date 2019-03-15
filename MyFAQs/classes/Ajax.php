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

class Ajax extends Input
{
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
            // print_r($data);
                $data2 = $this->str2arr($data);
                $obj = $this->getObject($type);
                $msg = $obj->add($data);
                echo json_encode(array('msg'=>$msg));
                exit(0);
                break;
            case 'edit':
                break;
            case 'delete':
                break;
            default:
                
                break;
        }
        // exit(0);
    }

    private function str2arr ($str,$sp="&",$kv="=")
    {
        $arr = str_replace(array($kv,$sp),array('"=>"','","'),'array("'.$str.'")');
        eval("\$arr"." = $arr;");   
        return $arr;
    }

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