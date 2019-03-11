<?php
/**
 * Created by PhpStorm.
 * User: huimingdeng
 * Date: 2019/3/11
 * Time: 15:13
 */

namespace MyFAQs\Classes;


class View
{
    protected $file;
    // 模板变量
    protected $vars = array(); //兼容5.4以下版本用 array()

    /**
     * @param $file Template file name
     */
    public function make($file){
        $this->file = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.$file.'.php';
//        print_r($this->file);
        return $this;
    }

    /**
     * @param string $name 变量名
     * @param string $value 变量值
     */
    public function with($name, $value){
        $this->vars[$name] = $value;
        return $this;
    }

    /**
     * 用于执行PHP代码字符化，用于加载模板
     */
    public function __toString()
    {
//        echo "__toString";
        extract($this->vars); // 因在同一作用域中，模板中可以获得
        include $this->file;
        return '';
    }

}