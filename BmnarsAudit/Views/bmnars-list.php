<?php  ?>
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/css/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/css/buttons.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Responsive-2.1.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/css/bmnars.css">

<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/js/jquery-1.12.3.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/layer-v2.3/layer.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/js/bootstrap.min.js"></script>
<!-- Buttons dt -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/dataTables.buttons.min.js"></script>
<!-- Buttons bt -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/buttons.bootstrap.min.js"></script>
<!-- jszip -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/jszip.min.js"></script>
<!-- pdfmake -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/pdfmake.min.js"></script>
<!-- vfs_fonts -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/vfs_fonts.js"></script>
<!-- Buttons html5 -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/buttons.html5.min.js"></script>
<!-- Buttons print -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/buttons.print.min.js"></script>
<!-- Buttons colVis -->
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/js/buttons.colVis.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Responsive-2.1.0/js/dataTables.responsive.min.js"></script>

<div class="wrap">
	<div class="row">
		<div class="col-md-12">
			<h2><span class="glyphicon glyphicon-th-list"></span>&nbsp;Bmnars Data Audit &nbsp;  &nbsp;  &nbsp;  
			</h2> 
			<p class="text-danger">P.S.<b>审核通过后，请到<a href="/wp-admin/edit.php">文章</a>中编辑文章，选择分类目录后保存为草稿才可以在“生命科学”中预览。编辑完成后发布则可以让用户查看。</b></p>
		</div>
		<div class="col-md-12">
			<table class="table table-striped table-bordered table-hover"  id="bmnars-list">
				<thead>
					<tr>
						<th>Title</th>
						<th>Author</th>
						<th>Source</th>
						<th>Content(text)</th>
						<th>Source Url</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
	</div>	
</div>

<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="preview-modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="previewModalLabel">
                    <span>预览</span><span id="preview_title" class="code"></span>
                </h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <div id="previewbody"></div>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="preview_button">通过</button>
                <button type="button" class="btn btn-danger" id="disallowed_button">禁止</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"> 取消 </button>
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
        loadBmnars();
        $('[data-toggle="tooltip"]').tooltip();
	});
	function loadBmnars(){
		table = $('#bmnars-list').DataTable({
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
	                extend:'excel',
	                exportOptions:{columns: ':visible'}
	            },
	            {
	                extend:'pdf',
	                exportOptions:{columns: ':visible'}
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
	            "url": "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/bmnars-ajax.php'; ?>",
	            "type": "GET",
	            "data": { action: "listAll"},
	        },
	        "columns": [
		        { 
		            "data": "title",
		            "width": "15%"
		        },
		        {	"data" : "author" },
		        {	"data" : "source" },
		    	{	"data" : "content_text",
		        	"render": function ( data, type, full, meta ){
	                    if(data!=null){
	                        return '<div style="overflow: hidden; width:300px; height:80px; overflow-y:auto;">'+data+'</div>'; 
	                    }else{
	                        return '';
	                    }
                	},
                	"width":"15%"
		    	},
		        {	"data" : "source_url",
		        	"render": function ( data, type, full, meta ){
	                    if(data!=null){
	                        return '<div style="width:250px;"><a href="'+data+'" target="_blank">'+data+'</a></div>'; 
	                    }else{
	                        return '';
	                    }
                	},
                	"width":"10%"
		    	},
		        {
		        	"data": "id",
	            //将catalog渲染成两个按钮，点击按钮时将dt单元格的catalog作为参数调用对应的方法
	                "render": function ( data, type, full, meta ){
	                    return '<a class="btn btn-primary btn-xs edit" onclick="allowed(\''+data+'\',this)" title="预览"><span class="glyphicon glyphicon-eye-open"></span></a>'
	                    +'&nbsp'
	                    +'&nbsp'+
	                    '<a title="不通过" class="btn btn-danger btn-xs delete" onclick="disallowed(\''+data+'\')"><span class="glyphicon glyphicon-remove"></span></a>';
	                },"orderable": false,"width":"15%"
		        }
	        ]
        });
	}

	function allowed(postid,object){
		$.ajax({
			"url": "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/bmnars-ajax.php';?>",
			"type": 'GET',
			"dataType": 'json',
			"data": {action:"previewdraft",id: postid},
		})
		.done(function(json) {
			$("#previewModal").modal().find('#preview_title').text(json.title);
			$("#previewbody").html(json.html);
		})
		.fail(function() {
			
		})
		.always(function() {
			
		});
		$("#preview_button").unbind('click').click(function(){
			$.ajax({
				url: "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/bmnars-ajax.php';?>",
				type: 'GET',
				dataType: 'json',
				data: {action:"savedraft",id: postid},
				success:function(msg){
					if(msg.status==200){
						layer.msg('OK! 同步到WordPress草稿箱，请查看!', {
			                icon: 1,
			                time: 3000
			            });
			            table.ajax.reload( null, false );
			            $("#previewModal").modal('hide');
					}else{
						layer.msg('抱歉，无法同步到WordPress草稿箱!', {
			                icon: 2,
			                time: 3000
			            });
					}

				}
			});
		});
		$("#disallowed_button").unbind('click').click(function() {
			$.ajax({
				"url": "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/bmnars-ajax.php';?>",
				"type": 'GET',
				"dataType": 'json',
				"data": {action:"disallowed",id: postid},
				success:function(msg){
					if(msg.status==200){
						layer.msg('审核不通过!', {
			                icon: 1,
			                time: 3000
			            });
			            table.ajax.reload( null, false );
			            $("#previewModal").modal('hide');
					}else{
						layer.msg('抱歉，操作失败!', {
			                icon: 2,
			                time: 3000
			            });
					}
				}
			});
		});
	}
	function disallowed(postid){
		$.ajax({
			"url": "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/bmnars-ajax.php';?>",
			"type": 'GET',
			"dataType": 'json',
			"data": {action:"disallowed",id: postid},
		})
		.done(function(msg) {
			if(msg.status==200){
				layer.msg('审核不通过!', {
	                icon: 1,
	                time: 3000
	            });
	            table.ajax.reload( null, false );
			}else{
				layer.msg('抱歉，操作失败!', {
	                icon: 2,
	                time: 3000
	            });
			}
		})
		.fail(function() {
			
		})
		.always(function() {
			
		});
	}
</script>

