<?php ?>
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/css/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/css/buttons.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__)));?>/css/lenti.css">
<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/js/jquery-1.12.3.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/js/dataTables.bootstrap.min.js"></script>
<!-- Layer -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/layer-v2.3/layer.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/js/bootstrap.min.js"></script>

<!-- Buttons dt -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/dataTables.buttons.min.js"></script>
<!-- Buttons bt -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/buttons.bootstrap.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/buttons.colVis.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Responsive-2.1.0/js/dataTables.responsive.min.js"></script>
<div class="wrap">
	<div class="row">
		<div class="col-md-12">
			<h2><span class="glyphicon glyphicon-file"></span>&nbsp;<?php _e("Logs Management","LentiManager"); ?> &nbsp; 
			</h2>
		</div>
		<div class="col-md-12">
			<table class="table table-striped table-bordered table-hover"  id="files-list">
				<thead>
					<tr>
						<th><?php _e("Filename","LentiManager");?></th>
						<th><?php _e("Size","LentiManager");?></th>
						<th><?php _e("Readable","LentiManager");?></th>
						<th><?php _e("Writable","LentiManager");?></th>
						<th><?php _e("Executable","LentiManager");?></th>
						<th><?php _e("Creation Time","LentiManager");?></th>
						<th><?php _e("Modification Time","LentiManager");?></th>
						<th><?php _e("Access Time","LentiManager");?></th>
						<th><?php _e("Action","LentiManager");?></th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal fade" id="lookModal" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="logs-modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="lookModalLabel">
                    <?php _e("Look the Log","LentiManager"); ?> <span id="look_log" class="code"></span>
                </h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <div class="logs" >
                	<div class="logs-body" id="log-list"></div>
                </div>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"> <?php _e("Close","LentiManager"); ?> </button>
            </div>
		</div>
	</div>
</div>


<script type="text/javascript">
	var table;
	jQuery(document).ready(function($) {
        loadFiles();
        $('[data-toggle="tooltip"]').tooltip();
	});
	function loadFiles(){
		table = $('#files-list').DataTable({
            // responsive: true,
            "dom": '<"container-fluid"<"row"<"col-md-2"l><"col-md-6"B><"col-md-2"f>>rt<"rol-md-2"><"col-md-2"i>p>',
            "stateSave": true,
            "buttons": [
	            {
	                text:'<?php _e("Reload","LentiManager"); ?>',
	                action: function( e, dt, node, config ) {
	                   table.ajax.reload();
	                }
	            },
	            {   
	                extend: 'colvis',
	                className: 'colvisButton',
	                columns: ':gt(0)'
	            }
        	],
        	"order": [[ 0, 'asc' ]],
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
	            "data": { action: "listLogs"},
	        },
	        "columns": [
		        { 
		            "data": "Filename", 
		        },
		        { 
		            "data": "Size", 
		            "width":"5%"
		        },
		        { 
		            "data": "Readable",
		            "width": "8%",
		            "orderable": false
		        },
		        { 
		            "data": "Writable",
		            "width": "8%",
		            "orderable": false
		        },
		        { 
		            "data": "Executable",
		            "width": "8%",
		            "orderable": false
		        },
		        { 
		            "data": "created"
		        },
		        { 
		            "data": "modification"
		        },
		        { 
		            "data": "access"
		        },
		        { 
		            "data": "Filename",
		            "render":function(data, type, full, meta){
		            	return "<a class=\"btn btn-default btn-sm\" data-toggle=\"modal\" data-target=\"#lookModal\" onclick=\"lookLog('"+data+"')\"><span class=\"glyphicon glyphicon-eye-open glyphicon-blue\" ></span></a>" + "&nbsp;";
		            },
		            "width":"8%",
		            "orderable": false
		        },
	        ]
        });
	}
	function lookLog(path){
		$("input").val('');
		table.ajax.reload( null, false );
		$("#look_log").html('').append(path);
        $("#log-list").html('');
        $.ajax({//发送ajax请求
            url: "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/lenti-ajax.php'; ?>",
            type: "GET",
            dataType:'json',
            data: { action: "lookLog",filename:path},
            success:function(data){

            	$("#log-list").html(data.data);

            }
        });      
	}
</script>