<?php
//引入该核心文件才能使用wpdb类
require_once(dirname(__FILE__)."/../../../wp-config.php");
// wp中直接引入核心文件会提示404，需要此语句来强制标记提示200才能成功引入
header('HTTP/1.1 200 OK');

global $wpdb;
$action = $_REQUEST['action'];
$per_num = $_REQUEST['per_num'];//表单值
$display_num = $_REQUEST['display_num'];//表单值
$type 	= $_REQUEST['type'];//获取AJAX提交的type
$item = $_REQUEST['item'];//获取AJAX提交的Items

if($action=="pio") {
	if($type=="get_url"){
		if(isset($item)){
			$arr = getRelatedURLBySingleItem($item);
			if($arr){
				$Output = getRelatedURLByAllItem($arr);
				echo json_encode($Output);
			}
		}else{
			echo json_encode(array());
		}
	}
}elseif("pioID"==$action){
	if($type=="get_url"){
		if(isset($item)){
			$postid = trim($item);
			$Output = getRelatePostsByPostId($postid);
			echo json_encode($Output);
		}else{
			echo json_encode(array());
		}
	}
}elseif("pioCombined"==$action){
	if($type=="get_url"){
		if(isset($item)){
			$postid = trim($item['postid']);
			// $url = $item['url'];
			$urls = explode('?',$item['url']);
			$url = $urls[0];
			
			$Output = getCombinedUrl($postid,$url);
			// echo json_encode($item);
			echo json_encode($Output);
		}else{
			echo json_encode(array());
		}
	}
}

// 根据item的url获取别名
function getRelatedURLBySingleItem($item,$num=''){
	if(!$num){
		$num = get_option('pio_related_product_per_num',4);
	}
	// $uri = "http://localhost:8000/queries.json";
	$uri = "http://192.168.8.4:8003/queries.json";
	if(preg_match("/genecopoeia.com/",$_SERVER['SERVER_NAME'])) {
		$uri = "http://othello.genecopoeia.com:8003/queries.json";
	}
	$header = "Content-Type: application/json";
	if(preg_match("/".$_SERVER['HTTP_HOST']."/",$item)){
		$item = preg_replace("/^[https:\/\/]+|^[http:\/\/]+|".$_SERVER['HTTP_HOST']."/", '', $item);
	}
	$data = '{ "item": "'. $item .'", "itembias": '. $num .',"num": '.$num.' }';//itembias:系统
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $uri );
	curl_setopt ( $ch, CURLOPT_POST, 1 );
	curl_setopt ( $ch, CURLOPT_HEADER, $header );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
	$return = curl_exec ( $ch );
	curl_close ( $ch );
	$output = json_decode($return,true);
	$output = $output["itemScores"];//提取item和score
	return $output;
}

// 或取当前产品的相关产品推荐
function getRelatedURLByAllItem($AllArr){
	// global $wpdb;
	$i = 0;
	$DisplayArr = array();
	$relateUrl = array();
	$display_num = get_option('pio_related_product_display_num',4);
	foreach ($AllArr as $value) {
		$item = $value['item'];
		$score = $value['score'];
		if($score>0){
			$DisplayArr[$item] = $score;
		}
	}
	array_unique($DisplayArr);//去掉重复值
	
	$DisplayArr = array_keys($DisplayArr);//只输出item
	rsort($DisplayArr);//排序
	if(!empty($DisplayArr)){
		foreach ($DisplayArr as $value) {
			$postid=url_to_postid($value);
			if($postid){
				$post=get_post($postid,'ARRAY_A');
				$tmp['post_title']=$post['post_title'];
				$tmp['url']=$value.'?utm_source=genecopoeia.com&utm_medium=display&utm_campaign=also_viewed';
				if(!empty($tmp['post_title']))
					$relateUrl[]=$tmp;
			}
		}
	}
	return $relateUrl;
}


function getRelatePostsByPostId($postid){
	global $wpdb;
	if(!$num){
		$num = get_option('pio_related_product_per_num',4);
	}
	$sql = sprintf("SELECT * from wp_posts_similarity WHERE post_id = %s ORDER BY similarity DESC limit %s",$postid,$display_num = get_option('pio_related_product_display_num',4));
	$results = $wpdb->get_results($sql,ARRAY_A);
	$showPost = array();
	$relateUrl = array();
	foreach($results AS $row){
		if(count($relateUrl)<$num){
			$post=get_post($row['sim_post_id'],'ARRAY_A');
			$tmp['post_title']=$post['post_title'];
			$tmp['url']='/?p='.$post['ID'].'&utm_source=genecopoeia.com&utm_medium=display&utm_campaign=also_viewed';
			if(!empty($tmp['post_title']))
				$relateUrl[]=$tmp;
		}
	}

	return $relateUrl;
}

// 混合旧版的pio和新版的相似度
function getCombinedUrl($postid,$url){
	$arr = getRelatedURLBySingleItem($url);
	if($arr){
		$Output1 = getRelatedURLByAllItem($arr);
	}
	$Output2 = getRelatePostsByPostId($postid);
	// print_r($Output2);
	$relateUrl = array();
	if(!empty($Output1)&&!empty($Output2)){
		$combined = array_merge($Output1,$Output2);
		foreach ($combined as $k => $v) {
			$relateUrl[$v['post_title']] = $v;
		}
		sort($relateUrl);
	}else{
		$relateUrl = $Output2;
	}
	return $relateUrl;
}