<?php
error_reporting(E_ALL);
set_time_limit(0);
echo "<h2>TCP/IP Connection</h2>\n";

$port = 1935;
$ip = "127.0.0.1";
// $ip = "192.168.8.12";
// $ip = "192.168.8.69";


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




$ts = microtime(true);

$i=0;
while($i++ < 10000){

	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if ($socket < 0) {
		echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
	}else {
		echo "OK.\n";
	}

	// socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 1, "usec" => 0));  //发送套接流的最大超时时间为6秒

	echo "Try to connect: '$ip' port: '$port'...\n";
	$result = socket_connect($socket, $ip, $port);
	if ($result < 0) {
		echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
	}else {
		echo "Connect OK\n";
	}

	$in = "Ho\r\n";
	$in .= "first blood\r\n";

	$in = json_encode(array("abc"=>array("aaa"=>"bbb") , "i"=>$i));

	$out = '';

	if(!socket_write($socket, $in, strlen($in))) {
		echo "socket_write() failed: reason: " . socket_strerror($socket) . "\n";
	}else {
		echo "Send to Server message success!\n";
		echo "Send content are:<font color='red'>$in</font> <br>";
		while($out = socket_read($socket, 8192)) {
			echo "Receive Server message success!\n";
			echo "Receive conetent are:",$out;
		}
	}
	// // sleep(3);
	// if(!socket_write($socket, $in, strlen($in))) {
	// 	echo "socket_write() failed: reason: " . socket_strerror($socket) . "\n";
	// }else {
	// 	echo "Send to Server message success!\n";
	// 	echo "Send content are:<font color='red'>$in</font> <br>";
	// 	while($out = socket_read($socket, 8192)) {
	// 		echo "Receive Server message success!\n";
	// 		echo "Receive conetent are:",$out;
	// 	}
	// }

	echo "Close SOCKET...\n";
	socket_close($socket);
	echo "Close OK\n";

	// usleep(1000000);
	
}
	// if(!socket_write($socket, $in, strlen($in))) {
	// 	echo "socket_write() failed: reason: " . socket_strerror($socket) . "\n";
	// }else {
	// 	echo "Send to Server message success!\n";
	// 	echo "Send content are:<font color='red'>$in</font> <br>";
	// }

	// while($out = socket_read($socket, 8192)) {
	// 	echo "Receive Server message success!\n";
	// 	echo "Receive conetent are:",$out;
	// }




$te = microtime(true);
echo "time use:" . ($te - $ts) . PHP_EOL;
?>