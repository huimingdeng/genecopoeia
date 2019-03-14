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
        switch ($operation){
            case 'add':
                break;
            case 'edit':
                break;
            case 'delete':
                break;
            default:
                break;
        }
    }
}