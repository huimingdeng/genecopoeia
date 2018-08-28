<?php
/**
 * 批量转换wordpress的链接
 * 从?p=xxx形式转成现有的固定连接形式
 */
require_once(dirname(__FILE__).'/../wp-blog-header.php');
if(!is_user_logged_in()){
	header("location:/wp-login.php?redirect_to=".urlencode($_SERVER['PHP_SELF']));
	exit();
}
header('HTTP/1.1 200 OK');

if($_REQUEST['urls']){
	$urls = explode("\n" , $_REQUEST['urls']);
	$new_urls = array();
	foreach ( $urls as $url) {
		if(preg_match("~\?p=(\d+)\s*$~" , $url , $matches)){
			$post_id = $matches[1];
			$new_urls[] = array(
				'url' => $url,
				'new_url' => get_permalink($post_id),
			);
		}else{
			$new_urls[] = array(
				'url' => $url,
				'new_url' => $url,
			);
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Post id to Permalink</title>
	<style type="text/css">
	.compact{
		border-collapse: collapse;
		border: 1px solid #e5e5e5;
	}
	.compact td{
		padding: 2px 4px;
	}
</style>
</head>
<body>
	<a href="./">工具首页</a><br><br>
	<p>This tool is used to Transfer normal link(/?p=xxx) to permalink</p>
	<form action="" method="POST">
		<textarea name="urls" id="" cols="200" rows="20" placeholder="URLs here. Format : ......./?p=xxx"></textarea> <br>
		<input type="submit" name="Transfer" value="Transfer">		
	</form>

	<?php
	if(!empty($new_urls)){
		$html = '<table border="1" class="compact" width="70%">
		<tr>
		<th>Posted URL</th>
		<th>Transfered URL</th>
		</tr>
		';
		foreach ($new_urls as $key => $value) {
			$html .= '
			<tr>
			<td>'. $value['url'].'</td>
			<td>'. $value['new_url'].'</td>
			</tr>';
		}
		$html .= '</table>';
		echo $html;
	}
	?>
</body>
</html>
