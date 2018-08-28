<?php 
require_once(dirname(__FILE__)."/../../../wp-config.php");
include_once(ABSPATH . WPINC . '/class-IXR.php');
global $wpdb;
$action = $_REQUEST['action'];
if($action=='push'){
	$postId = $_POST['postId'];
	// $postId=142;//116;
		
	// Test 
	$server = get_host();
	if($server['authenticated']){
		$post_tab = $table_prefix."posts";
		$sql = "SELECT * FROM $post_tab where ID=$postId";
		$post=$wpdb->get_row($sql,ARRAY_A);
		$seo_tab = $table_prefix."postmeta";
		$seo = "SELECT meta_key,meta_value FROM $seo_tab WHERE post_id = $postId AND (meta_key LIKE '_aioseop_%')";
		$custom_fields = $wpdb->get_results($seo,ARRAY_A);
		$host = addslashes(site_url().'/');
		//设置远程站点信息
		$target = $server['host'];
		if ( '/' != substr( $target, -1 ) ) {
			$target .= '/';
		}
		$username = $server['user'];
		$password = $server['pass'];
		$result=sync_post($post,$username,$password,$target,$host,$custom_fields);
	}else{
		$result=array('status'=>500,'msg'=>$server['api']);
	}
	echo json_encode($result);
	
}else{
	echo json_encode(array('status'=>404,'msg'=>'No operation.'));
}

// 替换源站点附件
function replace_host($string,$target,$host){
	if('' == $target || '' == $host) return false;
	// 判断连接是否为图片，PDF附件等
	$allowedExts=array('jpeg','jpg','png','gif','txt','pdf');
	$pattern = "(http://[a-zA-Z0-9\./]+)";
	preg_match_all($pattern, $string, $urls);
	//如果源站点的附件等才替换，其他的不替换
	if(!empty($urls)&&!empty($urls[0])){
		foreach ($urls[0] as $v) {
			$ext = strtolower(pathinfo($v,PATHINFO_EXTENSION));
			if(in_array($ext, $allowedExts)){
				$tmp=preg_replace('('.$host.')',$target,$v);
				// echo $tmp."\r\n";
				$string=str_replace($v,$tmp,$string);//替换掉附件的url域名，非附件不替换
				// echo $v."\r\n";
				// $string = $tmp;
			}
		}
	}
	// echo $string;
	return $string;
	//测试
	// return preg_replace('('.$host.')',$target,$string);
}
/**
 * 远程同步文章(A->B)
 * @param  array  $post         需要同步的文章信息
 * @param  string $user         同步站点的用户（登录用）
 * @param  string $pass         同步站点的用户的登陆密码
 * @param  string $target       目标站点域名（同步站点）
 * @param  string $host         来源站点域名（本站点）
 * @param  array  $custom_fields 需要同步的自定义字段
 * @return array               返回消息记录（成功200失败400）
 */
