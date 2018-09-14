<?php ?>
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/css/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/css/buttons.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Responsive-2.1.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__)));?>/css/lenti.css">

<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/js/jquery-1.12.3.min.js">
</script>
<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/js/jquery.dataTables.min.js">
</script>
<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/js/dataTables.bootstrap.min.js">
</script>
<!-- Layer -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/layer-v2.3/layer.js">
</script>
<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/js/bootstrap.min.js">
</script>
<!-- Buttons dt -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/dataTables.buttons.min.js">
</script>
<!-- Buttons bt -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/buttons.bootstrap.min.js">
</script>
<!-- jszip -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/jszip.min.js">
</script>
<!-- pdfmake -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/pdfmake.min.js">
</script>
<!-- vfs_fonts -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/vfs_fonts.js">
</script>
<!-- Buttons html5 -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/buttons.html5.min.js">
</script>
<!-- Buttons print -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/buttons.print.min.js">
</script>
<!-- Buttons colVis -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/buttons.colVis.min.js">
</script>
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Responsive-2.1.0/js/dataTables.responsive.min.js">
</script>
<div class="wrap">
	<div class="row">
		<div class="col-md-12">
			<h2><span class="glyphicon glyphicon-trash"></span>&nbsp;<?php _e("Lentivirus Recycled Management","LentiManager"); ?> &nbsp; <a href="javascript:void(0);" onclick="restoreLentilAll();" class="btn btn-primary"><?php _e("Restore","LentiManager"); ?></a></h2> 
		</div>
		<div class="col-md-12">
			<table class="table table-striped table-bordered table-hover"  id="recycled-list">
				<thead>
					<tr>
						<th><input type="checkbox" class="select_all" name="select_all" id="selectAll"></th>
						<th><?php _e("Catalog","LentiManager");?></th>
						<th><?php _e("Type","LentiManager");?></th>
						<th><?php _e("Description","LentiManager");?></th>
						<th><?php _e("Volume","LentiManager");?></th>
						<th><?php _e("Titer","LentiManager");?></th>
						<th><?php _e("Purity","LentiManager");?></th>
						<th><?php _e("Size","LentiManager");?></th>
						<th><?php _e("Vector","LentiManager");?></th>
						<th><?php _e("Delivery Time","LentiManager");?></th>
						<th><?php _e("Price","LentiManager");?></th>
						<th><?php _e("Del Time","LentiManager");?></th>
						<th><?php _e("Action","LentiManager");?></th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
	</div>	
</div>

<!-- restore 模态框 -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="delete-modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="deleteModalLabel">
                    <?php _e("Restore the Lentivirus","LentiManager"); ?>
                </h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <?php _e("Are you sure to restore","LentiManager"); ?>
                <span id="delete_lenti" class="code">
                </span>&nbsp;?
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="restore_button"><?php _e("Restore","LentiManager"); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"> <?php _e("Cancel","LentiManager"); ?> </button>
            </div>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-fade -->
</div>


