<?php
/**
* author: NickBai
* createTime: 2016/12/5 0005 上午 9:17
*/
$shm_key = ftok(__FILE__, 't');

/**
开辟一块共享内存

int $key , string $flags , int $mode , int $size
$flags: a:访问只读内存段
c:创建一个新内存段，或者如果该内存段已存在，尝试打开它进行读写
w:可读写的内存段
n:创建一个新内存段，如果该内存段已存在，则会失败
$mode: 八进制格式  0655
$size: 开辟的数据大小 字节

*/

$shm_id = shmop_open($shm_key, "c", 0655, 1024);

/**
* 写入数据 数据必须是字符串格式 , 最后一个指偏移量
* 注意：偏移量必须在指定的范围之内，否则写入不了
*
*/
$size = shmop_write($shm_id, 'hello world', 0);
echo "write into {$size}";

#读取的范围也必须在申请的内存范围之内,否则失败
$data = shmop_read($shm_id, 0, 100);
var_dump($data);

#删除 只是做一个删除标志位，同时不在允许新的进程进程读取，当在没有任何进程读取时系统会自动删除
shmop_delete($shm_id);

#关闭该内存段
shmop_close($shm_id);
?>