function sync_post($post,$user,$pass,$target,$host,$custom_fields){
	
	$xmlrpcurl=$target.'xmlrpc.php';
	$blogid=get_current_blog_id();
	$GLOBALS['xmlrpc_internalencoding'] = 'UTF-8';
	$client = new IXR_Client($xmlrpcurl);
	//先获取文章主体内容，用于上传内容中的附件
	$file=$post['post_content'];
	// $post['post_content']=replace_host($post['post_content'],$target,$host);//替换成目标站点url
	$post['guid']='';
	if(!empty($custom_fields)){//如果用户自定义非空，则添加到$post中
		$cs_fields=array();
		foreach ($custom_fields as $k => $v) {
			$tmp_fields['key']=$v['meta_key'];
			$tmp_fields['value']=$v['meta_value'];
			// $tmp_fields=array($v['meta_key'],$v['meta_value']);
			$cs_fields[]=$tmp_fields;
			
		}
		$post['custom_fields']=$cs_fields;
		
		// $post['custom_fields']=array(array('key' => '_aioseop_title', 'value' => '还是测试'));
	}
	// 新的文章则进行新增，否则修改 
	$target_id = get_option("target_postid_".$post['ID']);
	if(!$target_id){
		
		$client->query("wp.newPost",$blogid,$user,$pass,$post);
		$response=$client->getResponse();
		// 成功后设置
		if ($response['faultCode']==0){
			if( is_a( $client->message, '\IXR_Message' ) ){
				// 需要设置 target_postid_{the_ID} => 返回的post_id
				if(!$client->message->message->params[0]){//如果无法获取post_id,则根据返回的xml获取
					if(preg_match('/<string>(\d+)<\/string>/', $client->message->message,$rs)){
						//如果正则匹配成功
						if(!empty($rs)&&!empty($rs[1])){
							update_option("target_postid_".$post['ID'],$rs[1]);
							/*if(!empty($custom_fields)){//同步SEO元素
								foreach ($custom_fields as $k => $v) {
									
								}
							}*/
						}
					}
				}else{
					update_option("target_postid_".$post['ID'],$client->message->message->params[0]);
				}
			}
			$msg = "The article uploaded successfully.";
			$result=update_media($file,$target,$host,$user,$pass);
			$is_true=200;  	
		} else {
			$is_true=400;//文章同步失败
		   	$msg = 'Fail:' . $response['faultString'];
		}
	}else{//echo "yes";
		$post['ID'] = intval($target_id);//换成目标网站的文章id
		$post['post_modified'] = $post['post_modified_gmt'] = date("Y-m-d H:i:s");
		// 需要修改的字段
		$content = array(
			'post_status' => $post['post_status'],//文章状态
			'post_title' => $post['post_title'],//标题
			'post_excerpt' => $post['post_excerpt'],//摘要
			'post_content' => $post['post_content'],//正文
			'post_name' => $post['post_name'],
			'post_password' => $post['post_password'],
			'post_thumbnail' => intval($post['post_thumbnail']),
			// 'custom_fields' => $post['custom_fields'],
		);
		$client->query("wp.editPost",$blogid,$user,$pass,intval($target_id),$content);
		$response=$client->getResponse();
		if($response['faultCode']==0){
			$msg = "The article uploaded successfully.";
			$result=update_media($file,$target,$host,$user,$pass);
			$is_true=200;//  
			/*if(!empty($custom_fields)){//同步SEO元素
				foreach ($custom_fields as $k => $v) {
					
				}
			}*/	
		} else {
			$is_true=400;//文章同步失败
		   	$msg = 'Fail:' . $response['faultString'];
		}
	}	//	exit;
	return array("status"=>$is_true,"msg"=>$msg);
	// return $is_true;
}
// 获取目标站点的域名
function get_host(){
	$option = get_option('wp2wp_server_option');//获取设置中保存的配置项
	return $option;
}


// 附件处理,只能上传到目标站点的/wp-content/uploads/{Year}/{Month} {Year},{Month}为当前的年月
// 返回值中 status 的值大于1则成功
function update_media($postContent,$target,$host,$user,$pass){
	$is_true=0;
	$xmlrpcurl=$target.'xmlrpc.php';
	$client = new IXR_Client($xmlrpcurl);
	// Determine whether the connection is a picture, PDF attachment, etc
	$allowedExts=array('jpeg','jpg','png','gif','txt','pdf');
	// reference：http://www.jb51.net/article/23511.htm
	$mimetype=array(
		'jpg'=>'image/jpeg',
		'gif'=>'image/gif',
		'png'=>'image/png',
		'bmp'=>'image/bmp',
		'pdf'=>'application/pdf',
		'txt'=>'text/plain'
		);
	// Matching gets the url.
	$pattern = "(http://[a-zA-Z0-9\./]+)";
	preg_match_all($pattern, $postContent, $urls);
	$msg = "";
	// print_r($urls);
	// Gets the file suffix name for the url. pathinfo($fileInfo['name'],PATHINFO_EXTENSION)
	if(!empty($urls)&&!empty($urls[0])){
		foreach ($urls[0] as $v) {
			$ext = strtolower(pathinfo($v,PATHINFO_EXTENSION));
			if(in_array($ext, $allowedExts)){
				$tmp=preg_replace('('.$host.')','',$v);
				// $attach[]=$tmp;
				$arr=explode('/',$v);
				$filename=end($arr);
				// echo $filename."\r\n";
				//替换目录，得到附件
				$path = str_replace($filename,'',$tmp);
				// echo $path."\r\n";
				$upload_file = $tmp;
				// echo $upload_file."\r\n";
				$attachment = array(
					'name' => $filename,//pathinfo($filename,PATHINFO_FILENAME),
					'type' => $mimetype[$ext],
					'bits' => new IXR_Base64( file_get_contents( ABSPATH.$path.$filename )),
					'overwrite' => true
					);
				$client->query('wp.uploadFile','',$user,$pass,$attachment);
				// $clinet->query('metaWeblog.newMediaObject','',$user,$pass,$attachment);
				$response=$client->getResponse();
				// print_r($client);
				if($response['faultCode']==0){
					$is_true +=1;
					$msg .= $filename." upload success. \r\n";
				}else{
					$is_true -= 1;//不成功则减一
					$msg .= $filename." upload failured... \r\n";
				}
			}
		}
	}
	// print_r($attachment);
	// echo $msg;
	return array("status"=>$is_true,"msg"=>$msg);
}
