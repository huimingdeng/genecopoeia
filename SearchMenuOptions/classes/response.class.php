<?php 
/**
 * 响应函数
 */
class Response
{
	
	private $_headers_sent = FALSE;				// TRUE when headers are sent on instantiation
	private $_capture = FALSE;					// TRUE when capturing output
	private $data;

	public function __construct($send_headers = FALSE)
	{
		if ($send_headers) {
			$this->_headers_sent = TRUE;
		}
	}

	public function set($action,$data=array())
	{
		switch ($action) {
			case 'list':
				
				$menu_name = $data['menu_name'];
				// print_r($data);
				if(!empty($data)){
					foreach ($data['results'] as $k => $v) {
						$newdata[$v['menu_name']][] = $v;
						$newtitle[$v['menu_name']] = $v['menu_name'];
					}
				}
				$title = array_unique($newtitle);
				sort($title);
				// print_r($newdata);
				ob_start();
				include(dirname(dirname(__FILE__)).'/views/'.'list.php');
				$this->data = ob_get_contents();
				ob_end_clean();
				break;
			case 'listOne':
				$title = $data['title'];
				// print_r($title);
				$newdata = $data['results'];
				$menu_name = $data['menu_name'];
				ob_start();
				include(dirname(dirname(__FILE__)).'/views/'.'listOne.php');
				$this->data = ob_get_contents();
				ob_end_clean();
				break;
		}
	}

	/**
	 * Sends the contents of the ApiResponse instance to the caller of the API
	 * @param boolean $exit TRUE if script is to end after sending data; otherwise FALSE (default)
	 */
	public function send($exit = TRUE)
	{
		if($this->_headers_sent){
			echo json_encode(array('msg'=>$this->data));
			exit(0);
		}
	}

}