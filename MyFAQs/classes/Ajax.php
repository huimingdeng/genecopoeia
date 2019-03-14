<?php
/**
 * Created by PhpStorm.
 * User: huimingdeng
 * Date: 2019/3/14
 * Time: 15:11
 */

namespace MyFAQs\Classes;


class Ajax extends Input
{
    public function dispatch(){
        $operation = $this->post('operation');
        $data = $this->post('data');
        // set headers
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Encoding: ajax');
        header('Cache-Control: private, max-age=0');
        header('Expires: -1');
        switch ($operation){
            case 'add':
                print_r($data);
                break;
            case 'edit':
                break;
            case 'delete':
                break;
            default:
                print_r($data);
                break;
        }
    }
}