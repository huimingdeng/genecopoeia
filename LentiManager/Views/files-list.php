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
			<h2><span class="glyphicon glyphicon-file"></span>&nbsp;<?php _e("Lentivirus Files Management","LentiManager"); ?> &nbsp; 
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
	<div class="modal-dialog" id="look-modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="lookModalLabel">
                    <?php _e("Look the Excel","LentiManager"); ?> <span id="look_excel" class="code"></span>
                </h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <table class="table table-hover table-striped" id="excel-list">
                	<thead>
                		<tr>
                			<th><?php _e("catalog","LentiManager");?></th>
	                		<th><?php _e("type","LentiManager");?></th>
	                		<th><?php _e("description","LentiManager");?></th>
	                		<th><?php _e("volume","LentiManager");?></th>
	                		<th><?php _e("titer","LentiManager");?></th>
	                		<th><?php _e("titer_description","LentiManager");?></th>
	                		<th><?php _e("purity","LentiManager");?></th>
	                		<th><?php _e("size","LentiManager");?></th>
	                		<th><?php _e("vector","LentiManager");?></th>
	                		<th><?php _e("delivery_format","LentiManager");?></th>
	                		<th><?php _e("delivery_time","LentiManager");?></th>
	                		<th><?php _e("symbol_link","LentiManager");?></th>
	                		<th><?php _e("pdf_link","LentiManager");?></th>
	                		<th><?php _e("price","LentiManager");?></th>
	                		<th><?php _e("priority","LentiManager");?></th>
                		</tr>
                	</thead>
                	<tbody>
                		
                	</tbody>
                </table>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"> <?php _e("Close","LentiManager"); ?> </button>
            </div>
		</div>
	</div>
</div>
<!-- delete模态框 -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="delete-modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="deleteModalLabel">
                    <?php _e("Delete the Excel","LentiManager"); ?> 
                </h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <?php _e("Are you sure to delete","LentiManager"); ?>
                <span id="delete_excel" class="code">
                </span>&nbsp;?
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="delete_button"><?php _e("Delete","LentiManager"); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"> <?php _e("Cancel","LentiManager"); ?> </button>
            </div>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-fade -->
