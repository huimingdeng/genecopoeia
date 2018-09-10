<?php error_reporting(255);
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/wp-config.php");
global $wpdb;
global $user_login;
get_currentuserinfo();

if (!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
		$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
		// $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
		$theValue = mysql_escape_string($theValue);//' /wp-content/plugins/promotion_tag/functions.php' 函数也修改

		switch ($theType) {
			case "text":
			$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
			break;    
			case "long":
			case "int":
			$theValue = ($theValue != "") ? intval($theValue) : "NULL";
			break;
			case "double":
			$theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
			break;
			case "date":
			$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
			break;
			case "defined":
			$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
			break;
		}
		return $theValue;
	}
}

if($user_login){
	$action = $_REQUEST['action'];

	if ('listAll' == $action) 
	{
		$imgSrc = WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__)));
		$sql = sprintf("SELECT id,title,author,source,source_url,content_text FROM _cs_bmnars_contents",$imgSrc);//REPLACE(content_html,'/home/bmnars/data/','%s/data/') as content_html,
		$results = $wpdb->get_results($sql);

		$filter = $wpdb->get_results("SELECT crawler_id FROM _cs_audit_bmnars_status",ARRAY_A);
		$final_results=array();
		if(!empty($filter)){
			foreach($filter as $k=>$v){
				$filters[] = $v['crawler_id'];
			}
			foreach ($results as $k => $obj) {
				if(!in_array($obj->id, $filters)){
					$final_results[] = $obj;
				}
			}
		}
		$response = array(
			// 'sql'=>$sql,
			'data' => $final_results
			);
		echo json_encode($response);
	}

	if ('listOne' == $action || 'queryRep' == $action) 
	{
		$post_id = trim($_REQUEST['id']);
		$imgSrc = WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__)));
		$sql = sprintf("SELECT id,title,author,source,source_url,REPLACE(content_html,'/home/bmnars/data/','%s/data/') as content_html,REPLACE(content_p,'/home/bmnars/data/','%s/data/') as content_p,content_text FROM _cs_bmnars_contents WHERE id = %s",$imgSrc,$post_id);
			switch ($action) {
				case 'listOne':
				$query = $wpdb->get_row($sql);
				break;
				case 'queryRep':
				$query = $wpdb->query($sql);
				break;
			}
			echo json_encode($query);
	}

	if ('allowed' == $action || 'disallowed' == $action) 
	{
		switch ($action) {
			case 'allowed':
				$sql = sprintf("");
				break;
			
			case 'disallowed':
				
				break;
		}
	}
	// 查看
	if ('previewdraft' == $action) {
		$post_id = trim($_REQUEST['id']);
		$imgSrc = WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__)));
		$sql = sprintf("SELECT id,title,author,source,source_url,source_date,REPLACE(content_html,'/home/bmnars/data/','%s/data/') as content_html, content_p,content_text FROM _cs_bmnars_contents WHERE id = %s",$imgSrc,$post_id);
			
		$query = $wpdb->get_row($sql);
		// 替换Python字典无关字符
		$temp = preg_replace('/\d+\:|\{|\}/', '', $query->content_p);
		// 替换图片路径
		$temp2 = str_replace('/home/bmnars/',$imgSrc.'/',$temp);
		// 形成 p 标签数组
		$data = explode(',', $temp2);
		ob_start();
		include('../Views/template.php');
		$html = ob_get_contents();
		ob_end_clean();
		echo json_encode(array('status'=>200,'html'=>$html,'title'=>$query->title));
		
	}
	// 保存发布到 wp 审核通过 1
	if ('savedraft' == $action) {
		$post_id = trim($_REQUEST['id']);
		$imgSrc = WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__)));
		$sql = sprintf("SELECT id,title,author,source,source_url,source_date,REPLACE(content_html,'/home/bmnars/data/','%s/data/') as content_html, content_p,content_text FROM _cs_bmnars_contents WHERE id = %s",$imgSrc,$post_id);
			
		$query = $wpdb->get_row($sql);
		// 替换Python字典无关字符
		$temp = preg_replace('/\d+\:|\{|\}/', '', $query->content_p);
		// 替换图片路径
		$temp2 = str_replace('/home/bmnars/',$imgSrc.'/',$temp);
		// 形成 p 标签数组
		$data = explode(',', $temp2);
		ob_start();
		include('../Views/template.php');
		$html = ob_get_contents();
		ob_end_clean();
		$user_id = get_current_user_id();
		$insert = array(
			'post_title' => $query->title,
	        'post_content' => $html,
	        'post_excerpt' => $query->content_text,
	        'post_type' => 'post',
	        'post_status' => 'draft',
		);
		
		$wp_post_id = wp_insert_post($insert);
		if($wp_post_id!==0) {
			$status = 200;
		}else{
			$status = 500;
		}
		// 修改审核状态
		if($status==200){
			$ok = $wpdb->get_row(sprintf("SELECT id FROM `_cs_audit_bmnars_status` WHERE crawler_id=%s",$post_id));
			if(!empty($ok)){
				$sql = sprintf("UPDATE `_cs_audit_bmnars_status` SET crawler_id=%s,post_id=%s,user_id=%s,status=%s,audit_time=%s WHERE crawler_id=%s ;",$post_id,$wp_post_id,$user_id,1,time(),$post_id);
			}else{
				$sql = sprintf("INSERT INTO `_cs_audit_bmnars_status`(crawler_id,post_id,user_id,status,audit_time) VALUES(%s,%s,%s,%s,%s);",$post_id,$wp_post_id,$user_id,1,time());
			}
			
			$query = $wpdb->query($sql);
		}
		echo json_encode(array('status'=>$status));
		
	}
	// 审核不通过 状态 2
	if ('disallowed' == $action) {
		$post_id = trim($_REQUEST['id']);
		$oksql = sprintf("SELECT id FROM `_cs_audit_bmnars_status` WHERE crawler_id=%s",$post_id);
		$ok = $wpdb->get_row($oksql);
		
		$user_id = get_current_user_id();
		if(!empty($ok)){
			$sql = sprintf("UPDATE `_cs_audit_bmnars_status` SET crawler_id=%s,post_id=%s,user_id=%s,status=%s,audit_time=%s WHERE crawler_id=%s ;",$post_id,0,$user_id,2,time(),$post_id);
		}else{
			$sql = sprintf("INSERT INTO `_cs_audit_bmnars_status`(crawler_id,post_id,user_id,status,audit_time) VALUES(%s,%s,%s,%s,%s);",$post_id,0,$user_id,2,time());
		}
		
		$query = $wpdb->query($sql);

		if($query==1){
			$status = 200;
		}else{
			$status = 500;
		}
		echo json_encode(array('status'=>$status));
	}
}