<script type="text/javascript">
	var table;
	jQuery(document).ready(function($) {
        loadLentil();
        $('[data-toggle="tooltip"]').tooltip();
	    $("#selectAll").on('click',  function(event) {
	    	if (this.checked){
	    		$("input.checkbox_select").prop("checked", true);
	    	}else{
	    		$("input.checkbox_select").prop('checked', false);
	    	}
	    });
	});
	function loadLentil(){
		table = $('#recycled-list').DataTable({
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
	                extend:'excel',
	                exportOptions:{columns: ':visible'}
	            },
	            {   
	                extend: 'colvis',
	                className: 'colvisButton',
	                columns: ':gt(0)'
	            }
        	],
        	"order": [[ 0, 'desc' ]],
        	"destroy": true,//销毁上一个实例
	        "autoWidth": false,//关闭自动列宽
	        "processing": true,//处理中的提示
	        "serverSide": false,//客户端处理
	        "language": {
	            "sProcessing": '<?php _e("Processing...","LentiManager"); ?>',
	            "sLengthMenu": "Show _MENU_ entires",
	            "sZeroRecords": '<?php _e("No matching records found.","LentiManager"); ?>',
	            "sInfo": "Showing _START_ to _END_ of _TOTAL_ entires",
	            "sInfoEmpty": '<?php _e("Showing 0 to 0 of 0 entires","LentiManager"); ?>',
	            "sInfoFiltered": '<?php _e("(filtered from _MAX_ total entries)","LentiManager"); ?>',
	            "sSearch": '<?php _e("Search","LentiManager"); ?>',
	            "sEmptyTable": '<?php _e("No data was found","LentiManager"); ?>',
	            "sLoadingRecords": '<?php _e("loading...","LentiManager"); ?>',
	            "sInfoThousands": ",",
	            "oPaginate": {
	                "sPrevious": '<?php _e("Previous","LentiManager"); ?>',
	                "sNext": '<?php _e("Next","LentiManager"); ?>'
	            }
	        },
	        "ajax": {//发送ajax请求
	            "url": "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/lenti-ajax.php'; ?>",
	            "type": "GET",
	            "data": { action: "listAllBack"},
	        },
	        "columns": [
	        	{
	        		"data" : "catalog",
	        		"width" : "4%"
	        	},
		        { 
		            "data": "catalog",
		            "width": "10%"
		        },
		        {	"data" : "type","width":"8%" },
		        {	"data" : "description",
		        	"render": function ( data, type, full, meta ){
	                    if(data!=null){
	                        return '<div style="overflow: hidden;text-overflow: ellipsis; white-space: nowrap; height: 20px; width:120px; cursor: pointer;" title="'+data+'">'+data+'</div>'; 
	                    }else{
	                        return '';
	                    }
                	},
                	"width": "120"
		    	},
		        {	"data" : "volume", 
		        	"width" : "5%"	},
		        {	"data" : "titer"	},
		        {	"data" : "purity", "width":"5%"	},
		        {	"data" : "size", "width" : "5%"	},
		        {	"data" : "vector"	},
		        {	"data" : "delivery_time", "width" : "10%"	},
		        {	"data" : "price",
		        	"render" : function(data,type,full,meta){
		        		return '<?php _e("$","LentiManager");?>'+data;
		        	},
		        	"width" : "5%"
		        },
		        {	"data":"del_time",
		    	},
		        {
		        	"data": "catalog",
	            //将catalog渲染成两个按钮，点击按钮时将dt单元格的catalog作为参数调用对应的方法
	                "render": function ( data, type, full, meta ){
	                    return '<a data-toggle="modal" data-target="#deleteModal" class="btn btn-success btn-xs delete" onclick="restoreLentil(\''+data+'\')">Restore</a>';
	                },"orderable": false
		        }
	        ],
	        "aoColumnDefs":[
	        	{　　//为每一行数据添加一个checkbox，
	                'targets': 0,
	                'searchable':false,
	                'orderable':false,
	                'className': 'dt-body-center',
	                'render': function (data, type, row){
	                    return '<input class="checkbox_select" type="checkbox" name="catalogs[]" value="' + data + '">';
	                }
            	},
	        ]
        });
	}
	// 批量还原
	function restoreLentilAll(){
		var catalogs = [];
		$("input.checkbox_select:checked").each(function(index, el) {
			catalogs.push($(this).val());
		});
		if(catalogs.length!=0){
			$("#deleteModal").modal();
		}
		console.log(catalogs);
		$("#delete_lenti").html('').append(catalogs.join(','));
		$("#restore_button").unbind('click').click(function(){
			$.ajax({
	            "url": "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/lenti-ajax.php'; ?>",
	            "type": "POST",
	            "data": {action: "restoreAll", catalogs: catalogs},
	            success: function(d) {//d为1或false
	                var obj = JSON.parse(d);
	                if (obj.query != "0") {
	                    //如果后台删除成功，则刷新表格
	                    layer.msg('Successful!', {
	                        icon: 1,
	                        time: 3000
	                    });
	                    $('#deleteModal').modal('hide');
	                    $("input#selectAll").prop("checked", false);
	                    table.ajax.reload( null, false );
	                    
	                } else {
	                    layer.msg('<?php _e("Failed!<br/>Please try again after refresh!","LentiManager"); ?>', {
	                        icon: 2,
	                        time: 3000
	                    });
	                }
	            }
	        });
		});
	}
	// 单个还原
	function restoreLentil(catalog){
		$("#delete_lenti").html('').append(catalog);
		$("#restore_button").unbind('click').click(function(){
			$.ajax({
	            "url": "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/lenti-ajax.php'; ?>",
	            "type": "POST",
	            "data": {action: "restore", catalog: catalog},
	            success: function(d) {//d为1或false
	                var obj = JSON.parse(d);
	                if (obj.query == "1") {
	                    //如果后台删除成功，则刷新表格
	                    layer.msg('Successful!', {
	                        icon: 1,
	                        time: 1000
	                    });
	                    $('#deleteModal').modal('hide');
	                    table.ajax.reload( null, false );
	                    
	                } else {
	                    layer.msg('<?php _e("Failed!<br/>Please try again after refresh!","LentiManager"); ?>', {
	                        icon: 2,
	                        time: 3000
	                    });
	                }
	            }
	        });
		});
	}
	
</script>