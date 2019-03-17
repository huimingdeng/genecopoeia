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
    private $msg = array();

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
        $data['pubdate'] = $data['editdate'] = date('Y-m-d H:i:s',time());
        $fields = array_keys($data);
        foreach ($data as $k => $v)
        {
            $data[$k] = $this->format($k , $v);
//            var_dump($data[$k]);
        }
//        var_dump($data);
        $values = array_values($data);
        // 需要按照table的字段类型设置字段
        // ...
        $query = sprintf("INSERT INTO {$this->table} (%s) VALUES(%s)",implode(',', $fields),implode(',',$values));
        $bool = $this->wpdb->query($query);

        if($bool !== false){
            $this->msg = array( 'status'=>200, 'msg'=>_('Data added successfully','myfaqs'));
        }else{
            $this->msg = array( 'status'=>500, 'msg'=>_('Data addition error.','myfaqs'));
        }

        return $this->msg;
    }

    public function getOne($id){
        $this->sql = "SELECT {$this->fields} FROM {$this->table} WHERE id = {$id} LIMIT 1";
        $data = $this->wpdb->get_row($this->sql, ARRAY_A);
        return $data;
    }

    public function editOne($data){
        $data['editdate'] = date('Y-m-d H:i:s',time());
        $fields = array_keys($data);
        foreach ($data as $k => $v)
        {
            $data[$k] = $this->format($k , $v);
        }

        $values = array_values($data);
        // 需要按照table的字段类型设置字段
        // ...
        /*$query = sprintf("UPDATE {$this->table} SET %s",implode(',', $fields),implode(',',$values));
        $bool = $this->wpdb->query($query);

        if($bool !== false){
            $this->msg = array( 'status'=>200, 'msg'=>_('Data added successfully','myfaqs'));
        }else{
            $this->msg = array( 'status'=>500, 'msg'=>_('Data addition error.','myfaqs'));
        }

        return $this->msg;*/
    }

    public function query($sql = ''){
        $data = $this->wpdb->get_results($sql, ARRAY_A);
        return $data;
    }

    public function getConstucture(){
        // Get table structure
        $sql  = 'describe ' . $this->table;
        $data = $this->query($sql);

        $constucture = array();

        foreach ($data as $v)
        {
            $constucture[$v['Field']] = $this->getType($v['Type']);
        }

        return $constucture;
    }

    /**
     * Gets the concrete data type
     * @param string $type
     * @return string
     */
    public function getType($type = ''){
        if (preg_match("/tinyint/" , $type) === 1) {
            return 'tinyint';
        }

        if (preg_match("/int/" , $type) === 1) {
            return 'int';
        }

        if (preg_match("/char/" , $type) === 1) {
            return 'char';
        }

        if (preg_match("/varchar/" , $type) === 1) {
            return 'varchar';
        }

        if (preg_match("/text/" , $type) === 1) {
            return 'text';
        }

        if (preg_match('/datetime/', $type) === 1){
            return 'datetime';
        }
        // 数据库类型不止以上这些，其他的请自行补充
        // ...
    }

    /**
     * Formatting strings
     * @param string $key
     * @param string $val
     * @return bool|string
     */
    public function format($key='', $val=''){

        $group = $this->getConstucture();
        $add_quote_type_range = array('char' , 'varchar' , 'text', 'datetime');

        foreach ($group as $k => $type)
        {
            if ($k === $key) {
                foreach ($add_quote_type_range as $ok_type)
                {
                    if ($type === $ok_type){
                        return "'" . $val . "'";
                    }
                }

                return $val;
            }
        }

        return false;
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