<?php
/**
 * Created by PhpStorm.
 * User: huimingdeng
 * Date: 2019/3/7
 * Time: 16:06
 */

namespace MyFAQs\Classes;


class Model
{
    private $prefix = '_faq_';
    private $table;
    private $wpdb;
    private $sql;
    private $fields = '*';
    private $limited;

    public function __construct($tab_name)
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $this->prefix.$tab_name;
    }

    public function setFiles($fields="*"){
        $this->fields = $fields;
    }

    public function getList(){
        $fields = $this->fields;
        $table = $this->table;
        $limited = $this->limited;

        $sql = "SELECT {$fields} FROM {$table} {$limited};";
        $this->sql = $sql;

        $res = $this->wpdb->get_results($sql,ARRAY_A);
        return $res;
    }

    /**
     * @param $data
     * @return bool true|false;
     */
    public function addOne($data){

        return true;
    }

    public function limitpage($start=0,$offeser=20){
        $this->limited = " limit {$start},{$offeser}";
    }

    public function getSql(){
        return $this->sql;
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        $this->wpdb = null;
    }
}