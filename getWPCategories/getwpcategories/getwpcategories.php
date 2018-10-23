<?php 
/**
 * 获取网站的固定连接
 * 每过了当前时间间隔才能创建新的json文件。 
 * eg. link-18102302.json 表示 UTC 时间，18年10月23日02时生成的 json 文件。
 */
class GetWPCategories
{
	private static $_instance = null;
	private $wpdb;
	private $sql = "SELECT ID FROM wp_posts WHERE `post_status` = 'publish' AND `post_type` = 'post';";
	private $results;
	private $host;// host, 用于组建访问 json 的地址。
	private $logfile;
	private $log;
	private $filename;// 删除文件名
		

	function __construct()
	{
		require(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."wp-config.php");
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->host = home_url();
		$this->logfile = dirname(__FILE__).DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR.'log.txt';
		
		if( !empty($_POST) && isset($_POST['operation']) ){
			$operation = $_POST['operation'];
			switch ($operation) {
				case 'generate':
					$this->main();
					break;

				case 'delete':
					$this->filename = $_POST['filename'];
					$this->deleteJson();
					break;

				case 'getjsonurl':
					$this->getJsonUrl();
					break;
			}
			
		}
	}

	private function main()
	{
		$this->results = $this->wpdb->get_results($this->sql,ARRAY_A);
		if(!empty($this->results))
		{
			$format = array();
			$this->logfomat();
			foreach ($this->results as $k => $id_a) {
				$format[] = array(
					$id_a['ID'] => get_permalink($id_a['ID'])
				);
			}
			$json = "{\n".'"RECORDS"'.":".json_encode($format)."\n}";
			$path = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'link-'.date("ymdH").'.json';
			if(!file_exists($path)){
				file_put_contents($path, $json);
				if(file_exists($path)){
					$url = $this->host.'/getwpcategories/data/link-'.date("ymdH").'.json';
					update_option("permalink_json",$url);
					file_put_contents($this->logfile,sprintf($this->log,date("Y-m-d H:i:s"),"Note: Url generation success -- ".$url));
					echo json_encode(array('status' => 200, 'path' => $path, 'url' => $url, 'info' => 'Ok, Generate success.' ));
					exit(0);
				}else{
					file_put_contents($this->logfile,sprintf($this->log,date("Y-m-d H:i:s"),' Error, URL generation error.'));
					echo json_encode(array('status' => 500, 'path' => '', 'url' => '', 'info'=>'Sorry, json file cannot be generated, please check if the directory has writable permission.') );
					exit(0);
				}
			}else{
				$url = $this->host.'/data/link-'.date("ymdH").'.json';
				file_put_contents($this->logfile,sprintf($this->log,date("Y-m-d H:i:s")," Instead of generating a new json file, use the old file: ".$url));
				echo json_encode(array('status'=>200, 'path'=>$path, 'url' => $url, 'info' => "The file has been generated." ));
				exit(0);
			}
		}
		else
		{
			return false;
		}
	}

	private function logfomat()
	{
		$this->log = "[ %s ]\t\t"."%s\t"."\n";
	}

	private function deleteJson()
	{
		$filename = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$this->filename;
		$ext = pathinfo($this->filename,PATHINFO_EXTENSION);
		if(file_exists($filename)&&"json"==$ext){
			unlink($filename);
			if(!file_exists($filename)){
				echo json_encode(array('status' => 200, 'info' => 'Ok, Deleted success.' ));
			}else{
				echo json_encode(array('status' => 500, 'info' => 'Deleted failed.' ));
			}
		}else{
			echo json_encode(array('status' => 500, 'info' => 'Sorry, only the json file can be deleted.' ));
		}
	}

	private function getJsonUrl()
	{
		$json = get_option('permalink_json');
		if($json){
			echo json_encode(array('status' => 200, 'url'=>$json, 'info' => 'Ok, success.' ));
		}else{
			echo json_encode(array('status' => 500, 'url'=>'', 'info' => 'Failed.' ));
		}
	}

	public static function getInstance()
	{
		if (NULL === self::$_instance) {
			self::$_instance = new self();	
		}
		return self::$_instance;
	}

	public function __destruct(){
		$this->results = null;
		$this->wpdb = null;
		self::$_instance = null;
	}
}

GetWPCategories::getInstance();