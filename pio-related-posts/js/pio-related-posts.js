
jQuery(document).ready(function($){
    // $(window).scroll(function(){
    
        $.ajax({
            async: false,
            type: "POST",
            dataType: "html",
            // cache:true,
            url: "/wp-content/plugins/pio-related-posts/pio-related-ajax.php",
            data: "action=pio&type=get_url&item="+window.location.href,
            success: function(d){//有返回才显示窗口
                var obj = JSON.parse(d);
                console.log('test-pio');
                if(obj.length!=0){
                    var strHtml='<div id="related_posts" class="pioRelatedUrl">'+"\r\n";//style="display:none"
                        strHtml+='     <h2>Customer Who Viewed This Product Also Viewed</h2>'+"\r\n";
                        strHtml+='     <ul id="product-related-url">'+"\r\n";
                    /*for (var key in obj){
                        console.log(key+"test");
                        if(key){
                            strHtml+='<li><a href="'+obj[key]['url']+'?utm_source=genecopoeia.com&utm_medium=display&utm_campaign=also_viewed" title="'+obj[key]['post_title']+'">'+obj[key]['post_title']+'</a></li>';
                        }
                    }*/
                    for(var i=0;i<obj.length;i++){
                        strHtml+='<li><a href="'+obj[i]['url']+'?utm_source=genecopoeia.com&utm_medium=display&utm_campaign=also_viewed" title="'+obj[i]['post_title']+'">'+obj[i]['post_title']+'</a></li>';
                    }
                    strHtml+='  </ul>'+"\r\n";
                    strHtml+='</div>'+"\r\n";
                    $("#container #content").append(strHtml);
                    // $("#related_posts").show();
                }
            }
        });
    // });
});
