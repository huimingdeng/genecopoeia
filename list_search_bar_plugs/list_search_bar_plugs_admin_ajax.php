<?php 
include '../../../wp-config.php';
global $wpdb;

ob_start();?>
<div class="row">
	<div class="col-md-12">
		<div class="col-md-10" id="opertion"><a href="javascript:void(0);" id="AddList" class="btn btn-primary">Add</a></div>
		<div class="col-md-2 "><a href="<?php echo plugins_url('',__FILE__);?>/help%20manual.docx" class="glyphicon glyphicon-question-sign glyphicon-size-24 fR" title="Down manual here" style="color: rgb(255, 0, 0);"></a></div>
	</div>
	<div class="col-md-12">
		<div class="panel-body SeachNotice" title="Feedback information"></div>
	</div>
</div>
<div class="row">
	<div class="col-md-12 " id="panelshow">
		
		<div class="col-md-8">
			<form id="searchBar">
				
			</form>
			<div class="col-md-12">
				<div class="panel">
				<div class="panel-body">
					<p><b><span class="glyphicon glyphicon-info-sign" style="color:blue;"></span>&nbsp;`Format`和`Species`表格的数据如果全部勾选则SearchBar查询的筛选方式为`Product Type`+`Format`+`Species`</b></p>
					<p><b>eg.&nbsp; miRNA + [Clone + `Lentiviral Particle` + `AAV Particle`(Format)] + [Human + Mouse + Rat(Species)],如右图所示</b></p>
				</div>
			</div>
			</div>
		</div>
			
		<div class="col-md-4">
			<div class="panel">
				<div class="panel-body">
					<p><img src="<?php echo plugins_url('',__FILE__);?>/images/example_01.png" alt="eg.&nbsp; miRNA + [Clone + `Lentiviral Particle` + `AAV Particle`(Format)] + [Human + Mouse + Rat(Species)]"></p>
				</div>
			</div>
		</div>
		
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$.ajax({//检测当前文章是否设置searchBar
			url:"<?php echo plugins_url('',__FILE__);?>/list_search_bar_plugs_service.php",
			type:"POST",
			dataType:'json',
			data:'action=select'+'&postid='+$('#post_ID').val(),
			beforeSend:function(){
				$(".SeachNotice").html("Loading...");
			},
			success: function(msg){
				if(msg.status){
					$("#opertion").append(msg.notice);
					$(".SeachNotice").html(msg.html);
				}else{
					$(".SeachNotice").html('');
				}
			}
		});
		$("#opertion").on('click','#AddList',function(event) {
			$("#panelshow").show();
			$.ajax({
				url: "<?php echo plugins_url('',__FILE__);?>/list_search_bar_plugs_service.php",
				type: 'POST',
				dataType: 'json',
				data: {'action': 'add','postid':$('#post_ID').val()},
				beforeSend:function(){
					$("#searchBar").html("Loading...");
				},
				success:function(msg){
					if(msg.status){
						$("#searchBar").html(msg.html);
					}
					
				}
			});
		});
		$("#opertion").on('click','a.UpdateSeach', function(event) {
			$("#panelshow").show();
			$.ajax({
				url: "<?php echo plugins_url('',__FILE__);?>/list_search_bar_plugs_service.php",
				type: 'POST',
				dataType: 'json',
				data: {'action': 'update','list_search_bar_classify':$(this).attr('value')},
				beforeSend:function(){
					$("#searchBar").html("Loading...");
				},
				success:function(msg){
					if(msg.status){
						$("#searchBar").html(msg.html);
					}
				}
			});
			
		});
		$("#searchBar").on('click', '.close', function(event) {
			$("#panelshow").hide();
			$("#searchBar").html('');
		});
		$("#searchBar").on('click','.deleteSearch',function(){
			$.ajax({
				url: "<?php echo plugins_url('',__FILE__);?>/list_search_bar_plugs_service.php",
				type: 'POST',
				dataType: 'json',
				data: {'action': 'delete', 'list_search_bar_classify':$("#searchBar input[name=list_search_bar_classify]").val()},
				success:function(msg){
					if(msg.status){
						$("#panelshow").hide();
						$("#searchBar").html('');
						$(".SeachNotice").html(msg.notice);
						$("#"+msg.delbtn).remove();
						// $("#opertion").html('<a href="javascript:void(0);" id="AddList" class="btn btn-primary">Add</a>');
					}
				}
			});
		});
		$("#searchBar").submit(function() {
			var action = "<?php echo plugins_url('',__FILE__);?>/list_search_bar_plugs_service.php";
			$.ajax({
				url:action,
				type:"POST",
				dataType:'json',
				data:$("#searchBar").serialize()+'&postid='+$('#post_ID').val(),
				beforeSend:function(){
					$(".SeachNotice").html("Loading...");
				},
				success: function(msg){
					if(msg.status){
						if(msg.btn!='')
							$("#opertion").append(msg.btn);
						$(".SeachNotice").html(msg.notice);
						$(".SeachNotice").append(msg.html);
						$("#panelshow").hide();
					}else{
						$(".SeachNotice").html('error');
					}
				}
			});
			return false;
		});
	});
</script>
<?php 
$html = ob_get_contents();
ob_end_clean();
echo $html;