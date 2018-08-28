<style type="text/css">
	#SBody{ height: 500px;}
	#searchbar-group{width: 250px;  }
	#searchbar input[type='text']{width:200px;height: 20px;}
	#searchbar .input-group-btn button.btn {height: 34px;}
	#searchbar ul li{list-style: none; overflow: hidden; clear: both; margin: 0px 5px; }
	#searchbar .tags-group{padding: 10px; width: 305px;}
	#searchbar .tags-group a{margin: 0px 5px 5px 0px; display: inline-block;}
	div.dropdown-menu {padding: 5px; width: 260px;}
	div.dropdown-menu .pul-right{position: absolute; right: 5px; top: 0px;}
	div.dropdown-menu ul {overflow: hidden; }
	div.dropdown-menu ul li a {overflow:hidden;text-overflow:ellipsis;white-space:nowrap; display: inline-block; width: 100%;}
	div.dropdown-menu ul li a:hover{text-decoration: none; cursor: pointer;}
</style>
<div id="SBody">
	<form action="/product/search3/" id="searchbar">
		<div class="input-group" id="searchbar-group">
			<div class="input-group-btn">
				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="genetype">
					<span class="glyphicon glyphicon-chevron-down"></span>
				</button>
				<div class="dropdown-menu" aria-labelledby="genetype">
					<div class="pul-right"><a href="javascript:;" data-cn="1" class="search_set_tag">clear</a></div>
					<div class="row">
						<?php if($atts['type']=='product'||$atts['type']=='all'){?>
						<div class="<?php echo ($atts['type']=='all')?'col-md-4':'col-md-10';?>">
							<h3>Product Type</h3>
							<ul>
								<?php foreach ($product_type as $key => $value) {?>
										<li>
											<a class="search_set_tag" data-cn="<?php echo $key;?>" title="<?php echo $value;?>" value="p"><?php echo $value;?></a>
										</li>
								<?php }?>
							</ul>
						</div>
						<?php }?>
						<?php if($atts['type']=='format'||$atts['type']=='all'){?>
						<div class="<?php echo ($atts['type']=='all')?'col-md-4':'col-md-10';?>">
							<h3>Format</h3>
							<ul>
								<?php foreach ($format as $key => $value) {?>
										<li>
											<a class="search_set_tag" data-cn="<?php echo $key;?>" title="<?php echo $value;?>" value='f'><?php echo $value;?></a>
										</li>
								<?php }?>
							</ul>
						</div>
						<?php }?>
						<?php if($atts['type']=='species'||$atts['type']=='all'){?>
						<div class="<?php echo ($atts['type']=='all')?'col-md-4':'col-md-10';?>">
							<h3>Species</h3>
							<ul>
								<?php foreach ($species as $key => $value) {?>
										<li>
											<a class="search_set_tag" data-cn="<?php echo $key;?>" title="<?php echo $value;?>" value='sp'><?php echo $value;?></a>
										</li>
								<?php }?>
							</ul>
						</div>
						<?php }?>
					</div>
				</div>
				
			</div>
			<input type="text" id="searchtext" name="s" class="form-control">
			<span class="input-group-btn">
				<button class="btn btn-default" id="searchbarsubmit" type="button">
					<i class="glyphicon glyphicon-search"></i>
				</button>
			</span>
		</div>
		<div class="tags-group" id="tags-group"></div>
		<input type="hidden" name="search_type" value="1">
	</form>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("#searchbar").submit(function() {
			if($("#searchtext").val()==""){
				$("#searchtext").focus();
				return false;
			}
		});
		$("#searchbarsubmit").click(function() {
			$("#searchbar").submit();
		});
		$(".search_set_tag").click(function() {
			
			if($(this).attr('data-cn')!='1'){
				var tag_v = $(this).attr('data-cn');
				var tag_t = $(this).text();
				var tag_tp = $(this).attr('value');
				var obj = $(this);
				var add = 0;
				var labelcs = '';
				(tag_tp=='p')?(labelcs='label-success'):((tag_tp=='f')?(labelcs='label-primary'):(labelcs='label-info'));
				
				$("#searchbar").find('.search_tag').each(function(index,el){
					if($(this).val()==tag_v){
						$(this).remove();
						$(obj).css("color","#337ab7");
						$("#"+tag_v).remove();
						add++;
					}
				});
				if(add==0){
					$(obj).css('color','red');
					console.log($(obj).val());
					$(".tags-group").append('<a class="label '+labelcs+' label-tag" id="'+tag_v+'">'+tag_t+'&nbsp;|&nbsp;<i>&times;</i></a>');
					
					$("#searchbar").append('<input type="hidden" class="search_tag" name="tags[]" value="'+tag_v+'"/>');
				}

			}else{
				$(".search_set_tag").css("color","#262626");
				$(".label-tag").remove();
				$("#searchbar").find(".search_tag").remove();
			}
		});
		$("#tags-group").on('click','a', function() {
			var id = $(this).attr('id');
			$(this).remove();
			$("#searchbar").find(".search_tag").each(function(index, el) {
				if($(this).val()==id){
					$(this).remove();
				}
			});
			$("#searchbar").find(".search_set_tag").each(function(index, el) {
				if($(this).attr('data-cn')==id){
					$(this).css("color","#337ab7");
				}
			});
		});
	});
</script>
