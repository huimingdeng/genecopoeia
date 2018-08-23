
jQuery(document).ready(function($){
   
    var a = Math.ceil(Math.random()*2);
    if(a==1){
        data = "action=pio&type=get_url&item="+window.location.href;
    }else{
        data = "action=pioID&type=get_url&item="+postId;
    }
        $.ajax({
            async: false,
            type: "POST",
            dataType: "html",
            // cache:true,
            url: "/wp-content/plugins/pio-related-posts/pio-related-ajax.php",
            data: data,
            success: function(d){//有返回才显示窗口
                var obj = JSON.parse(d);
                if(obj.length!=0){
                    var strHtml='<div id="related_posts" class="pioRelatedUrl">'+"\r\n";
                        strHtml+='     <h2>Customer Who Viewed This Product Also Viewed</h2>'+"\r\n";
                        strHtml+='     <ul id="product-related-url">'+"\r\n";
                    for(var i=0;i<obj.length;i++){
                        strHtml+='<li><a href="'+obj[i]['url']+'" title="'+obj[i]['post_title']+'">'+obj[i]['post_title']+'</a></li>';
                    }
                    strHtml+='  </ul>'+"\r\n";
                    strHtml+='</div>'+"\r\n";
                    $("#container #content").append(strHtml);
                }
            }
        });
    
});
