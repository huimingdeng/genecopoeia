<?php 
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
		$sql = sprintf("SELECT id,title,author,source,source_url,REPLACE(content_html,'/home/bmnars/data/','%s/data/') as content_html,content_text FROM _cs_bmnars_contents",$imgSrc);
		$results = $wpdb->get_results($sql);
		$response = array(
			// 'sql'=>$sql,
			'data' => $results
			);
		echo json_encode($response);
	}

	if ('listOne' == $action || 'queryRep' == $action) 
	{
		$post_id = trim($_REQUEST['id']);
		$imgSrc = WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__)));
		$sql = sprintf("SELECT id,title,author,source,source_url,REPLACE(content_html,'/home/bmnars/data/','%s/data/') as content_html,content_text FROM _cs_bmnars_contents WHERE id = %s",$imgSrc,$post_id);
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
}
