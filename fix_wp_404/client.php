<?php
/**
 * 由于运行时间较长，请在CLI模式下运行
 * 用Socket链接server.php，读取post_id。
 * 检查每个post_id对应的post_content中的属于本域名下的链接（a标签的href属性），判断其是否失效（404状态）
 * 删除404状态的链接，使用tidy库整理html文本
 * 保存更改post_content前、后的文本到文件中，用于比对检查
 */
//确保在连接客户端时不会超时
set_time_limit(0);
$time_s = microtime(true);
require_once(dirname(__FILE__).'/functions.php');
require_once(dirname(__FILE__).'/problem_post.php');

$fix_wp_404 = new CHILD_PROGRESS_FIX_WP_404();



// $i=0;
// $sum = count($problem_post_id_list);
// foreach ($problem_post_id_list as $post_id) {
// 	echo $post_id . PHP_EOL;
// 	if($fix_wp_404->get_checked_post($post_id)===false){
// 		$id_content_array = $fix_wp_404->get_post_content_from_post_id($post_id );
// 		$fix_wp_404->check_post_content_404($id_content_array);
// 		$fix_wp_404->save_message_to_file();
// 		$fix_wp_404->set_checked_post($post_id);
// 	}
// 	$fix_wp_404->work_status(++$i , $sum);
// }

// $time_e = microtime(true);
// echo "Time use : " . ($time_e - $time_s) ."\n\n";


$port = 1935;
$ip = "127.0.0.1";

/*
+-------------------------------
*    @socket连接整个过程
+-------------------------------
*    @socket_create
*    @socket_connect
*    @socket_write
*    @socket_read
*    @socket_close
+--------------------------------
*/

	$str[] = str_pad("a", 102 , "a"); 
	$str[] = str_pad("b", 100 , "b"); 
	// $str[] = str_pad("c", 100 , "c"); 
	// $str[] = str_pad("d", 100 , "d"); 



$ts = microtime(true);




// $clone_sokcet = $socket;

$i=0;
while(true){
	// $socket = $clone_sokcet;

	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if ($socket < 0) {
		echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
	}
	$result = socket_connect($socket, $ip, $port);
	if ($result < 0) {
		echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
	}
	var_dump($socket );

// echo $str[$i++%2];

	$send = json_encode(array('key'=>'xxxxx' , 'action'=>'get_post_id' , 'content'=>$str[$i++%2])) ;
	// $send2 = json_encode(array('key'=>'dddddd' , 'action'=>'get_post_id'));

	$write_status = socket_write($socket, $send, strlen($send));
	$write_status = socket_write($socket, "\r\n" , strlen("\r\n"));
	if(!$write_status) {
		echo "socket_write() failed: reason: " . socket_strerror($socket) . "\n";
	}else {
		// sleep(3);
		$receive = '';
		while($tmp = socket_read($socket, 8192)) {
			echo "Receive conetent are:";
			echo  strlen($tmp);
			$receive .= $tmp;
		}

		echo "Ending receive:";
		echo ($receive);




	}

	echo "Close SOCKET...\n";
	socket_close($socket);
	echo "Close OK\n";

	echo "Time used: " . (microtime(true) - $ts);


	// sleep(3);

	// if($receive){
	// 	//do something
	// 	$id_content_array = $fix_wp_404->get_post_content_from_post_id($post_id );
	// 	$fix_wp_404->check_post_content_404($id_content_array);
	// 	$fix_wp_404->save_message_to_file();
	// 	$url_status_code = $fix_wp_404->get_all_url_status_code();


	// 	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	// 	if ($socket < 0) {
	// 		echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
	// 	}
	// 	$result = socket_connect($socket, $ip, $port);
	// 	if ($result < 0) {
	// 		echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
	// 	}


	// 	$send = json_encode(array('key'=>'xxxxx' , 'action'=>'set_url_status' , 'url_status_code'=>$url_status_code));
	// 	// $send2 = json_encode(array('key'=>'dddddd' , 'action'=>'get_post_id'));

	// 	$receive = '';

	// 	if(!socket_write($socket, $send, strlen($send))) {
	// 		echo "socket_write() failed: reason: " . socket_strerror($socket) . "\n";
	// 	}else {
	// 		while($tmp = socket_read($socket, 8192)) {
	// 			echo "Receive conetent are:";
	// 			echo  strlen($tmp);
	// 			$receive .= $tmp;
	// 		}

	// 		echo "Ending receive:";
	// 		echo strlen($receive);




	// 	}
	// 	if($receive){
	// 		/////////////
	// 	}else{
	// 		/////////////
	// 	}

	// 	echo "Close SOCKET...\n";
	// 	socket_close($socket);
	// 	echo "Close OK\n";
	// }else{
	// 	break;
	// }



	// if($i++ > 10) break;
	// usleep(100000000);
	
}
	// if(!socket_write($socket, $send, strlen($send))) {
	// 	echo "socket_write() failed: reason: " . socket_strerror($socket) . "\n";
	// }else {
	// 	echo "Send to Server message success!\n";
	// 	echo "Send content are:<font color='red'>$send</font> <br>";
	// }

	// while($out = socket_read($socket, 8192)) {
	// 	echo "Receive Server message success!\n";
	// 	echo "Receive conetent are:",$out;
	// }

echo "Close SOCKET...\n";
socket_close($socket);
echo "Close OK\n";


$te = microtime(true);
echo "time use:" . ($te - $ts) . PHP_EOL;
?>