<?php
//确保在连接客户端时不会超时
set_time_limit(0);

$ip = '127.0.0.1';
// $ip = "192.168.8.69";
// $ip = "192.168.8.12";

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

$count = 0;

$arr = array();
for ($i=0; $i < 10000; $i++) { 
	array_push($arr , $i);
}

do {
	if (($msgsock = socket_accept($sock)) < 0) {
		echo "socket_accept() failed: reason: " . socket_strerror($msgsock) . "\n";
		break;
	} else {
		echo "It is success.\n";
		$buf = socket_read($msgsock,8192);
		// print_r(json_decode($buf , true));
		$talkback = "Receive message:$buf\n";
		echo $talkback;

		//发到客户端
		$msg ="You get:" . array_shift($arr) ."\n";
		socket_write($msgsock, $msg, strlen($msg));


		if(++$count >= 50000){
			break;
		};


	}
//echo $buf;

	socket_close($msgsock);
} while (true);

socket_close($sock);
?>