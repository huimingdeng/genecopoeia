<?php 
/**
 * 异步调用
 */
class Ajax extends Input
{
	public function dispatch()
	{
		global $wpdb;
		$operation = $this->post('operation');
		$response = new Response(TRUE);
		// set headers
		header('Content-Type: application/json; charset=utf-8');
		header('Content-Encoding: ajax');
		header('Cache-Control: private, max-age=0');
		header('Expires: -1');
		switch ($operation) {
			case 'showmenu':
				$menu_name = $this->post('menu_name');
				(empty($menu_name))?($menu_name='aav_crispr_detail_page'):($menu_name);
				// $query = $wpdb->get_results("SELECT DISTINCT menu_name FROM left_menu_option",ARRAY_A);
				$results = $wpdb->get_results("SELECT * FROM left_menu_option;",ARRAY_A);
				$data = array('results'=>$results,'menu_name'=>$menu_name);
				$response->set('list',$data);
				
				break;
			case 'showOne':
				$query = $wpdb->get_results("SELECT DISTINCT menu_name FROM left_menu_option",ARRAY_A);
				$title = $query;
				// print_r($query);
				$menu_name = $this->post('menu_name');
				(empty($menu_name))?($menu_name='aav_crispr_detail_page'):($menu_name);
				$sql = sprintf("SELECT * FROM left_menu_option WHERE menu_name = '%s';",$menu_name);
				$results = $wpdb->get_results($sql,ARRAY_A);
				$data = array('title'=>$title,'results'=>$results,'menu_name'=>$menu_name);
				$response->set('listOne',$data);
				break;
			case 'addOnePage':

				$response->set('addOnePage',array());
				break;
			case 'addOne':
				$menu_name = $this->post('menu_name');
				$classify_name = $this->post('classify_name');
				$classify_order = $this->post_int('classify_order');
				$item_name = $this->post('item_name');
				$item_display_name = $this->post('item_display_name');
				$item_value = $this->post('item_value');
				$item_order = $this->post_int('item_order');
				$compare_mode = $this->post('compare_mode');

				$query = $wpdb->query(sprintf("INSERT INTO left_menu_option(menu_name,classify_name,classify_order,item_name,item_display_name,item_value,item_order,compare_mode) VALUES('%s','%s',%s,'%s','%s','%s',%s,'%s');",$menu_name,$classify_name,$classify_order,$item_name,$item_display_name,$item_value,$item_order,$compare_mode));
				$response->set('add',$query);
				
				
				break;

			case 'editOnePage':
				$sn = $this->post_int('sn');
				if(!empty($sn)){
					$query = $wpdb->get_row(sprintf("SELECT * FROM left_menu_option WHERE sn=%s",$sn),ARRAY_A);
					$response->set('editOnePage',$query);
				}else{
					$response->set('editOnePage',array());
				}
				break;

			case 'editOne':
				$sn = $this->post_int('sn');
				$menu_name = $this->post('menu_name');
				$classify_name = $this->post('classify_name');
				$classify_order = $this->post_int('classify_order');
				$item_name = $this->post('item_name');
				$item_display_name = $this->post('item_display_name');
				$item_value = $this->post('item_value');
				$item_order = $this->post_int('item_order');
				$compare_mode = $this->post('compare_mode');

				$query = $wpdb->query(sprintf("UPDATE left_menu_option SET `sn`=%s, `menu_name`='%s', `classify_name`='%s', `classify_order`=%s, `item_name`='%s', `item_display_name`='%s', `item_value`='%s', `item_order`=%s, `compare_mode`='%s' WHERE `sn`=%s",$sn,$menu_name,$classify_name,$classify_order,$item_name,$item_display_name,$item_value,$item_order,$compare_mode,$sn));
				break;

			case 'delOne':
				$sn = $this->post_int('sn');
				if(!empty($sn)){
					$query = $wpdb->query(sprintf("DELETE FROM left_menu_option WHERE sn=%s",$sn));
					$response->set('delete',$query);
				}
				break;
		}

		$response->send();
	}
}