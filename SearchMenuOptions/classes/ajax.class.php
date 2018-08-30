<?php 
/**
 * 异步调用
 */
class Ajax extends Input
{
	
	function __construct()
	{
		
	}

	public function dispatch()
	{
		$operation = $this->post('operation');
		$response = new Response();
		// set headers
		header('Content-Type: application/json; charset=utf-8');
		header('Content-Encoding: ajax');
		header('Cache-Control: private, max-age=0');
		header('Expires: -1');
		switch ($operation) {
			case 'showmenu':
				global $wpdb;
				$query = $wpdb->get_result("SELECT DISTINCT menu_name FROM left_menu_option");

				break;
		}
	}
}