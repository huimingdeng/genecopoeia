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
		$sql = "SELECT * FROM _cs_bmnars_contents";
		$results = $wpdb->get_results($sql);
		$response = array(
			// 'sql'=>$sql,
			'data' => $results
			);
		echo json_encode($response);
	}
}
