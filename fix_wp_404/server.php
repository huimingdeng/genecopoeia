<?php
/**
 * 由于运行时间较长，请在CLI模式下运行
 * 从problem_post.php文件加载需要检查的post_id。（如果没有，则从wp_post中取出全部的post_id）
 * 监听端口，并提供服务
 * 提供要检查的posi_id
 * 提供/更新已检查的链接状态信息
 */
//确保在连接客户端时不会超时
set_time_limit(0);
$time_s = microtime(true);
require_once(dirname(__FILE__).'/functions.php');
require_once(dirname(__FILE__).'/problem_post.php');

$fix_wp_404 = new CHILD_PROGRESS_FIX_WP_404();
// $fix_wp_404 = new FIX_WP_404();
//create some paths
$fix_wp_404->mk_check_path();


if(!isset($problem_post_id_list) || count($problem_post_id_list)==0){
	$problem_post_id_list = array();
	foreach ($fix_wp_404->get_post_content('all', 'all' ,array('ID') , false) as $value) {
		array_push($problem_post_id_list , $value['ID']);
	};

}


function auth($key){
	return true;
}


$ip = '127.0.0.1';
$port = 1935;

/*
+-------------------------------
*    @socket通信整个过程
+-------------------------------
*    @socket_create
*    @socket_bind
*    @socket_listen
*    @socket_accept
*    @socket_read
*    @socket_write
*    @socket_close
+--------------------------------
*/

/*----------------    以下操作都是手册上的    -------------------*/
if(($sock = socket_create(AF_INET,SOCK_STREAM,SOL_TCP)) < 0) {
	echo "socket_create() Fail reason is:".socket_strerror($sock)."\n";
}

if(($ret = socket_bind($sock,$ip,$port)) < 0) {
	echo "socket_bind() Fail reason is:".socket_strerror($ret)."\n";
}

if(($ret = socket_listen($sock,4)) < 0) {
	echo "socket_listen() Fail reason is:".socket_strerror($ret)."\n";
}
// socket_set_nonblock($sock);

// $count = 0;

$arr = array();
for ($i=0; $i < 1000000; $i++) { 
	array_push($arr , $i);
}
echo "Ready.".PHP_EOL;

do {
	if(count($problem_post_id_list)==0) break;

	if (($msgsock = socket_accept($sock)) < 0) {
		echo "socket_accept() failed: reason: " . socket_strerror($msgsock) . "\n";
		// socket_close($msgsock);

		break;
	} else {
		// // echo "It is success.\n";
		// // $buf = socket_read($msgsock,8192);
		// $buf = '';
		// echo "Receive conetent are:";
		// while($tmp = socket_read($msgsock, 30)) {
		// 	// echo "Receive Server message success!\n";
		// 	echo  ($tmp);
		// 	echo  strlen($tmp);
		// 	$buf .= $tmp;
		// }
		// // print_r(json_decode($buf , true));

		// // file_put_contents('tmp.txt', $buf , FILE_APPEND);

		// // //do something....
		// // $message_decode = json_decode($buf , true);

		// // if(auth($message_decode['key'])){
		// // 	if($message_decode['action']=='get_post_id'){
		// // 		// $talkback = array_shift($problem_post_id_list);
		// // 		$talkback = json_encode( array('post_id'=>array_shift($problem_post_id_list) , 'url_status_code'=>$fix_wp_404->get_all_url_status_code()) );
		// // 	}elseif($message_decode['action']=='get_url_status'){

		// // 	}elseif($message_decode['action']=='set_url_status'){

		// // 	}


		// // }else{
		// // 	$talkback = "auth fail";
		// // }
		// $talkback ='ok'."\n";

		// // $talkback = "Receive message:$buf\n";
		// // echo $talkback;


		// //send back to client
		// //发到客户端
		// // $msg ="You get:" . array_shift($arr) ."\n";
		// socket_write($msgsock, $talkback, strlen($talkback));


		// // if(++$count >= 50000){
		// // 	break;
		// // };

		echo "It is success.\n";
		// $buf = socket_read($msgsock,100000);//8192
		$buf='';
		$ts = microtime(true);
		while( $tmp = socket_read($msgsock, 50000 )  ) {
			echo "Receive conetent are:";
			echo  strlen($tmp) . ':';
			echo  ($tmp);
			echo "".PHP_EOL;
			$buf .= $tmp;
			echo (microtime(true) - $ts);
			echo "".PHP_EOL;
			if( strrpos($tmp , "\r\n")  !== false){
				break;
			}
		}
		// print_r(json_decode($buf , true));
		$talkback = "Receive message:$buf\n";
		echo $talkback;

		//发到客户端
		$msg ="You get:" . array_shift($arr) ."\n";
		socket_write($msgsock, $msg, strlen($msg));


		if(++$count >= 1000000){
			break;
		};


	}
		socket_close($msgsock);
//echo $buf;

} while (true);

socket_close($sock);

$time_e = microtime(true);
echo "Time use : " . ($time_e - $time_s) ."\n\n";