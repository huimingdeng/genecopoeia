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
			<h2><span class="glyphicon glyphicon-file"></span>&nbsp;Lentivirus Files Management &nbsp; 
			</h2>
		</div>
		<div class="col-md-12">
			<table class="table table-striped table-bordered table-hover"  id="files-list">
				<thead>
					<tr>
						<th>Filename</th>
						<th>Size</th>
						<th>Readable</th>
						<th>Writable</th>
						<th>Executable</th>
						<th>Creation Time</th>
						<th>Modification Time</th>
						<th>Access Time</th>
						<th>Action</th>
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
                    Look the Excel <span id="look_excel" class="code"></span>
                </h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <table class="table table-hover table-striped" id="excel-list">
                	<thead>
                		<tr>
                			<th>catalog</th>
	                		<th>type</th>
	                		<th>description</th>
	                		<th>volume</th>
	                		<th>titer</th>
	                		<th>titer_description</th>
	                		<th>purity</th>
	                		<th>size</th>
	                		<th>vector</th>
	                		<th>delivery_format</th>
	                		<th>delivery_time</th>
	                		<th>symbol_link</th>
	                		<th>pdf_link</th>
	                		<th>price</th>
	                		<th>priority</th>
                		</tr>
                	</thead>
                	<tbody>
                		
                	</tbody>
                </table>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"> Close </button>
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
                    Delete the Excel 
                </h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                Are you sure to delete
                <span id="delete_excel" class="code">
                </span>&nbsp;?
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="delete_button">Delete</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"> Cancel </button>
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
	                text:'Reload',
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
	                    layer.msg('Successful!', {
	                        icon: 1,
	                        time: 3000
	                    });
	                    $('#deleteModal').modal('hide');
	                    table.ajax.reload( null, false );
	                    
	                } else {
	                    layer.msg('Failed!<br/>Please try again after refresh!', {
	                        icon: 2,
	                        time: 3000
	                    });
	                }
	            }
	        });
		});
	}
</script>