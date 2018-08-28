<?php
/**
 * 批量检查链接的状态
 */
require_once(dirname(__FILE__).'/../wp-blog-header.php');
if(!is_user_logged_in()){
	header("location:/wp-login.php?redirect_to=".urlencode($_SERVER['PHP_SELF']));
	exit();
}
require_once(dirname(__FILE__).'/functions.php');
header('HTTP/1.1 200 OK');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Check status code</title>
	<script type="text/javascript" src="./jquery.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$("form").submit(function(event) {
				event.preventDefault();
				index=1;
				var urls = $("#urls").val().toString().split("\n");
				//remove old result
				$("#result").find('tr:gt(0)').remove();
				check_status(urls , index , wait=300);
			});

			function check_status(urls , index , wait){
				if(urls.length<=0) return;
				cu = $.trim(urls.shift());
				if(cu){
					$.ajax({
						url: 'check_status_code_do.php',
						type: 'GET',
						dataType: 'json',
						data: {
							"check_url": cu,
							"index":index
						},
						beforeSend:function(i){
							$("#result").append('<tr id="r' + index + '"><td>'+ cu +'</td><td class="rs">Checking...</td></tr>');
						}
					})
					.done(function(msg) {
						if(msg.status=="ok"){
							if(msg.status_code==404){
								$("#r"+msg.index).find(".rs").html('<span class="error">' + msg.status_code + '</span>');
							}else{
								$("#r"+msg.index).find(".rs").html(msg.status_code);
							}
						}else{
							$("#r"+msg.index).find(".rs").html('<span class="warning">' + msg.info + '</span>');
						}
					})
					.fail(function() {
						$("#r"+index).find(".rs").text('');
					});

				}
				window.setTimeout(function(){
					check_status(urls , ++index , wait)
				} , wait );
			}
		});

	</script>
	<style type="text/css">
		.compact{
			border-collapse: collapse;
			border: 1px solid #e5e5e5;
		}
		.compact td{
			padding: 2px 4px;
		}
		.error{
			color:#d43f3a;
		}
		.warning{
			color:#eea236;
		}
	</style>
</head>
<body>
	<a href="./">工具首页</a><br><br>
	<p>This tool is used to check url status code</p>
	<form action="" method="POST">
		<textarea name="urls" id="urls" cols="200" rows="20" placeholder="URLs here. Format : ......./?p=xxx"></textarea> <br>
		<input type="submit" name="Check" value="Check">		
	</form>

<br>
	<table border="1" class="compact" width="70%" id="result">
		<tr>
			<th>Posted URL</th>
			<th>Status Code</th>
		</tr>
	</table>
</body>
</html>