</div>
<script type="text/javascript">
	var table,table2;
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
	            "data": { action: "listFiles"},
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
		            	return "<a class=\"btn btn-default btn-sm\" data-toggle=\"modal\" data-target=\"#lookModal\" onclick=\"lookExcel('"+data+"')\"><span class=\"glyphicon glyphicon-eye-open glyphicon-blue\" ></span></a>" + "&nbsp;" + "<a class=\"btn btn-default btn-sm\" data-toggle=\"modal\" data-target=\"#deleteModal\" onclick=\"delExcel('"+ data +"')\"><span class=\"glyphicon glyphicon-trash glyphicon-red\"></span></a>";
		            },
		            "width":"8%",
		            "orderable": false
		        },
	        ]
        });
	}
	function lookExcel(path){
		$("input").val('');
		table.ajax.reload( null, false );
		$("#look_excel").html('').append(path);
        table2 = $('#excel-list').DataTable({
        	"dom": '<"container-fluid"<"row"<"col-md-2"l><"col-md-6"B><"col-md-2"f>>rt<"rol-md-2"><"col-md-2"i>p>',
        	"stateSave": true,
        	"buttons": [
	            {
	                text:'Reload',
	                action: function( e, dt, node, config ) {
	                   table2.ajax.reload();
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
	            "data": { action: "lookExcel",filename:path},
	        },
	        "columns": [
		        { 
		        	"data": "catalog",
		        	"render" : function(data, type, full, meta){
		        		if(data!=null){
		        			// eg. array data['a']
	                        return '<pre><div data-toggle="tooltip" data-placement="top" style="overflow: hidden;text-overflow: ellipsis; white-space: nowrap; height: 20px; cursor: pointer;" title="'+data+'">'+data+'</div></pre>';
	                        // return '<pre>'+data+'</pre>';
	                    }else{
	                        return '';
	                    }
		        	}, },
		        { "data": "type","width":"5%" },
		        { 
		        	"data": "description",
		        	"render" : function(data, type, full, meta){
		        		if(data!=null){
	                        return '<pre><div data-toggle="tooltip" data-placement="top" style="overflow: hidden;text-overflow: ellipsis; white-space: nowrap; height: 20px; cursor: pointer;" title="'+data+'">'+data+'</div></pre>';
	                        // return '<pre>'+data+'</pre>';
	                    }else{
	                        return '';
	                    }
		        	},
		        	"width":"100"
		        },
		        { "data": "volume" },
		        { 
		        	"data": "titer",
		        	"render" : function(data, type, full, meta){
		        		if(data!=null){
	                        // return '<pre>'+data+'</pre>';
	                        return '<pre><div data-toggle="tooltip" data-placement="top" style="overflow: hidden;text-overflow: ellipsis; white-space: nowrap; height: 20px; cursor: pointer;" title="'+data+'">'+data+'</div></pre>';
	                    }else{
	                        return '';
	                    }
		        	},

		        },
		        { 
		        	"data": "titer_description",
		        	"render" : function(data, type, full, meta){
		        		if(data!=null){
	                        // return '<pre>'+data+'</pre>';
	                        return '<pre><div data-toggle="tooltip" data-placement="top" style="overflow: hidden;text-overflow: ellipsis; white-space: nowrap; height: 20px; cursor: pointer;" title="'+data+'">'+data+'</div></pre>';
	                    }else{
	                        return '';
	                    }
		        	},
		        },
		        { "data": "purity" },
		        { "data": "size" },
		        { "data": "vector" },
		        { 
		        	"data": "delivery_format",
		        	"render" : function(data, type, full, meta){
		        		if(data!=null){
	                        return '<input type="text" class="form-control" data-toggle="tooltip" data-placement="top" title="'+data+'" value="'+data+'">';
	                        // return '<pre><div data-toggle="tooltip" data-placement="top" style="overflow: hidden;text-overflow: ellipsis; white-space: nowrap; height: 20px; cursor: pointer;" title="'+data+'">'+data+'</div></pre>';
	                    }else{
	                        return '';
	                    }
		        	},},
		        { "data": "delivery_time" },
		        { "data": "symbol_link",
		        	"render" : function(data, type, full, meta){
		        		if(data!=null){
	                        return "<input data-toggle=\"tooltip\" data-placement=\"top\" type=\"text\" class=\"form-control\" title=\""+data+"\" value=\""+data+"\">";
	                    }else{
	                        return '';
	                    }
		        	},
		        	"width":"100"
		         },
		        { "data": "pdf_link",
		        	"render" : function(data, type, full, meta){
		        		if(data!=null){
	                        return "<input type=\"text\" class=\"form-control\" value=\""+data+"\">";
	                    }else{
	                        return '';
	                    }
		        	},
		        	"width":"100"
		        },
		        { "data": "price" },
		        { "data": "priority" },
	        ]
        });
	        
	}
	function delExcel(path){
		$("#delete_excel").html('').append(path);
		$("#delete_button").unbind('click').click(function(){
			$.ajax({
	            "url": "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/lenti-ajax.php'; ?>",
	            "type": "POST",
	            "data": {action: "delExcel", filename: path},
	            success: function(d) {//d为1或false
	                var obj = JSON.parse(d);
	                console.log(obj);
	                if (obj.query == "1") {
	                    //如果后台删除成功，则刷新表格
	                    layer.msg('<?php _e("Successful","LentiManager"); ?>!', {
	                        icon: 1,
	                        time: 3000
	                    });
	                    $('#deleteModal').modal('hide');
	                    table.ajax.reload( null, false );
	                    
	                } else {
	                    layer.msg('<?php _e("Failed!<br/>Please try again after refresh","LentiManager"); ?>!', {
	                        icon: 2,
	                        time: 3000
	                    });
	                }
	            }
	        });
		});
	}
</script>