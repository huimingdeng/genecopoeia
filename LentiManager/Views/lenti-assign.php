<?php 
global $wpdb;
global $user_login;
$allowed_user = get_option('lenti_manager_permission');
$allowed_user_options = json_decode($allowed_user,true);
// if($user_login=='admin'||$user_login=='huimingdeng')
// if($user_login=='admin')
// if(in_array($user_login, $allowed_user_options))
	//{?>
	<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/css/dataTables.bootstrap.css">
	<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__)));?>/css/lenti.css">
	<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/js/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/js/dataTables.bootstrap.min.js"></script>
	<!-- Layer -->
	<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/layer-v2.3/layer.js"></script>
	<div class="wrap">
		<div class="row">
			<div class="col-md-12">
				<h2><span class="glyphicon glyphicon-wrench glyphicon-blue"></span>&nbsp;<?php _e("LMOPAP","LentiManager"); ?></h2>
				<p><?php _e("Lentivirus management operation permission allocation page.","LentiManager"); ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-8">
					<table class="table table-striped table-hover" id="lenti-assign">
						<thead>
							<tr><th><?php _e("USERS","LentiManager");?></th><th><?php _e("Action","LentiManager");?></th></tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var table;
		jQuery(document).ready(function($) {
			loadUsers();
			
		});
		function loadUsers(){
			table = $('#lenti-assign').DataTable({
	            "stateSave": true,
	        	"destroy": true,//销毁上一个实例
		        "autoWidth": false,//关闭自动列宽
		        "processing": true,//处理中的提示
		        "serverSide": false,//客户端处理
		        "language": {
		            "sProcessing": "<?php _e( "Processing", 'LentiManager' ); ?>...",
		            "sLengthMenu": "<?php _e( "Show _MENU_ entires", 'LentiManager' ); ?>",
		            "sZeroRecords": "<?php _e( "No matching records found.", 'LentiManager' ); ?>",
		            "sInfo": "<?php _e( "Showing _START_ to _END_ of _TOTAL_ entires", 'LentiManager' ); ?>",
		            "sInfoEmpty": "<?php _e( "Showing 0 to 0 of 0 entires", 'LentiManager' ); ?>",
		            "sInfoFiltered": "(<?php _e( "filtered from _MAX_ total entries", 'LentiManager' ); ?>)",
		            "sSearch": "<?php _e( "Search", 'LentiManager' ); ?>:",
		            "sEmptyTable": "<?php _e( "No data was found", 'LentiManager' ); ?>",
		            "sLoadingRecords": "<?php _e( "loading", 'LentiManager' ); ?>...",
		            "sInfoThousands": ",",
		            "oPaginate": {
		                "sPrevious": "<?php _e( "Previous", 'LentiManager' ); ?>",
		                "sNext": "<?php _e( "Next", 'LentiManager' ); ?>"
		            }
		        },
		        "ajax": {//发送ajax请求
		            "url": "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/lenti-ajax.php'; ?>",
		            "type": "GET",
		            "data": { action: "listUsers"},
		        },
		        "columns": [
			        { 
			            "data": "users",
			        },
			        {
			        	"data": "action",
			        	"orderable": false
			        }
		        ]
	        });
		}
		function permissions(objs,manager){
			$.ajax({
				type: "GET",
	            dataType: "html",
	            data: {
	                action: "updatePermissions",
	                manager: manager
	                },
				"url":"<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/lenti-ajax.php'; ?>",
				success:function(e){
					var obj = JSON.parse(e);
					console.log(obj.msg);
					console.log(obj.value);
					if(obj.value){//如果存在且值为1，则修改按钮
						$(objs).replaceWith("<a class='btn btn-danger btn-xs' onclick='permissions(this,\""+manager+"\");'><?php _e('Disallow','LentiManager'); ?></a>");
						layer.msg(obj.msg, {
                            icon: 1,
                            time: 1000
                        });
					}else{
						$(objs).replaceWith("<a class='btn btn-primary btn-xs' onclick='permissions(this,\""+manager+"\");'><?php _e('Allow','LentiManager'); ?></a>");
						layer.msg(obj.msg, {
                            icon: 1,
                            time: 1000
                        });
					}
				}
			});
		}
	</script>
<?php 
/*}else{
	die(<?php _e("Sorry, you don't have access to the current page.","LentiManager");?>);
}*/
?>