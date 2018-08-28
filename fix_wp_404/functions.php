<?php
declare(ticks=1);//配合信号量杀死进程用

/***** 从THINKPHP拿的。****/
define('IS_CLI',PHP_SAPI=='cli'? 1   :   0);
define('IS_CGI',(0 === strpos(PHP_SAPI,'cgi') || false !== strpos(PHP_SAPI,'fcgi')) ? 1 : 0 );

//加载simple html dom 类
require_once(dirname(__FILE__).'/simple_html_dom.php');
/**
 * 本脚本会引入同目录下的config.php文件，作为配置变量
 * */



class FIX_WP_404{
	protected $mysqli;
	protected $domain;
	protected $checked_url=array();
	protected $checked_post=array();

	// public function __construct($host, $user, $pass, $db , $charset) {
	public function __construct() {

		//加载配置
		$this->_config() ;
		$this->connect_to_mysql();

		//set domain 
		$this->set_domain();

		$this->create_log_path();	

		$this->define_parm();

		$this->fault_error_handler();

		
	}


	public function __destruct(){
		$this->save_url_status_code_to_file();
		$this->save_checked_post_to_file();
	}
	function fault_error_handler(){
		if(IS_CLI){
			pcntl_signal(SIGINT, function ($signo) {
				if ($signo == SIGINT) {
					exit();//接收到Ctrl+C后，程序意外关闭，会调用__destruct()方法
				}
			});
		}

		register_shutdown_function(array($this, "__destruct"));//程序报错，调用函数，保存数据

		// $this->save_url_status_code_to_file();
		// $this->save_checked_post_to_file();
	}

	//加载配置文件，合并配置
	protected function _config(){
		$config_array =  require(dirname(__FILE__).'/config.php');
		// $config_array =  require(dirname(__FILE__).'/config_local.php');
		$this->config = array(
			//数据库链接
			'DB_NAME'=>'',
			'DB_USER'=>'',
			'DB_PASSWORD'=>'',
			'DB_HOST'=>'',
			'DB_CHARSET'=>'utf8',
			//域名设置
			'DOMAIN'=>'',
			//是否调试
			'DEBUG'=>false,
			'CHECTED_FILE_PATH'=>'./check',//检查文件记录保存位置
			'ERROR_LOG'=>'logs/error_message.log',//错误日志
			'URL_STATUS_CODE_LOG'=>'logs/url_status_code.log',//url的状态码记录，可以快速判断url的状态，不需要再测试一次
			'POST_CHECKED_LOG'=>'logs/post_checked.log',//已检查过的文章记录
			'PROBLEM_POST'=> 'logs/queued_post.txt',
			'REPLACE_404_LINK_TO'=>'',//把含404的链接替换为。（默认空，则删除链接和文字；非空，则把连接指向这个值）
		);
		if(!empty($config_array)){
			$this->config = $config_array + $this->config;
		}else{
			die("Please make a config file.");
		}
	}

