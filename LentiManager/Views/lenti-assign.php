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
				<h2><span class="glyphicon glyphicon-wrench glyphicon-blue"></span>&nbsp;LMOPAP</h2>
				<p>Lentivirus management operation permission allocation page</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-8">
					<table class="table table-striped table-hover" id="lenti-assign">
						<thead>
							<tr><th>USERS</th><th>Action</th></tr>
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
		            "sProcessing": "Processing...",
		            "sLengthMenu": "Show _MENU_ entires",
		            "sZeroRecords": "No matching records found.",
		            "sInfo": "Showing _START_ to _END_ of _TOTAL_ entires",
		            "sInfoEmpty": "Showing 0 to 0 of 0 entires",
		            "sInfoFiltered": "(filtered from _MAX_ total entries)",
		            "sSearch": "Search",
		            "sEmptyTable": "No data was found",
		            "sLoadingRecords": "loading...",
		            "sInfoThousands": ",",
		            "oPaginate": {
		                "sPrevious": "Previous",
		                "sNext": "Next"
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
						$(objs).replaceWith("<a class='btn btn-danger btn-xs' onclick='permissions(this,\""+manager+"\");'>Disallow</a>");
						layer.msg(obj.msg, {
                            icon: 1,
                            time: 1000
                        });
					}else{
						$(objs).replaceWith("<a class='btn btn-primary btn-xs' onclick='permissions(this,\""+manager+"\");'>Allow</a>");
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
	die("Sorry, you don't have access to the current page.");
}*/
?>