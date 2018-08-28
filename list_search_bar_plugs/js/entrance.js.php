<?php
require_once(dirname(__FILE__)."/../../../../wp-blog-header.php");
header('HTTP/1.1 200 OK');
$list_search_bar_plus_service_url = plugins_url('',__FILE__).'/../list_search_bar_plugs_admin_ajax.php';
echo <<<EOA
jQuery(document).ready(function(\$){
	\$.ajax({
		type: "GET",
		//dataType: "html",
		url: "{$list_search_bar_plus_service_url}",
		data: "action=init&postID={$_GET['post']}",
		beforeSend: function(){\$("#postexcerpt").after('<div id="list_search_bar" class="postbox "><div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>List Search Bar</span></h3><div class="inside">Loading...</div></div>');},
		success: function(msg){
			$("#list_search_bar .inside").html('').append(msg);
		}
	});
});
EOA;
?>