	protected function connect_to_mysql(){
		$this->mysqli = new mysqli($this->config['DB_HOST'], $this->config['DB_USER'], $this->config['DB_PASSWORD'], $this->config['DB_NAME']);

		// check connection
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error()) and exit();
		}

		// change character set to utf8
		if (!$this->mysqli->set_charset($this->config['DB_CHARSET'])) {
			printf("Error loading character set %s: %s\n", $this->config['DB_CHARSET'] , $this->mysqli->error);
		}
	}

	protected function create_log_path(){
		!file_exists(dirname($this->config['ERROR_LOG'])) ? mkdir(dirname($this->config['ERROR_LOG']) , 0777 , true) : '';
		!file_exists(dirname($this->config['URL_STATUS_CODE_LOG'])) ? mkdir(dirname($this->config['URL_STATUS_CODE_LOG']) , 0777 , true) : '';
		!file_exists(dirname($this->config['POST_CHECKED_LOG'])) ? mkdir(dirname($this->config['POST_CHECKED_LOG']) , 0777 , true) : '';
	}

	protected function define_parm(){
		global $argv;//使用全局变量，接收CLI模式的参数
		$this->error_message = array();

		if( ($this->checked_url = $this->get_url_status_code_from_file() ) === false){
			$this->checked_url = array();
		}

		if(isset($argv[1]) and $argv[1] == 'load_post_status'){
			$this->checked_post = $this->get_checked_post_from_file();
		}else{
			$this->checked_post = array();
		}
	}

	public function change_config($name , $value){
		if(isset($this->config[$name])){
			$this->config[$name] = $value;
			return true;
		}
		return false;
	}

	//建立一些文件夹，用于存放数据检查前后的记录值（默认在本目录下）
	public function mk_check_path(array $paths = array('logs','before','after')){
		foreach ($paths as $path) {
			if(!file_exists($this->config['CHECTED_FILE_PATH'] .'/'. $path)){
				mkdir( $this->config['CHECTED_FILE_PATH'] .'/'. $path , 0777 , true);
			}
		}
	}

	//设置域名
	protected function set_domain(){
		$this->domain = $this->config['DOMAIN'];
	}

	//修复url
	function fix_url_domain($url ){
		$url = preg_replace("~#(\w+)?$~", "" , trim($url));
		if(preg_match("~^https?://~" , $url)){
			return $url;
		}
		if(preg_match("~^//~" , $url)){
			return 'http:'.$url;
		}
		if(preg_match("~^/(?!/)~" , $url)){
			return 'http://' . $this->domain.$url;
		}
		return false;
	}

	//判断url是不是属于本域名下的链接
	function is_this_domain($url){
		return preg_match("~^(https?|ftp)://(\w+\.)*?". $this->domain ."~" , $url) ? true : false;
	}

	function get_queued_post_id(){
		// if( file_exists($this->config['PROBLEM_POST']) ){
		// 	$file = $this->config['PROBLEM_POST'] ;
		// }else{
		// 	return false;
		// }
		// return file_exists($this->config['PROBLEM_POST']) ? json_decode(file_get_contents($this->config['PROBLEM_POST']) , true) : false;
		$fp = fopen($this->config['PROBLEM_POST'], "r+");
		if (flock($fp, LOCK_EX)) {  // 进行排它型锁定
			$line = fgets($fp); //取一行
			ob_start();
			fpassthru($fp); //获得文件指针处后所有字符
			$new_content = ob_get_clean();
			ftruncate($fp, 0);
			fseek($fp , 0); //定位到第文件开头
			fwrite($fp, $new_content); //写入新数据
			fflush($fp); // flush output before releasing the lock
			flock($fp, LOCK_UN);    // 释放锁定
		} else {
			return -1;
		}
		fclose($fp);
		return $line;
	}

	function set_queued_post_id($post_ids){
		// if( file_exists($this->config['PROBLEM_POST']) ){
		// 	$file = $this->config['PROBLEM_POST'] ;
		// }else{
		// 	return false;
		// }
		return file_put_contents($this->config['PROBLEM_POST'] , implode("\n" , $post_ids) , LOCK_EX);
	}

	function get_all_url_status_code(){
		return $this->checked_url;
	}


	function get_url_status_code($url){
		return array_key_exists( md5($url) , $this->checked_url) ? $this->checked_url[md5($url)] : false;
		// return false;
	}

	function set_url_status_code($url , $status_code){
		// print_r($this->checked_url);
		return $this->checked_url[md5($url)] = $status_code;
		// return true;
	}
	function get_all_checked_post(){
		return $this->checked_post;
	}
	function get_checked_post($postid){
		return in_array( $postid , $this->checked_post) ? $postid : false;
		// return array_key_exists( $postid , $this->checked_post) ? $this->checked_post[$postid] : false;
		// return false;
	}
	function set_checked_post($postid){
		// print_r($this->checked_post);
		return in_array( $postid , $this->checked_post) ? false : $this->checked_post[] = $postid;
		// return $this->checked_post[$postid] = $postid;
		// return true;
	}

	function save_url_status_code_to_file(){
		// print_r($this->checked_url);
		return file_put_contents($this->config['URL_STATUS_CODE_LOG'], json_encode( $this->checked_url) , LOCK_EX  );
	}

	function get_url_status_code_from_file(){
		return file_exists($this->config['URL_STATUS_CODE_LOG']) ? json_decode(file_get_contents($this->config['URL_STATUS_CODE_LOG']) , true) : false;
	}

	function save_checked_post_to_file(){
		return file_put_contents($this->config['POST_CHECKED_LOG'], json_encode( $this->checked_post) , LOCK_EX );
	}

	function get_checked_post_from_file(){
		return file_exists($this->config['POST_CHECKED_LOG']) ? json_decode(file_get_contents($this->config['POST_CHECKED_LOG']) , true) : false;
	}

	//删除body前后的标签（tidy类使用后带来的问题）
	function remove_html_tag($string){
		return preg_replace(array('~^[\w\W]+?<body>~','~</body>[\w\W]+$~') , '' , $string);
	}

	/*
	检查一个链接的状态是不是404
	*/
	function check_url_status_code($url){
		// $url = preg_replace(array("~^([^#]+)~" , "~ ~"), array("$1" , "%20"), trim($url));
		$exploded = explode('#', $url);
		$url = reset($exploded);//去掉url的fragment（#xxxx），解决这个问题：http://www.genecopoeia.com/product/crispr-cas9/#Cas9 Nuclease Expression Clones
		$url = str_replace(' ' , '%20', trim($url)); //转换空格为%20，解决这个问题：http://www.genecopoeia.com/wp-content/uploads/oldpdfs/product/qpcr-arrays/gene/docs/QG067 datasheet.pdf ， status code 404; http://www.genecopoeia.com/wp-content/uploads/oldpdfs/product/qpcr-arrays/gene/docs/QG067%20datasheet.pdf ， status code 200。
		if($url){
			//打开缓冲
			// ob_start();
			// 创建一个cURL句柄
			$ch = curl_init();
			// 设置cURL传输选项
			curl_setopt($ch, CURLOPT_URL, $url);//需要获取的URL地址，也可以在curl_init()函数中设置。 
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。 
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10); //指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的。 
			curl_setopt($ch, CURLOPT_NOBODY, true);//启用时将不对HTML中的BODY部分进行输出。
			curl_setopt($ch, CURLOPT_HTTPGET, true);//启用时会设置HTTP的method为GET //需要使用这个选项，否则php文件的链接会返回404错误。例如这种链接：http://gcdev.fulengen.cn/product/search/detail.php?prt=1&cid=&key=H0264&choose=cdk2
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。（不显示出来）
			// 执行
			curl_exec($ch);

			//不需要获得缓存内容
			// $return_message = ob_get_contents();

			//清空（擦除）缓冲区并关闭输出缓冲
			// ob_end_clean();
			// 检查是否有错误发生
			if(!curl_errno($ch)){
				$status_code = curl_getinfo($ch , CURLINFO_HTTP_CODE);
				curl_close($ch);
				return $status_code;
			}
		}else{
			return false;
		}
		
	}

	public function work_status($now , $all ){
		$work_status = round( ((int)$now / (int)$all ), 4);
		if($this->config['DEBUG']==true){
			// echo "Finished :" . $work_status * 100 ." %" . PHP_EOL;
			printf("Finished : %s / %s ( %s ) . Memory used: %s MB\n" , $now , $all , $work_status * 100 ." %" , round(memory_get_usage() / 1024 / 1024 , 2) );
		}else{
			return $work_status;
		}
	}

	/*
	$page 自然页数，从1开始
	$post_per_page 每页多少行
	*/
	function get_post_content( $post_per_page = 'all' , $page = 1 ,$selected_array = array('ID','post_content') , $ispost=true){
		$accepted_array = array(
			'ID',
			'post_content'
		);
		$tmp = array();
		foreach ($selected_array as $value) {
			if( in_array($value , $accepted_array)){
				array_push($tmp , $value);
			}
		}
		if(empty($tmp)){
			$tmp = $accepted_array;
		}

		$part_of_sql = implode('`,`' , $tmp);
		if($post_per_page == 'all'){
			$limit = '';
		}else{
			if((int)$page >= 1 and (int)$post_per_page >= 1){
				$limit = sprintf(" LIMIT %d , %d" , ( $page-1 ) * (int)$post_per_page , (int)$post_per_page );
			}else{
				$limit = '';
			}
		}
		if($ispost===true){
			$part_of_sql_2 = " WHERE post_type='post'";
		}else{
			$part_of_sql_2 = '';
		}

		$sql = sprintf("SELECT `%s` FROM wp_posts  %s %s ORDER BY ID" , $part_of_sql , $part_of_sql_2 , $limit);
		// die($sql);
		// $sql = sprintf("SELECT ID , post_content FROM wp_posts WHERE ID=8121 and post_type='post' " );
		$result = $this->mysqli->query($sql , MYSQLI_USE_RESULT);
		if($result){
			$group_arr = array();
			// Cycle through results
			while ($row = $result->fetch_assoc()){
				$group_arr[] = $row;
			}
			// Free result set
			$result->close();
			// $this->mysqli->next_result();
			return $group_arr;
		}else{
			echo($this->mysqli->error);
			return false;
		}
	}



	//根据post_id获得对应的内容（表wp_posts）
	function get_post_content_from_post_id($postid){
		$sql = sprintf("SELECT ID , post_content FROM wp_posts WHERE ID=%d" , $postid );
		$result = $this->mysqli->query($sql , MYSQLI_USE_RESULT);
		if($result){
			$group_arr = array();
			// Cycle through results
			while ($row = $result->fetch_assoc()){
				$group_arr[] = $row;
			}
			// Free result set
			$result->close();
			return $group_arr;
		}else{
			echo($this->mysqli->error);
			return false;
		}
	}

	//根据post_id获得对应的内容（表wp_posts_copy）
	function get_post_copy_content_from_post_id($postid){
		
		$sql = sprintf("SELECT ID , post_content FROM wp_posts_copy WHERE ID=%d" , $postid);
		$result = $this->mysqli->query($sql , MYSQLI_USE_RESULT);

		if($result){
			$group_arr = array();
			// Cycle through results
			while ($row = $result->fetch_assoc()){
				$group_arr[] = $row;
			}
			// Free result set
			$result->close();
			// $this->mysqli->next_result();
		}else echo($this->mysqli->error);
		return $group_arr;
	}

	function get_post_new_revisions($postid , $checktime){
		$sql = sprintf("SELECT '%s' AS post_id , ID AS revision_id , post_date FROM wp_posts WHERE post_name like '%s-revision%%' and post_type='revision' and post_date > '%s' ORDER BY ID " , $postid , $postid , $checktime );
		$result = $this->mysqli->query($sql , MYSQLI_USE_RESULT);
		if($result){
			$group_arr = array();
			while ($row = $result->fetch_assoc()){
				// $group_arr[] = implode(',' , $row);
				$group_arr[] = $row;
			}
			$result->close();
			// $this->mysqli->next_result();
		}else echo($this->mysqli->error);
		return $group_arr;
	}

	//没啥用
	function _2d_array_to_string($_2d_array , $glue_1d = '' , $glue_2d = ''){
		$string = '';
		foreach ($_2d_array as $key => $value) {
			$string .= implode($glue_2d , $value). $glue_1d;
		}
		return $string;
	}

	function debug_message($message){
		if($this->config['DEBUG']===true){
			echo $message;
		}
	}



	//
	function get_post_permalink($post_id , $request_url){
		return file_get_contents($request_url . '?post_id=' . $post_id);
	}

	function get_post_id($permalink , $request_url){
		return file_get_contents($request_url . '?url=' . $permalink);
	}


	function tidy_content($content){
		$tidy = new tidy();
		$tidy_option = array(
			'indent' => TRUE,
			'output-xhtml' => TRUE,
			'wrap' => 0
		);
		$tidy->parseString($content,$tidy_option);
		$tidy->cleanRepair();
		return $this->remove_html_tag($tidy);
	}

	/*
	修复包含404链接的内容
	$id_content_array 格式：
	Array
	(
	    [0] => Array
	        (
	            [ID] => 28316
	            [post_content] => ..............
	        )

	    [1] => Array
	        (
	            [ID] => 28317
	            [post_content] => .................
	        )
	)
	*/
	function fix_post_content_404($id_content_array){
		$new_id_content_array = array();
		foreach ($id_content_array as $each) {
			$is_different = false;
			$total_s = microtime(true);
			if(trim($each['post_content'])!=''){
				if( $html = str_get_html($each['post_content']) ){// html to dom
					foreach($html->find('a') as $key =>  $element){

						$url = $this->fix_url_domain($element->href );
						if($this->is_this_domain($url)){
							$status_code = $this->get_url_status_code($url); //get status code
							if($status_code===false){//no status code, crawl url and get status code
								$status_code = $this->check_url_status_code($url);
							}
							
							if($status_code == '404'){//page is 404
								if($this->config['REPLACE_404_LINK_TO']==''){
									$html->find('a' , $key)->outertext = $html->find('a' , $key)->innertext; // modify content
								}else{
									$html->find('a' , $key)->href =$this->config['REPLACE_404_LINK_TO'];
								}
								$is_different = true;
							}
							$this->set_url_status_code($url , $status_code); // save status code
						}
					}
				}
			}
			$new_id_content_array[] = array(
				'ID'=> $each['ID'],
				'post_content' => $this->tidy_content($html->outertext),
				'is_different' => $is_different,
			);
			$total_e = microtime(true);

			$this->debug_message(sprintf("Post < %d > is Fixed. Time use: %s\n" , $each['ID'] , round($total_e - $total_s , 4) ));
		}
		return $new_id_content_array;
	}

	function check_post_content_404($id_content_array){
		foreach ($id_content_array as $each) {
			$is_different = false;
			if(trim($each['post_content'])!=''){
				$html = str_get_html($each['post_content']);
				// if($html = str_get_html($each['post_content'])){

					$before_change = $this->tidy_content($html->outertext);
					
					$this->debug_message("Checking Post <". $each['ID'] .">\n");
					$total_s = microtime(true);
					foreach($html->find('a') as $key =>  $element){
						$time_s = microtime(true);

						$url = $this->fix_url_domain($element->href );

						if($this->is_this_domain($url)){

							$status_code = $this->get_url_status_code($url); //get status code
							if($status_code===false){//no status code, crawl url and get status code
								$this->debug_message("Checking URL: ". $url );
								$status_code = $this->check_url_status_code($url);
								$this->debug_message(" <". $status_code ."> \n" );
							}
							$time_e = microtime(true);
							if($status_code == '404'){//page is 404
								if($this->config['REPLACE_404_LINK_TO']==''){
									$html->find('a' , $key)->outertext = $html->find('a' , $key)->innertext ; // modify content
								}else{
									$html->find('a' , $key)->href =$this->config['REPLACE_404_LINK_TO'];
								}
								$this->error_message[] = sprintf("ID:%d, TOTAL_TIME:%s , ERROR_LINK:%s\n" , $each['ID'] , round($time_e - $time_s , 4) , $element->href);
								$is_different = true;
							}
							// echo "post..........";
							$this->set_url_status_code($url , $status_code); // save status code
							// $this->save_url_status_code_to_file(); // save status code to file, next run time be faster
						}
						
					}
					if($is_different){
						file_put_contents( $this->config['CHECTED_FILE_PATH'] .'/before/'. $each['ID'].".html", $before_change , LOCK_EX ); //save file to before/ path

						file_put_contents( $this->config['CHECTED_FILE_PATH'] .'/after/' .$each['ID'].".html", $this->tidy_content($html->outertext) , LOCK_EX );//save file to after/ path
					}
					
					$total_e = microtime(true);
					$this->debug_message(sprintf("Post < %d > is checked. Time use: %s\n\n" , $each['ID'] , round($total_e - $total_s , 4) ));
				// }
			}
		}
		return true;
		// return $error_message;
	}

	function save_message_to_file($message=''){
		// if(!file_exists(dirname($this->config['ERROR_LOG']))){
		// 	mkdir(dirname($this->config['ERROR_LOG']) , 0777 , true);
		// }
		if($message){
			return file_put_contents($this->config['ERROR_LOG'] , $message , FILE_APPEND|LOCK_EX  );
		}elseif($this->error_message){
			file_put_contents($this->config['ERROR_LOG'] , $this->error_message , FILE_APPEND|LOCK_EX  );
			$this->error_message = array();
			return true;
		}else{
			return false;
		}
	}

	/*
	保存$id_content_array的内容到post表中
	$id_content_array格式：
	Array
	(
	    [0] => Array
	        (
	            [ID] => 28316
	            [post_content] => ..............
	            [is_different] => (true / false)
	        )
	)
	*/
	function update_post_content($id_content_array){
		$i = 0;
		foreach ($id_content_array as $each) {
			if($each['is_different']===true){
				$sql = sprintf("UPDATE wp_posts SET post_content='%s' WHERE ID=%d ;" , $this->mysqli->real_escape_string($each['post_content']) , $each['ID'] );
				if ( $result = $this->mysqli->query($sql ) ){
					$i++;
				}
				printf("Post < %d > is Saved. \n\n" , $each['ID']);
			}else{
				printf("Post < %d > Not changed. \n\n" , $each['ID']);
			}

		}
		return $i;
		
	}
	
}

CLASS MY_SOCKET{
	public function __construct(){

	}
	public function __destruct(){

	}

	public function read(){

	}
	public function write($message){

	}

}

CLASS CHILD_PROGRESS_FIX_WP_404 EXTENDS FIX_WP_404{
	public function __construct() {
		//加载配置
		parent::_config() ;
		parent::connect_to_mysql();
		//set domain 
		parent::set_domain();
		parent::create_log_path();	
		parent::define_parm();
		// parent::fault_error_handler();
	}
}



