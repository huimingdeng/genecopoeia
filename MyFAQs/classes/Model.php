<?php
/**
 * Created by PhpStorm.
 * User: huimingdeng
 * Date: 2019/3/7
 * Time: 16:06
 */

namespace MyFAQs\Classes;

use MyFAQs\MyFAQs;

class Model
{
    private $prefix = '_faq_';
    private $table;
    private $wpdb;
    private $sql;
    private $fields = '*';
    private $limited;
    private $msg = array();
    private $union; //

    /**
     * Model constructor.
     * @param $tab_name
     */
    public function __construct($tab_name)
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $this->prefix.$tab_name;
    }

    /**
     * @param string $fields
     */
    public function setFiles($fields="*"){
        $this->fields = $fields;
    }

    /**
     * Query database return data.
     * @return mixed
     */
    public function getList(){
        $fields = $this->fields;
        $table = $this->table;
        $limited = $this->limited;

        $sql = "SELECT {$fields} FROM {$table} {$this->union} {$limited};";
        $this->sql = $sql;
//        print_r($sql);
        $res = $this->wpdb->get_results($sql,ARRAY_A);
        return $res;
    }

    /**
     * Gets the total number of data rows.
     * @param string $sql 
     * @return array
     */
    public function getCount($sql=''){
        $sql = ($sql!='')?preg_replace('/LIMIT \d+\,\d+/', '', preg_replace('/SELECT [\.\,a-z`\*]+ FROM/', 'SELECT count(*) AS total FROM',$sql)):"SELECT count(*) AS total FROM {$this->table} ";
        // echo $sql;
        return $this->wpdb->get_row($sql, ARRAY_A);
    }

    /**
     * Gets the paging HTML code.
     * @param int $current_page The current page number
     * @param int $offset Number per page
     * @param int $total The total number of records
     * @return string
     */
    public function getPage($current_page = 1, $offset = 15, $total =0, $uri='', $adjacents=2){
        $last_page = ceil($total/$offset);// Calculate the total page number
        $uri = preg_replace('/\&p=\d+/','',$uri);
        $url = (!empty($uri))?$uri.'&p=':'javascript:void(0);';

        $html = "<nav aria-label=\"...\">\n";
        $html.=     "<ul class=\"pagination\">\n";
        if($current_page==1){
            $html.=         "<li class=\"disabled\"><a href=\"javascript:void(0);\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo;</span></a></li>\n";
        }elseif($current_page==2){
            $html.=         "<li><a href=\"" . $uri ."\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo;</span></a></li>\n";
        }else{
            $html.=         "<li><a href=\"" . $url . ($current_page-1) ."\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo;</span></a></li>\n";
        }

        $pmin = ($current_page > $adjacents) ? ($current_page-$adjacents) : 1;
        $pmax = ($current_page < ($last_page - $adjacents)) ? ($current_page+$adjacents) : $last_page;

        for ($page=$pmin; $page<=$pmax; $page++){
            if($current_page==$page){
                $html.=         "<li class=\"active\"><a href=\"javascript:void(0);\">" . $page . "</a></li>\n";
            }elseif($page==1){
                $html.=         "<li><a href=\"". $uri ."\">" . $page . "</a></li>\n";
            }else{
                $html.=         "<li><a href=\"". $url . $page ."\">" . $page . "</a></li>\n";
            }
        }
        if($current_page==$last_page){
            $html.=         "<li class=\"disabled\"><a href=\"javascript:void(0);\" aria-label=\"Next\"><span aria-hidden=\"true\">&raquo;</span></a></li>\n";
        }else{
            $html.=         "<li><a href=\"" . $url . ($current_page+1) ."\" aria-label=\"Next\"><span aria-hidden=\"true\">&raquo;</span></a></li>\n";
        }
        $html.=     "</ul>\n";
        $html.= "</nav>\n";
        if($total >= 1):
            return $html;
        else:
            return '';
        endif;
    }

    /**
     * @param $table    Join queries another table
     * @param $field1   The join field of the current table
     * @param $field2   Join queries the join fields of another table
     */
    public function setUnion($table,$field1,$field2){
        $table = $this->prefix.$table;
        $this->union = " LEFT JOIN {$table} ON {$this->table}.{$field1} = {$table}.{$field2} ";
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
        }
        $values = array_values($data);
        // 需要按照table的字段类型设置字段
        // ...
        $query = sprintf("INSERT INTO {$this->table} (%s) VALUES(%s)",implode(',', $fields),implode(',',$values));
        // echo $query;exit;
        $bool = $this->wpdb->query($query);

        if($bool !== false){
            $this->msg = array( 'status'=>200, 'msg'=>__('Data added successfully','myfaqs'));
        }else{
            $this->msg = array( 'status'=>500, 'msg'=>__('Data addition error.','myfaqs'));
        }
        // $this->msg = array('status'=>205, 'msg'=> $query);

        return $this->msg;
    }

    /**
     * Get the data by id.
     * @param $id
     * @return mixed
     */
    public function getOne($id){
        $this->sql = "SELECT {$this->fields} FROM {$this->table} WHERE id = {$id} LIMIT 1";
        $data = $this->wpdb->get_row($this->sql, ARRAY_A);
        return $data;
    }

    /**
     * Perform modification
     * @param array $data
     * @return array
     */
    public function editOne($data){
        $data['editdate'] = date('Y-m-d H:i:s',time());
        $id = $data['id'];
        unset($data['id']);
        foreach ($data as $k => $v)
        {
            $data[$k] = $this->format($k , $v);
        }
        $string = MyFAQs::arr2str($data);
        $query = sprintf("UPDATE {$this->table} SET %s WHERE id=%d",$string, $id);
        $bool = $this->wpdb->query($query);

        if($bool !== false){
            $this->msg = array( 'status'=>200, 'msg'=>__('Data modified successfully','myfaqs'));
        }else{
            $this->msg = array( 'status'=>500, 'msg'=>__('Data modification error.','myfaqs'));
        }
//        $this->msg = array('status'=>203, 'msg'=> $query);
        return $this->msg;
    }

    /**
     * @param Integer $id
     * @return array
     */
    public function deleteOne($id){
        $query = "DELETE FROM {$this->table} WHERE id={$id}";
        $bool = $this->wpdb->query($query);
        if($bool !== false){
            $this->msg = array( 'status'=>200, 'msg'=>__('Data deleted successfully','myfaqs'));
        }else{
            $this->msg = array( 'status'=>500, 'msg'=>__('Data deletion error.','myfaqs'));
        }
        return $this->msg;
    }

    /**
     * @param string $sql
     * @return mixed
     */
    public function query($sql = ''){
        $this->sql = $sql;
        $data = $this->wpdb->get_results($sql, ARRAY_A);
        return $data;
    }

    /**
     * @param string $sql
     * @return mixed
     */
    public function save($sql = ''){
        $data = $this->wpdb->query($sql);
        //print_r($data);
        return $data;
    }

    /**
     * @return array
     */
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
                        //return "'" . addslashes(htmlentities($val)) . "'";
                    }
                }

                return $val;
            }
        }

        return false;
    }

    /**
     * @param int $start
     * @param int $offset
     */
    public function limitpage($start=0, $offset=20){
        $this->limited = " limit {$start},{$offset}";
    }

    /**
     * @return mixed
     */
    public function getSql(){
        return $this->sql;
    }

    /**
     *
     */
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        $this->wpdb = null;
    }
}