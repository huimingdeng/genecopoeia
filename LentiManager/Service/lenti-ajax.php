<?php date_default_timezone_set("Asia/Shanghai");
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

if(!function_exists('array2str')){
	function array2str($array)
	{
		$tmp = array();
		if(count($array)>1){
			foreach ($array as $k => $v) {
				$tmp[] = "'$v'";
			}
		}else{
			return "'".implode(',',$array)."'";
		}
		return implode(',',$tmp);
	}
}

function checkcatalog($catalog){
	global $wpdb;
	if( preg_match( "/^[A-Za-z0-9_\-]+$/", $catalog ) ){//验证通过时查询
		$sql = sprintf( "SELECT * FROM _cs_lenti_collection WHERE BINARY catalog = %s", GetSQLValueString( $catalog, "text" ) );
		$query = $wpdb->query($sql);
		return ($query>0)?true:false;
	}else{
		return false;
	}
}

if($user_login){
	$action = $_REQUEST['action'];
	// 获取管理用户
	if ('listUsers'==$action)
	{
		$sql = "SELECT user_login FROM wp_users WHERE id not in ( SELECT user_id FROM wp_usermeta WHERE meta_key = 'ja_disable_user' AND meta_value = 1 )";
		$results = $wpdb->get_results($sql,ARRAY_N);
		$allowed_user = get_option('lenti_manager_permission');
  		$allowed_user_options = json_decode($allowed_user,true);

		foreach ($results as $row) {
			$users[] = array(
				'users'=>$row[0],
				'action'=>in_array($row[0],$allowed_user_options)?($row[0]=='admin')?("<a class='btn btn-danger btn-xs disabled' onclick='permissions(this,\"".$row[0]."\");'>Disallow</a>"):("<a class='btn btn-danger btn-xs' onclick='permissions(this,\"".$row[0]."\");'>Disallow</a>"):("<a class='btn btn-primary btn-xs' onclick='permissions(this,\"".$row[0]."\");'>Allow</a>")
			);
		}

		$response = array('data'=>$users);
		echo json_encode($response);
		// echo json_encode($users);
	}

	// 更改权限
	if ('updatePermissions'==$action)
	{
		$user = $_REQUEST['manager'];
		$allowed_user = get_option('lenti_manager_permission');
  		$allowed_user_options = json_decode($allowed_user,true);
  		
  		$index = array_search($user, $allowed_user_options);
		if ($index !== false){ 
			array_splice($allowed_user_options, $index, 1); 
			$return = 0;
			$info = "$user disables the operation management plug-in. ";
		}else{ 
			array_push($allowed_user_options,$user);
			$return = 1;
			$info = "$user allows action management plug-ins. ";
		}

		update_option('lenti_manager_permission',json_encode($allowed_user_options));
  		$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
  		file_put_contents("./logs/logs.txt", $errormsg, FILE_APPEND);
		$response=array('value'=>$return,'msg'=>'Permission Settings to be successful!');
			echo json_encode($response);	
	}

	// 获取所有慢病毒产品
	if ('listAll'==$action) 
	{
		$sql = "SELECT * FROM _cs_lenti_collection";
		$results = $wpdb->get_results($sql);
		$response = array(
			// 'sql'=>$sql,
			'data' => $results
			);
		echo json_encode($response);
	}

	// 查找回收站
	if ('listAllBack'==$action) 
	{
		$sql = "SELECT * FROM _cs_lenti_collection_delete_back";
		$results = $wpdb->get_results($sql);
		$response = array(
			// 'sql'=>$sql,
			'data' => $results
			);
		echo json_encode($response);
	}

	//处理查询指定产品的操作，返回单条记录
	if ( 'listOne' == $action || 'queryRep' == $action) 
	{
		$catalog = trim( stripslashes( $_REQUEST['catalog'] ) );
		if( preg_match( "/^[A-Za-z0-9_\-]+$/", $catalog ) ){//验证通过时查询
			$sql = sprintf( "SELECT * FROM _cs_lenti_collection WHERE BINARY catalog = %s", GetSQLValueString( $catalog, "text" ) );
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
	}

	if ( 'listFiles' == $action || 'listLogs' == $action)
	{
		require_once "fileOperation.class.php";
		switch ($action) {
			case 'listFiles':
				$path = dirname(__FILE__).DIRECTORY_SEPARATOR.'uploads';
				break;
			
			case 'listLogs':
				$path = dirname(__FILE__).DIRECTORY_SEPARATOR.'logs';
				break;
		}
		
		$fo = new fileOperaion($path);
		$res = $fo->readDirectory();
		$html = array();
		if(!empty($res)){
			foreach ($res as $k => $value) {
				if('file' == $k){
					foreach ($value as $file) {
						$p=$path.DIRECTORY_SEPARATOR.$file;
						$html[]=array(
							'Filename' => $file,
							'Size' => $fo->transByte(filesize($p)),
							'Readable' => is_readable($p)?"<span class=\"glyphicon glyphicon-ok glyphicon-green\"></span>":"<span class=\"glyphicon glyphicon-remove glyphicon-red\"></span>",
							'Writable' => is_writable($p)?"<span class=\"glyphicon glyphicon-ok glyphicon-green\"></span>":"<span class=\"glyphicon glyphicon-remove glyphicon-red\"></span>",
							'Executable' => is_executable($p)?"<span class=\"glyphicon glyphicon-ok glyphicon-green\"></span>":"<span class=\"glyphicon glyphicon-remove glyphicon-red\"></span>",
							'created' => date("Y-m-d H:i:s",filectime($p)),
							'modification' => date("Y-m-d H:i:s",filemtime($p)),
							'access' => date("Y-m-d H:i:s",fileatime($p)),
						);
					}
				}
			}
		}
		$response = array(
			'data' => $html
			);
		echo json_encode($response);
	}

	if ('lookLog' == $action) 
	{
		$filename = trim($_REQUEST['filename']);
		$path = dirname(__FILE__).DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR;
		$pathFile = $path.$filename;
		$data = file($pathFile);
		$html = '';
		if(!empty($data)){
			foreach ($data as $k => $v) {
				$html .= "<p><span class='logsnum'>".($k+1)."</span>".$v."</p>";
			}
		}
		$response = array(
			'data' => $html
		);
		echo json_encode($response);
	}


	if ('lookExcel'==$action)
	{
		$filename = trim($_REQUEST['filename']);
		require_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'phpexcel'.DIRECTORY_SEPARATOR.'PHPExcel.php');
		$path = dirname(__FILE__).DIRECTORY_SEPARATOR.'uploads';
		$inputFileName = $path.DIRECTORY_SEPARATOR.$filename;
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$data = array();
			$totalrow = 0;
			for ($row = 2; $row <= $highestRow; $row++){
				$rowDatas = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
				if(!empty($rowDatas)){
					foreach ($rowDatas as $rowData) {
						$data[] = array(
						'catalog' => $rowData[0], 
						'type' => $rowData[1],
						'description' => htmlspecialchars($rowData[2]), 
						'volume' => $rowData[3], 
						'titer' => htmlspecialchars($rowData[4]), 
						'titer_description' => htmlspecialchars($rowData[5]), 
						'purity' => $rowData[6], 
						'size' => $rowData[7], 
						'vector' => $rowData[8], 
						'delivery_format' => htmlspecialchars($rowData[9]), 
						'delivery_time' => $rowData[10], 
						'symbol_link' => $rowData[11], 
						'pdf_link' => $rowData[12], 
						'price' => $rowData[13], 
						'priority' => $rowData[14],
						);
					}
				}
				
			}
			$response = array(
				'data' => $data
			);
			echo json_encode($response);

		} catch(Exception $e) {
			echo json_encode(array('No'=>500,'Msg'=>'Error loading file:'.pathinfo($inputFileName,PATHINFO_BASENAME).': '.$e->getMessage()));
			die();
		}
	}

	if ('delExcel'==$action)
	{
		$filename = trim($_REQUEST['filename']);
		require_once "fileOperation.class.php";
		$path = dirname(__FILE__).DIRECTORY_SEPARATOR.'uploads';
		$fo = new fileOperaion($path);
		$path_name = $path.DIRECTORY_SEPARATOR.$filename;
		$msg = $fo->delFile($path_name);
		$query = 0;
		$info = "Delete Excel $filename failed. ";
		if($msg['Status']==200){ 
			$query=1;
			$info = "The Excel $filename was deleted successfully. ";
		}
		$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
		if(!$query){
			file_put_contents("./logs/error_logs.txt", $errormsg, FILE_APPEND);
		}
		file_put_contents("./logs/logs.txt", $errormsg, FILE_APPEND);
		$response = array(
			'query'=>$query,
			'Msg' => $msg['Msg']
			);
		echo json_encode($response);
	}

	// 添加与修改
	if ( 'add' == $action || 'edit' == $action )
	{
		$catalog = trim( stripslashes( $_REQUEST['catalog'] ) );
		$type = GetSQLValueString( trim( stripslashes( $_REQUEST['type'] ) ), "text" ); ;
		$volume = GetSQLValueString( trim( stripslashes( $_REQUEST['volume'] ) ), "text" );;
		$description = GetSQLValueString( trim( stripslashes( $_REQUEST['description'] ) ), "text" );;
		$titer = GetSQLValueString( trim( stripslashes( $_REQUEST['titer'] ) ), "text" );
		$titer_description = GetSQLValueString( trim( stripslashes( $_REQUEST['titer_description'] ) ), "text" );
		$purity = GetSQLValueString( trim( stripslashes( $_REQUEST['purity'] ) ), "text" );;
		$size = GetSQLValueString( trim( stripslashes( $_REQUEST['size'] ) ) , 'int');
		$vector = GetSQLValueString( trim( stripslashes( $_REQUEST['vector'] ) ), "text" );;
		$delivery_format = GetSQLValueString( trim( stripslashes( $_REQUEST['delivery_format'] ) ), "text" );
		$delivery_time = GetSQLValueString( trim( stripslashes( $_REQUEST['delivery_time'] ) ), "text" );;
		$symbol_link = GetSQLValueString( trim( stripslashes( $_REQUEST['symbol_link'] ) ), "text" );
		$pdf_link = GetSQLValueString( trim( stripslashes( $_REQUEST['pdf_link'] ) ), "text" );
		$price = GetSQLValueString( trim( stripslashes( $_REQUEST['price'] ) ), 'int');
		$priority = GetSQLValueString(trim( stripslashes( $_REQUEST['priority'] ) ),'int');

		//校验
		$match_catalog = preg_match("/^[A-Za-z0-9\-]+$/",$catalog);
		$match_price = preg_match("/^[1-9]\d*$/",$price);
		$match_size = $size ? preg_match("/^[1-9]\d*$/",$size) : 1;

		$catalog = GetSQLValueString($catalog,'text');

		if($match_size&&$match_price&&$match_catalog){
			switch ($action) {
				case 'add':
					$sql = sprintf("INSERT INTO _cs_lenti_collection(catalog,type,description,volume,titer,titer_description,purity,size,vector,delivery_format,delivery_time,symbol_link,pdf_link,price,priority) VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",$catalog, $type, $description, $volume, $titer, $titer_description, $purity, $size, $vector, $delivery_format, $delivery_time, $symbol_link, $pdf_link, $price, $priority);
					break;
				
				case 'edit':
					$sql = sprintf("UPDATE _cs_lenti_collection SET catalog=%s,type=%s,description=%s,volume=%s,titer=%s,titer_description=%s,purity=%s,size=%s,vector=%s,delivery_format=%s,delivery_time=%s,symbol_link=%s,pdf_link=%s,price=%s,priority=%s WHERE catalog = %s",$catalog, $type, $description, $volume, $titer, $titer_description, $purity, $size, $vector, $delivery_format, $delivery_time, $symbol_link, $pdf_link, $price, $priority, $catalog);
					break;
			}
			$query = $wpdb->query($sql);
			if($query){
				$info = "$action successfully.";
				$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
			}else{
				$info = "$action failed.";
				$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
				file_put_contents("./logs/error_logs.txt", $errormsg, FILE_APPEND);
			}
			file_put_contents("./logs/logs.txt", $errormsg, FILE_APPEND);
			$response = array(
				'sql'=>$sql,
				// 'catalog'=>$match_catalog,
				// 'price'=>$match_price,
				// 'size'=>$match_size,
				'query' => $query
				);
		}else{
			$response = array('msg'=>'data error');
			$info = "$action failed because of data error. ";
			$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
			file_put_contents("./logs/error_logs.txt", $errormsg, FILE_APPEND);
			file_put_contents("./logs/logs.txt", $errormsg, FILE_APPEND);
		}
		echo json_encode($response);
	}

	//删除
	if ('delete'==$action)
	{
		$catalog = trim( stripslashes( $_REQUEST['catalog'] ) );
		$catalog = GetSQLValueString($catalog , "text" );
		$sql = sprintf( "SELECT * FROM _cs_lenti_collection WHERE BINARY catalog = %s", $catalog );
		$res = $wpdb->get_row($sql);
		if(!empty($res)){
			$backsql = sprintf("INSERT INTO _cs_lenti_collection_delete_back(catalog,type,description,volume,titer,titer_description,purity,size,vector,delivery_format,delivery_time,symbol_link,pdf_link,price,priority,del_time) VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",GetSQLValueString($res->catalog,'text'), GetSQLValueString($res->type,'text'), GetSQLValueString($res->description,'text'), GetSQLValueString($res->volume,'text'), GetSQLValueString($res->titer,'text'), GetSQLValueString($res->titer_description,'text'), GetSQLValueString($res->purity,'text'), GetSQLValueString($res->size,'int'), GetSQLValueString($res->vector,'text'), GetSQLValueString($res->delivery_format,'text'), GetSQLValueString($res->delivery_time,'text'), GetSQLValueString($res->symbol_link,'text'), GetSQLValueString($res->pdf_link,'text'), GetSQLValueString($res->price,'int'), GetSQLValueString($res->priority,'int'), GetSQLValueString(date('Y-m-d H:i:s'),'text'));
			// 备份数据
			$query_back = $wpdb->query($backsql);
			if($query_back){
				$info = "$catalog was successfully backed up to `_cs_lenti_collection_delete_back`!";
				$sql_1 = "DELETE FROM _cs_lenti_collection WHERE catalog = $catalog";
				$query = $wpdb->query($sql_1);
				$response = array(
					'query'=>$query
				);
				if($query){
					$info .= " $catalog was successfully deleted from `_cs_lenti_collection`!";
				}else{
					$info .= " The deletion of $catalog from `_cs_lenti_collection` failed!";
					$info1 = "The deletion of $catalog from `_cs_lenti_collection` failed!";
					$errmsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info1."\n";
					file_put_contents("./logs/error_logs.txt", $errmsg, FILE_APPEND);
				}
				$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
			}else{
				$response = array(
					'query'=>$query_back
				);
				$info = "$catalog backup failed!";
				$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
				file_put_contents("./logs/error_logs.txt", $errormsg, FILE_APPEND);
			}
			file_put_contents("./logs/logs.txt", $errormsg, FILE_APPEND);
		}else{
			$response = array(
				'query'=> '0',
				'msg' => 'data error.'
			);
		}
		
		echo json_encode($response);
		
	}

	// 还原
	if ('restore'==$action)
	{
		$catalog = trim( stripslashes( $_REQUEST['catalog'] ) );
		$catalog = GetSQLValueString($catalog , "text" );
		$sql = sprintf( "SELECT * FROM _cs_lenti_collection_delete_back WHERE BINARY catalog = %s", $catalog );
		$res = $wpdb->get_row($sql);
		if(!empty($res)){
			$restore_sql = sprintf("INSERT INTO _cs_lenti_collection(catalog,type,description,volume,titer,titer_description,purity,size,vector,delivery_format,delivery_time,symbol_link,pdf_link,price,priority) VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",GetSQLValueString($res->catalog,'text'), GetSQLValueString($res->type,'text'), GetSQLValueString($res->description,'text'), GetSQLValueString($res->volume,'text'), GetSQLValueString($res->titer,'text'), GetSQLValueString($res->titer_description,'text'), GetSQLValueString($res->purity,'text'), GetSQLValueString($res->size,'int'), GetSQLValueString($res->vector,'text'), GetSQLValueString($res->delivery_format,'text'), GetSQLValueString($res->delivery_time,'text'), GetSQLValueString($res->symbol_link,'text'), GetSQLValueString($res->pdf_link,'text'), GetSQLValueString($res->price,'int'), GetSQLValueString($res->priority,'int'));
			// 还原数据
			$query_restore = $wpdb->query($restore_sql);
			if($query_restore){
				$info = "$catalog was successfully restored from `_cs_lenti_collection_delete_back` to `_cs_lenti_collection`.";
				$sql_1 = "DELETE FROM _cs_lenti_collection_delete_back WHERE catalog = $catalog";
				$query = $wpdb->query($sql_1);
				$response = array(
					'query'=>$query
				);
				if($query){
					$info.= "$catalog was successfully deleted from `_cs_lenti_collection_delete_back`.";
				}else{
					$info.= " The deletion of $catalog from `_cs_lenti_collection_delete_back`.";
					$info1 = "The deletion of $catalog from `_cs_lenti_collection_delete_back`.";
					$errmsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info1."\n";
					file_put_contents("./logs/error_logs.txt", $errmsg, FILE_APPEND);
				}
				$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
			}else{
				$response = array(
					'query'=>$query_restore
				);
				$info = "$catalog failed to restore from `_cs_lenti_collection_delete_back` to `_cs_lenti_collection`.";
				$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
				file_put_contents("./logs/error_logs.txt", $errormsg, FILE_APPEND);
			}
			file_put_contents("./logs/logs.txt", $errormsg, FILE_APPEND);
		}else{
			$response = array(
				'query'=> '0',
				'msg' => 'data error.'
			);
		}
		
		echo json_encode($response);
	}

	// 批量还原
	if ('restoreAll'==$action)
	{
		$catalogs =  $_REQUEST['catalogs'];
		if(!empty($catalogs)){
			if(count($catalogs)>1){
				$sql = sprintf("SELECT * FROM _cs_lenti_collection_delete_back WHERE catalog in (%s)",array2str($catalogs));
				$data = $wpdb->get_results($sql);
			}elseif(count($catalogs)==1){
				$catalog = GetSQLValueString($catalogs[0],'text');
				$sql = sprintf( "SELECT * FROM _cs_lenti_collection_delete_back WHERE BINARY catalog = %s", $catalog );
				$res = $wpdb->get_row($sql);
				$data[0] = $res;
			}
			if(isset($data)&&!empty($data)){
				$insertSql = "INSERT INTO _cs_lenti_collection(catalog,type,description,volume,titer,titer_description,purity,size,vector,delivery_format,delivery_time,symbol_link,pdf_link,price,priority) VALUES";
				$deleteSql = "DELETE FROM _cs_lenti_collection_delete_back WHERE ";
				$tmpsql = array();
				$tmpsql2 = array();
				foreach ($data as $k => $obj) {
					$tmpsql[] = sprintf("(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",GetSQLValueString($obj->catalog,'text'), GetSQLValueString($obj->type,'text'), GetSQLValueString($obj->description,'text'), GetSQLValueString($obj->volume,'text'), GetSQLValueString($obj->titer,'text'), GetSQLValueString($obj->titer_description,'text'), GetSQLValueString($obj->purity,'text'), GetSQLValueString($obj->size,'int'), GetSQLValueString($obj->vector,'text'), GetSQLValueString($obj->delivery_format,'text'), GetSQLValueString($obj->delivery_time,'text'), GetSQLValueString($obj->symbol_link,'text'), GetSQLValueString($obj->pdf_link,'text'), GetSQLValueString($obj->price,'int'), GetSQLValueString($obj->priority,'int'));
					$tmpsql2[] = "catalog=".GetSQLValueString($obj->catalog,'text');
				}
				if(!empty($tmpsql)&&!empty($tmpsql2)){
					$insertSql.= implode(',',$tmpsql);
					$deleteSql.= implode(' OR ',$tmpsql2);
					$query = $wpdb->query($insertSql);
					$query_del = $wpdb->query($deleteSql);
					$response = array(
						'query' => $query,
						'delete' => $query_del,
						'insertSql' => $insertSql,
						'deleteSql' => $deleteSql
					);
					if($query){
						$info = "Data was successfully restored from the recycle bin.";
					}else{
						$info = "Sorry, data recovery failed from recycle bin.";
						$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
						file_put_contents("./logs/error_logs.txt",$errormsg,FILE_APPEND);
					}
					if($query_del){
						$info .= " Data was successfully deleted from the recycle bin.";
					}else{
						$info = "Sorry, data cannot be deleted from recycle bin failed.";
						$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
						file_put_contents("./logs/error_logs.txt",$errormsg,FILE_APPEND);
					}
					$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
					file_put_contents("./logs/logs.txt",$errormsg,FILE_APPEND);
				}else{
					$response = array(
						'query' => false,
						'data' => $insertSql
					);
					$info = "Sorry, your SQL statement has syntax errors.";
					$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
					file_put_contents("./logs/error_logs.txt",$errormsg,FILE_APPEND);
					file_put_contents("./logs/logs.txt",$errormsg,FILE_APPEND);
				}
					
			}else{
				$response = array(
					'query' => false,
					'msg' => 'data error.'
				);
				$info = "Sorry, batch restore cannot be performed because the data was not checked.";
				$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
				file_put_contents("./logs/error_logs.txt",$errormsg,FILE_APPEND);
				file_put_contents("./logs/logs.txt",$errormsg,FILE_APPEND);
			}
			
		}else{
			$response = array(
				'query' => false,
				'data' => $data
			);
			$info = "Sorry, batch restore cannot be performed because the data was not checked.";
			$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
			file_put_contents("./logs/error_logs.txt",$errormsg,FILE_APPEND);
			file_put_contents("./logs/logs.txt",$errormsg,FILE_APPEND);
		}
		
		echo json_encode($response);
	}
	
	// 批量导入 version < 0.5.3
	if ('uploadsv1'==$action) 
	{
		require_once 'uploadfile.class.php';
		$file = $_FILES['file'];
		$upload = new uploadfile();
		$msg = $upload->uploadFile();
		$obj = json_decode($msg);
		if($obj->No==200){
			$filePath = $obj->Msg;//如果上传成功，则返回文件路径
			$exts = array('xlsx','xls','xlsm');
			if(file_exists($filePath)&&in_array(pathinfo($filePath,PATHINFO_EXTENSION),$exts)){
				require_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'phpexcel'.DIRECTORY_SEPARATOR.'PHPExcel.php');
				$inputFileName = $filePath;
				try {
					$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($inputFileName);
					$sheet = $objPHPExcel->getSheet(0);
					$highestRow = $sheet->getHighestRow();
					$highestColumn = $sheet->getHighestColumn();
					$tmpsql = array();
					$totalrow = 0;
					for ($row = 2; $row <= $highestRow; $row++){
						$rowDatas = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
						if(!empty($rowDatas)){
							foreach ($rowDatas as $rowData) {
								$tmpsql[] = sprintf("(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",GetSQLValueString($rowData[0],'text'), GetSQLValueString($rowData[1],'text'), GetSQLValueString($rowData[2],'text'), GetSQLValueString($rowData[3],'text'), GetSQLValueString($rowData[4],'text'), GetSQLValueString($rowData[5],'text'), GetSQLValueString($rowData[6],'text'), GetSQLValueString($rowData[7],'int'), GetSQLValueString($rowData[8],'text'), GetSQLValueString($rowData[9],'text'), GetSQLValueString($rowData[10],'text'), GetSQLValueString($rowData[11],'text'), GetSQLValueString($rowData[12],'text'), GetSQLValueString($rowData[13],'int'), GetSQLValueString($rowData[14],'int'));
								
							}
							$totalrow++;
						}
						
					}
					// 执行导入先备份
					$clearsql = "DELETE FROM _cs_lenti_collection_back";
					$clearquery = $wpdb->query($clearsql);
					if($clearquery){
						$backsql = "INSERT INTO _cs_lenti_collection_back SELECT * FROM _cs_lenti_collection";
						$backquery = $wpdb->query($backsql);
						if($backquery){
							$insertSql = "INSERT INTO _cs_lenti_collection(catalog,type,description,volume,titer,titer_description,purity,size,vector,delivery_format,delivery_time,symbol_link,pdf_link,price,priority) VALUES".implode(',',$tmpsql);
							$query = $wpdb->query($insertSql);
							if($query){
								echo json_encode(array('No'=>200,'Msg'=>'The data has been imported in batches, please check.'));
							}else{
								// 如果导入失败，则还原数据
								$clearsql1 = "DELETE FROM _cs_lenti_collection;";
								$clearquery1 = $wpdb->query($clearsql1);
								$restoresql = "INSERT INTO _cs_lenti_collection SELECT * FROM _cs_lenti_collection_back;";
								$restorequery = $wpdb->query($restoresql);
								echo json_encode(array('No'=>500,'Msg'=>'Batch import data error.'));
							}
						}else{
							echo json_encode(array('No'=>500,'Msg'=>'Data backup error2.'));
							die();
						}
					}else{
						echo json_encode(array('No'=>500,'Msg'=>'Data backup error.'));
							die();
					}

				} catch(Exception $e) {
					echo json_encode(array('No'=>500,'Msg'=>'Error loading file:'.pathinfo($inputFileName,PATHINFO_BASENAME).': '.$e->getMessage()));
					die();
				}
			}else{
				echo json_encode(array('No'=>400,'Msg'=>'Sorry，The file you uploaded is not in the correct format.'));
			}
			
		}else{
			echo $msg;
		}
	}

	if ('uploads'==$action) 
	{
		require_once 'uploadfile.class.php';
		$file = $_FILES['file'];
		$path = dirname(__FILE__).DIRECTORY_SEPARATOR."uploads";
		$upload = new uploadfile('file',$path);
		$msg = $upload->uploadFile();
		// print_r();
		$obj = json_decode($msg);
		if($obj->No==200){
			$filePath = $obj->Msg;//如果上传成功，则返回文件路径
			$exts = array('xlsx','xls','xlsm');
			if(file_exists($filePath)&&in_array(pathinfo($filePath,PATHINFO_EXTENSION),$exts)){
				require_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'phpexcel'.DIRECTORY_SEPARATOR.'PHPExcel.php');
				$inputFileName = $filePath;
				try {
					$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($inputFileName);
					$sheet = $objPHPExcel->getSheet(0);
					$highestRow = $sheet->getHighestRow();
					$highestColumn = $sheet->getHighestColumn();
					
					$totalrow = 0;
					for ($row = 2; $row <= $highestRow; $row++){
						$rowDatas = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
						if(!empty($rowDatas)){
							foreach ($rowDatas as $rowData) {
								$insertSql = ( (!checkcatalog($rowData[0]))?sprintf("INSERT INTO _cs_lenti_collection(catalog,type,description,volume,titer,titer_description,purity,size,vector,delivery_format,delivery_time,symbol_link,pdf_link,price,priority) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s);",GetSQLValueString($rowData[0],'text'), GetSQLValueString($rowData[1],'text'), GetSQLValueString($rowData[2],'text'), GetSQLValueString($rowData[3],'text'), GetSQLValueString($rowData[4],'text'), GetSQLValueString($rowData[5],'text'), GetSQLValueString($rowData[6],'text'), GetSQLValueString($rowData[7],'int'), GetSQLValueString($rowData[8],'text'), GetSQLValueString($rowData[9],'text'), GetSQLValueString($rowData[10],'text'), GetSQLValueString($rowData[11],'text'), GetSQLValueString($rowData[12],'text'), GetSQLValueString($rowData[13],'int'), GetSQLValueString($rowData[14],'int')):sprintf("UPDATE `_cs_lenti_collection` SET `catalog`=%s, `type`=%s, `description`=%s, `volume`=%s, `titer`=%s, `titer_description`=%s, `purity`=%s, `size`=%s, `vector`=%s, `delivery_format`=%s, `delivery_time`=%s, `symbol_link`=%s, `pdf_link`=%s, `price`=%s, `priority`=%s WHERE (`catalog`=%s);",GetSQLValueString($rowData[0],'text'), GetSQLValueString($rowData[1],'text'), GetSQLValueString($rowData[2],'text'), GetSQLValueString($rowData[3],'text'), GetSQLValueString($rowData[4],'text'), GetSQLValueString($rowData[5],'text'), GetSQLValueString($rowData[6],'text'), GetSQLValueString($rowData[7],'int'), GetSQLValueString($rowData[8],'text'), GetSQLValueString($rowData[9],'text'), GetSQLValueString($rowData[10],'text'), GetSQLValueString($rowData[11],'text'), GetSQLValueString($rowData[12],'text'), GetSQLValueString($rowData[13],'int'), GetSQLValueString($rowData[14],'int'),GetSQLValueString($rowData[0],'text')));
								$query = $wpdb->query($insertSql);
								$info = '';
								if($query){
									$info = (!checkcatalog($rowData[0]))?($rowData[0]. " inserted successfully"):($rowData[0]. " update successfully");
									$msginfo[] = $info;
									$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
									$isOk = ($query>0)?true:false;
								}else{
									$info = (!checkcatalog($rowData[0]))?($rowData[0]. " insertion failed"):($rowData[0]. " update failed");
									$msginfo[] = $info;
									$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
									file_put_contents("./logs/error_logs.txt", $errormsg, FILE_APPEND);
									$isOk = ($query>0)?true:false;
								}
								file_put_contents("./logs/logs.txt", $errormsg, FILE_APPEND);
							}
							$totalrow++;
						}else{
							$filename = basename($filePath);
							$isOk = false;
							$info = "Sorry, the Excel data in the file $filename you uploaded is empty, please upload again.";
							$msginfo[] = $info;

							$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
							file_put_contents("./logs/error_logs.txt", $errormsg, FILE_APPEND);
							file_put_contents("./logs/logs.txt", $errormsg, FILE_APPEND);
						}
						
					}
					if(!$totalrow){
						$filename = basename($filePath);
						$isOk = false;
						$info = "Sorry, the Excel data in the file $filename you uploaded is empty, please upload again.";
						$msginfo[] = $info;

						$errormsg = "[ ".date("Y-m-d H:i:s",time())." ]\t".$info."\n";
						file_put_contents("./logs/error_logs.txt", $errormsg, FILE_APPEND);
						file_put_contents("./logs/logs.txt", $errormsg, FILE_APPEND);
					}
					
					if($isOk&&$totalrow){
						echo json_encode(array('No'=>200,'Msg'=>implode(',',$msginfo) ));
					}else{
						echo json_encode(array('No'=>500,'Msg'=>implode(',',$msginfo),'query'=>$query));
					}

				} catch(Exception $e) {
					echo json_encode(array('No'=>500,'Msg'=>'Error loading file:'.pathinfo($inputFileName,PATHINFO_BASENAME).': '.$e->getMessage()));
					die();
				}
			}else{
				echo json_encode(array('No'=>400,'Msg'=>'Sorry，The file you uploaded is not in the correct format.'));
			}
			
		}else{
			echo $msg;
		}
	}

}
