
function wp2wppush(postId){
	var ajaxurl = $('#ajaxurl').val();
	var data = {postId:postId,action:'push'};
	$.ajax({
		url:ajaxurl,
		data:data,
		type:'POST',
		dataType:'json',
		beforeSend:function(){
			$("#sync-content").html("<span class='sync-loading'>Loding....</span>");
		},
		success:function(msg){
			if(msg.status==200){
			// if(msg==200){
				// alert(msg.status);
				$("#sync-content").html("<span class='sync-success'>当前文章同步成功!</span>").css({"margin-bottom":"10px","border":"1px dashed gray"});
			}else{
				var html = "<span class='sync-error'>同步失败!</span>";
				html+="<div class='error-msg'>"+msg.msg+"</div>";
				$("#sync-content").html(html).css("margin-bottom","10px");
			}
		},
		error:function(msg) {
			html="<span>Sorry,unknown error.";
			$("#sync-content").html(html);
		}
	})
	// alert(postId);
}

jQuery(document).ready(function($) {
	
});