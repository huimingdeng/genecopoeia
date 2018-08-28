<?php
/*
* 本页是导航页
*/
/*
本目录下包含的关键脚本文件信息如下：
config.php 是配置文件
functions.php 脚本使用的函数库
simple_html_dom.php 将html转换成dom，类似jquery的操作的一个库
problem_post.php 脚本会检查包含在这个文件中的post id值的内容

以下脚本，由于运行时间较长，需在命令行模式下运行：
verify.php : 使用此脚本前，请先备份wp_post表到wp_posts_copy，再运行下面的脚本。此脚本会检查这两个数据表之间的差异。
check_404.php : 检查链接是否404状态，并保存预更改前后的内容到check文件夹下的html文件中，用于检查
fix_404.php : 修改含404链接的文件内容，默认删除404链接，然后更新内容

*/
require_once(dirname(__FILE__).'/../wp-blog-header.php');
if(!is_user_logged_in()){
	header("location:/wp-login.php?redirect_to=".urlencode($_SERVER['PHP_SELF']));
	exit();
}
header('HTTP/1.1 200 OK');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>工具导航页</title>
</head>
<body>
	<a href="check_status_code.php">检测链接状态</a><br>
	<a href="mutil_transfer_permalink_to_postid.php">批量转换permalink到postid</a><br>
	<a href="mutil_transfer_postid_to_permalink.php">批量转换postid到permalink</a><br>
</body>
</html>