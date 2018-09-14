<?php ?>
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/css/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Buttons-1.1.2/css/buttons.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/lib/Responsive-2.1.0/css/responsive.bootstrap.min.css">
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
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL .'/'. dirname(dirname(plugin_basename(__FILE__))); ?>/js/jquery.form.js"></script>
<div class="wrap">
	<div class="row">
		<div class="col-md-12">
			<h2><span class="glyphicon glyphicon-th-list"></span>&nbsp;<?php _e("Lentivirus Management","LentiManager"); ?> &nbsp; <a href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/help%20manual.docx'; ?>" data-toggle="tooltip" data-placement="top" title="<?php _e("Instructions document.","LentiManager"); ?>"><span class="glyphicon glyphicon-question-sign"></span></a> &nbsp; &nbsp; &nbsp; <a href="javascript:void(0);" onclick="addLentil()" data-toggle="modal" data-target="#addModal" class="btn btn-primary"><?php _e("Add","LentiManager"); ?></a> &nbsp; <a href="javascript:void(0);" onclick="uploadLentiviurs();" class="btn btn-success"><?php _e("uploads","LentiManager"); ?></a> &nbsp; <a href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/template.xlsx'; ?>" data-toggle="tooltip" data-placement="right" title="<?php _e("For bulk import data, refer to the template.","LentiManager"); ?>" id="template" class="btn btn-warning"><?php _e("template","LentiManager"); ?></a> 
			</h2> 
		</div>
		<div class="col-md-12">
			<table class="table table-striped table-bordered table-hover"  id="lenti-list">
				<thead>
					<tr>
						<th><?php _e("Catalog","LentiManager"); ?></th>
						<th><?php _e("Type","LentiManager");?></th>
						<th><?php _e("Description","LentiManager");?></th>
						<th><?php _e("Volume","LentiManager");?></th>
						<th><?php _e("Titer","LentiManager");?></th>
						<th><?php _e("Purity","LentiManager");?></th>
						<th><?php _e("Size","LentiManager");?></th>
						<th><?php _e("Vector","LentiManager");?></th>
						<th><?php _e("Delivery Time","LentiManager"); ?></th>
						<th><?php _e("Price","LentiManager");?></th>
						<th><?php _e("Action","LentiManager");?></th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
	</div>	

	<!-- add 添加模态框  start -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="add-modal-dialog">
        <div class="modal-content">
            <form id="form_add" class="form-inline" >
	            <div class="modal-header">
	                <div style="display: inline-block;">
	                    <h4 class="modal-title" id="addModalLabel" style="display: inline;margin-right: 20px;">
	                    </h4>
	                </div>
	                <div style="display: inline-block;float: right;margin-right:10px;">
	                    <button type="button" class="btn btn-primary addLentil" id="modal_save_button"><?php _e("Save","LentiManager"); ?></button>
	                    <button type="button" class="btn btn-default close-edit-modal" data-dismiss="modal"><?php _e("Cancel","LentiManager"); ?></button>
	                </div>
	            </div>
            	<!-- modal-header -->
                <div class="modal-body">
                	<div class="form-group" style="width:49.5%">
                        <label style="font-size:13px;" for="catalog"><?php _e("Catalog","LentiManager"); ?>&nbsp;<span id="catalog_log" class="valid_log"></span></label>
                        <input type="text" class="form-control" id="catalog" name="catalog" placeholder="" onBlur="checkCatalog()" data-toggle="tooltip" data-placement="top" title="<?php _e("Required. Can only contain letters, numbers, dashes.","LentiManager"); ?>"/>
                    </div>
                    <!-- Titer -->
                    <div class="form-group" style="width:49.5%">
                    	<label for="titer"><?php _e("Titer","LentiManager"); ?>&nbsp;<span id="titer_log" class="valid_log"></span></label>
                    	<input type="text" class="form-control" id="titer" name="titer" data-toggle="tooltip" data-placement="top" title="<?php _e("Required.","LentiManager"); ?>" onBlur="checkIsNotNull(this);">
                    </div>
                    <!-- Type -->
                    <div class="form-group " style="width:24.5%">
                    	<label for="type"><?php _e("Type","LentiManager"); ?>&nbsp;<span id="type_log" class="valid_log"></span></label>
                    	<input type="text" class="form-control" id="type" name="type" data-toggle="tooltip" data-placement="top" title="<?php _e("Required.","LentiManager"); ?>" onBlur="checkIsNotNull(this);">
                    </div> 
                	<!-- Volume -->
                    <div class="form-group " style="width:24.5%">
                    	<label for="volume"><?php _e("Volume","LentiManager"); ?>&nbsp;<span id="volume_log" class="valid_log"></span></label>
                    	<input type="text" class="form-control" id="volume" name="volume" data-toggle="tooltip" data-placement="top" onBlur="checkIsNotNull(this);" title="<?php _e("Required.","LentiManager"); ?>">
                    </div>
                    <!-- Price -->
					<div class="form-group" style="width:24.5%">
						<label for="price"><?php _e("Price","LentiManager"); ?>&nbsp;<span id="price_log" class="valid_log"></span></label>
                        <div class="input-group">
                            <div class="input-group-addon"><?php _e("$","LentiManager"); ?></div>
                            <input type="text" class="form-control" id="price" name="price" onBlur="checkPrice()" data-toggle="tooltip" data-placement="top" title="<?php _e("Required. Need to be a positive integer.","LentiManager"); ?>"/>
                            <div class="input-group-addon">.00</div>
                        </div>
					</div>
					<!-- Purity -->
					<div class="form-group" style="width:24.5%">
						<label for="purity"><?php _e("Purity","LentiManager"); ?>&nbsp;<span id="purity_log" class="valid_log"></span></label>
						<input type="text" id="purity" name="purity" class="form-control" data-toggle="tooltip" data-placement="top" onBlur="checkIsNotNull(this);" title="<?php _e("Required.","LentiManager"); ?>">
					</div>
					<!-- Size -->
					<div class="form-group" style="width:24.5%">
						<label for="size"><?php _e("Size","LentiManager"); ?>&nbsp;<span id="size_log" class="valid_log"></span></label>
						<input type="text" id="size" name="size" data-toggle="tooltip" data-placement="top" title="Required. Need to be a positive integer." onBlur="checkisInteger(this);" class="form-control">
					</div>
					<!-- Vector -->
					<div class="form-group" style="width:24.5%">
						<label for="vector"><?php _e("Vector","LentiManager"); ?>&nbsp;<span id="vector_log" class="valid_log"></span></label><input type="text" id="vector" name="vector" data-toggle="tooltip" data-placement="top" title="<?php _e("Required. Vector Name.","LentiManager"); ?>" onBlur="checkIsNotNull(this);" class="form-control">
					</div>
                    <!-- Delivery Time -->
                    <div class="form-group" style="width:24.5%">
						<label for="delivery_time"><?php _e("Delivery Time","LentiManager"); ?>&nbsp;<span id="delivery_time_log" class="valid_log"></span></label>
						<input type="text" id="delivery_time" name="delivery_time" data-toggle="tooltip" data-placement="top" title="<?php _e("Required.","LentiManager"); ?>" onBlur="checkIsNotNull(this);" class="form-control">
					</div>
					<!-- Priority -->
					<div class="form-group" style="width:24.5%">
						<label for="priority"><?php _e("Priority","LentiManager"); ?>&nbsp;<span id="priority_log" class="valid_log"></span></label>
						<input type="text" id="priority" data-toggle="tooltip" data-placement="top" title="<?php _e("Optional.","LentiManager"); ?>" class="form-control">
					</div>
                    <!-- PDF Link -->
                    <div class="form-group" style="width:100%">
						<label for="pdf_link"><?php _e("PDF Link","LentiManager"); ?>&nbsp;<span id="pdf_link_log" class="valid_log"></span></label>
						<input type="text" id="pdf_link" name="pdf_link" placeholder=" Attachment link eg. /wp-content/uploads/oldpdfs/tech/omicslink/pEZ-AV02.pdf" data-toggle="tooltip" data-placement="top" title="<?php _e("Optional.","LentiManager"); ?>" class="form-control">
					</div>
                    <!-- Description -->
                    <div class="form-group " style="width:100%">
                    	<label for="description"><?php _e("Description","LentiManager"); ?>&nbsp;<span id="description_log" class="valid_log"></span>
                    	</label>
                    	<span class="character_entity_tip"><?php _e("Note: Please use <code class='form_code_1'>&amp;trade;</code> instead of <code class='form_code_2'>&trade;</code> and use <code class='form_code_1'>&amp;micro;</code> instead of <code class='form_code_2'>&micro;</code>, refer to the <a href='http://www.w3cschool.cn/htmltags/ref-entities.html' target='_blank'>Manual</a>.","LentiManager"); ?></span>
                    	<textarea class="form-control" id="description" name="description" data-toggle="tooltip" data-placement="top" onBlur="checkIsNotNull(this);" title="<?php _e("Required.","LentiManager"); ?>"></textarea> 
                    </div>
                    <!-- Titer Description -->
                    <div class="form-group" style="width:100%">
                    	<label for="titer_description"><?php _e("Titer Description","LentiManager"); ?>&nbsp;<span id="titer_description_log" class="valid_log"></span></label>
                    	<span class="character_entity_tip"><?php _e("Note: Please use <code class='form_code_1'>&amp;trade;</code> instead of <code class='form_code_2'>&trade;</code> and use <code class='form_code_1'>&amp;micro;</code> instead of <code class='form_code_2'>&micro;</code>, refer to the <a href='http://www.w3cschool.cn/htmltags/ref-entities.html' target='_blank'>Manual</a>.", "LentiManager"); ?></span>
                    	<textarea class="form-control" id="titer_description" name="titer_description" data-toggle="tooltip" data-placement="top" onBlur="checkIsNotNull(this);" title="<?php _e("Required.","LentiManager"); ?>"></textarea>
                    </div>
					<!-- Delivery Format -->
					<div class="form-group" style="width:100%">
						<label for="delivery_format"><?php _e("Delivery Format","LentiManager"); ?>&nbsp;<span id="delivery_format_log" class="valid_log"></span></label>
						<span class="character_entity_tip"><?php _e("Note: Please use <code class='form_code_1'>&amp;trade;</code> instead of <code class='form_code_2'>&trade;</code> and use <code class='form_code_1'>&amp;micro;</code> instead of <code class='form_code_2'>&micro;</code>, refer to the <a href='http://www.w3cschool.cn/htmltags/ref-entities.html' target='_blank'>Manual</a>.","LentiManager"); ?></span>
						<textarea id="delivery_format" name="delivery_format" data-toggle="tooltip" data-placement="top" title="<?php _e("Required.","LentiManager"); ?>" onBlur="checkIsNotNull(this);" class="form-control"></textarea>
					</div>
					<!-- Symbol Link -->
					<div class="form-group" style="width:100%">
						<label for="symbol_link"><?php _e("Symbol Link","LentiManager"); ?>&nbsp;<span id="symbol_link_log" class="valid_log"></span></label>
						<input type="text" id="symbol_link" name="symbol_link" data-toggle="tooltip" data-placement="top" title="<?php _e("Optional.","LentiManager"); ?>" class="form-control">
					</div>
                </div>
                <!-- modal-body -->
                <input id="catalog_ori" name="catalog_ori" type="hidden" />
            </form>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal -->
</div>
<!-- add 添加模态框  end -->

<!-- delete模态框 -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="delete-modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="deleteModalLabel">
                    <?php _e("Delete the Lentivirus","LentiManager"); ?>
                </h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <?php _e("Are you sure to delete","LentiManager"); ?>
                <span id="delete_lenti" class="code">
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

<!-- upload模态框 -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="moda-dialog" id="upload-modal-dialog">
		<div class="modal-content">
			<form id="uploadFiles" enctype="multipart/form-data" method="post" class="form-inline">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
	                    &times;
	                </button>
	                <h4 class="modal-title" id="deleteModalLabel">
	                    <?php _e("Uploads Lentivirus Data In Batches","LentiManager"); ?>
	                </h4>
	            </div>
				<div class="modal-body">
					<div class="form-group">
                        <label for="Files"><?php _e("File input","LentiManager"); ?></label>
                        <input id="Files" name="Files" type="file">
                        <input type="hidden" name="action" value="uploads">
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" id="upload_button" class="btn btn-primary"><?php _e("Save","LentiManager"); ?></button>
					<button type="button" class="btn btn-default" data-dismiss="modal"> <?php _e("Cancel","LentiManager"); ?> </button>
				</div>
			</form>
		</div>
	</div>
</div>
</div>




<script type="text/javascript">
	var table;
	jQuery(document).ready(function($) {
        loadLentil();
        $('[data-toggle="tooltip"]').tooltip();
	});
	function loadLentil(){
		table = $('#lenti-list').DataTable({
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
	            "data": { action: "listAll"},
	        },
	        "columns": [
		        { 
		            "data": "catalog", 
		            // "render": function ( data, type, full, meta ){
		            //     return '<div style="white-space: nowrap;">'+data+'</div>'; 
		            // },
		            "width": "15%"
		        },
		        {	"data" : "type" },
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
		        		return '<?php _e("$","LentiManager"); ?>'+data;
		        	},
		        	"width" : "5%"
		        },
		        {
		        	"data": "catalog",
	            //将catalog渲染成两个按钮，点击按钮时将dt单元格的catalog作为参数调用对应的方法
	                "render": function ( data, type, full, meta ){
	                    return '<a class="btn btn-primary btn-xs edit" onclick="editLentil(\''+data+'\',this)"><span class="glyphicon glyphicon-edit"></span></a>'
	                    +'&nbsp'+
	                    '<a data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-xs delete" onclick="deleteLentil(\''+data+'\')"><span class="glyphicon glyphicon-trash"></span></a>';
	                },"orderable": false
		        }
	        ]
        });
	}
	// 校验是否存在货号
	function checkCatalog(){
	    var catalog = document.getElementById('catalog').value.trim();
	    if (catalog.length==0) {//为空时
	        catalogCheck = false;//使用全局变量存储结果
	        $("#catalog_log").html(' * <?php _e("required","LentiManager"); ?>！');//提示文字
	        $("#catalog_log").css({color:"#d44950"});//提示着色
	        $("#catalog").css("borderColor","#d44950");//框着色
	    } else {//不为空时
	        var reg_1 = /^[A-Za-z0-9\-]+$/;
	        var r_1 = catalog.match(reg_1);
	        if(r_1==null){//不匹配正则时
	            catalogCheck = false;//使用全局变量存储结果
	            $("#catalog_log").html(' * <?php _e("invalid","LentiManager"); ?>！');//提示文字
	            $("#catalog_log").css({color:"#d44950"});//提示着色
	            $("#catalog").css("borderColor","#d44950");//框着色
	        } else if(r_1!=null && action=='add') {//添加弹窗中验证通过时查询是否已存在该Catalog（编辑弹窗不验证）
	            $.ajax({
	                /**因为js引擎的单线程机制，这里必需设置为同步（async: false），
	                 * 如果为异步（即 默认 或 async: true）将不会等待ajax结果，而是继续执行ajax之外的代码，
	                 * 直到整个外部方法执行完毕后才执行回调函数，
	                 * 这将导致catalog的校验滞后于form的校验，最终导致保存失败。
	                 */
	                async: false,
	                type: "POST",
	                dataType: "html",
	                url: "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/lenti-ajax.php'; ?>",
	                data: "action=queryRep&catalog="+catalog,
	                success: function(d){//不存在重复
	                    if(d==0){
	                        catalogCheck = true;
	                        $("#catalog_log").html('');//提示文字
	                        $("#catalog").css("borderColor","");//框着色
	                    } else {//存在重复时
	                        catalogCheck = false;
	                        $("#catalog_log").html(' * <?php _e("already exists","LentiManager"); ?>！');//提示文字
	                        $("#catalog_log").css({color:"#d44950"});//提示着色
	                        $("#catalog").css("borderColor","#d44950");//框着色
	                    }
	                }
	            });
	        } else if(r_1!=null && action=='edit') {//（编辑弹窗不验证）
	            catalogCheck = true;
	        }
	    }
	    return catalogCheck;
	}
	// 检查对象是否为空
	function checkIsNotNull(obj){
		var objname = $(obj).attr('name');
		var objvalue = document.getElementById(objname).value.trim();
	    if (objvalue.length==0) {//为空时
	        var objCheck = false;
	        $("#"+objname+"_log").html('*<?php _e("required","LentiManager"); ?>!');//提示文字
	        $("#"+objname+"_log").css({color:"#d44950"});//提示着色
	        $("#"+objname).css("borderColor","#d44950");//框着色
	    } else {//不为空时
	        var objCheck = true;
	        $("#"+objname+"_log").html('');//提示文字
	        // $("#desc_log").css({color:"#d44950"});//提示着色
	        $("#"+objname).css("borderColor","");//框着色
	    }
	    return objCheck;
	}
	// 检查对象不为空且为整型
	function checkisInteger(obj) {
		var objname = $(obj).attr('name');
		var objvalue = document.getElementById(objname).value.trim();
		if (objvalue.length==0) {//为空时
	        var objCheck = false;
	        $("#"+objname+"_log").html('*<?php _e("required","LentiManager"); ?>!');//提示文字
	        $("#"+objname+"_log").css({color:"#d44950"});//提示着色
	        $("#"+objname).css("borderColor","#d44950");//框着色
	    } else {
	    	var reg_int = /^[1-9]\d*$/;
	    	var is_int = objvalue.match(reg_int);
	    	if(is_int==null){
	    		var objCheck = false;
		        $("#"+objname+"_log").html('*<?php _e("required","LentiManager"); ?>!');//提示文字
		        $("#"+objname+"_log").css({color:"#d44950"});//提示着色
		        $("#"+objname).css("borderColor","#d44950");//框着色
	    	}else{
	    		var objCheck = true;
	       		$("#"+objname+"_log").html('');//提示文字
	            $("#"+objname).css("borderColor","");//框着色
	    	}
	    }
	    return objCheck;
	}
	//Price的校验
	function checkPrice(){
	    var price = document.getElementById('price').value.trim();
	    if (price.length==0) {//为空时
	        var priceCheck = false;
	        $("#price_log").html(' * <?php _e("required","LentiManager"); ?>！');//提示文字
	        $("#price_log").css({color:"#d44950"});//提示着色
	        $("#price").css("borderColor","#d44950");//框着色
	    } else {//不为空时
	        var reg_3 = /^[1-9]\d*$/;
	        var r_3 = price.match(reg_3);
	        if(r_3==null){//不匹配正则时
	            var priceCheck = false;
	            $("#price_log").html(' * <?php _e("invalid","LentiManager"); ?>！');//提示文字
	            $("#price_log").css({color:"#d44950"});//提示着色
	            $("#price").css("borderColor","#d44950");//框着色
	        } else {//匹配正则时
	            var priceCheck = true;
	            $("#price_log").html('');//提示文字
	            $("#price").css("borderColor","");//框着色
	        }
	    }
	    return priceCheck;
	}
	// 表单验证
	function CheckForm() {
		var catalogCheck = checkCatalog();
		var priceCheck = checkPrice();
		var checkType = checkIsNotNull($("#type"));
		var checkTiter = checkIsNotNull($("#titer"));
		var checkTiterDesc = checkIsNotNull($("#titer_description"));
		var checkDesc = checkIsNotNull($("#description"));
		var checkDF = checkIsNotNull($("#delivery_format"));
		var checkDT = checkIsNotNull($("#delivery_time"));
		var checkVolume = checkIsNotNull($("#volume"));
		var checkPurity = checkIsNotNull($("#purity"));
		var checkSize = checkisInteger($("#size"));
		var checkVector = checkIsNotNull($("#vector"));
		if(catalogCheck==true && priceCheck==true && checkType==true && checkTiter==true && checkTiterDesc==true && checkVolume==true && checkPurity==true && checkDesc==true && checkDF==true && checkSize==true && checkVector==true && checkDT==true){
	        return true;
	    } else {
	        return false;
	    }
	}
	// 修改
	function editLentil(catalog,obj){
		action = 'edit';
		$(".valid_log").html('');//提示文字
		$("input,textarea").css({
			'border': '1px solid #ddd',
			'borderColor': '#ddd'
		}).val('');
		
		$.ajax({//先查询出该记录，并输出到编辑弹窗中
	        "url": "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/lenti-ajax.php'; ?>",
	        "type": "GET",
	        "data": {action: "listOne", catalog: catalog},
	        beforeSend: function(e){
	            $(obj).attr('disabled',"true");
	        },
	    })
	    .done(function(d){//d为单条记录
	        $(obj).removeAttr('disabled');
	        $("#addModal").modal().find("#addModalLabel").text('<?php _e("Edit Lentivirus","LentiManager"); ?>');
	        var parsedJson = jQuery.parseJSON(d);
	        $("#catalog").val(parsedJson.catalog);
	        $("#type").val(parsedJson.type);
	        $("#size").val(parsedJson.size);
	        $("#price").val(parsedJson.price);
	        $("#titer").val(parsedJson.titer);
	        $("#titer_description").val(parsedJson.titer_description);
	        $("#volume").val(parsedJson.volume);
	        $("#description").val(parsedJson.description);
	        $("#purity").val(parsedJson.purity);
	        $("#delivery_format").val(parsedJson.delivery_format);
	        $("#delivery_time").val(parsedJson.delivery_time);
	        $("#symbol_link").val(parsedJson.symbol_link);
	        $("#pdf_link").val(parsedJson.pdf_link);
	        $("#vector").val(parsedJson.vector);
	        $("#priority").val(parsedJson.priority);
	    })
	    .fail(function() {
	        $(obj).removeAttr('disabled');
	    });

	    //点击"编辑产品"弹窗内的保存按钮时更新记录
	    $("#modal_save_button").unbind('click').click(function(){
	        //其中catalog为目标数据，catalog和old_catalog为更新数据
	        var formCheck = CheckForm();
	        var form_edit = "";
	        form_add = $("#form_add").serialize();

	        if(formCheck==1){//通过表单验证在发起请求
	            $.ajax({
	                type: "GET",
	                dataType: "html",
	                url: "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/lenti-ajax.php'; ?>",
	                data: "action=edit&" + form_add,
	                success: function(d){//d为json字符串{query:1,query_record:1}
	                    var obj = JSON.parse(d);
	                    if(obj.query=="1"){
	                        layer.msg('<?php _e("Successful","LentiManager"); ?>!', {
	                            icon: 1,
	                            time: 1000
	                        });
	                        $('#addModal').modal('hide');//隐藏弹窗
	                        table.ajax.reload( null, false );//重新载入列表
	                    } else if(obj.query=="0"){
	                        layer.msg('<?php _e("You seem to not modify the product","LentiManager"); ?>!', {
	                            icon: 0,
	                            time: 3000
	                        });
	                    } else {
	                        layer.msg('<?php _e("Failed!<br/>Please try again after refresh!","LentiManager"); ?>', {
	                        icon: 2,
	                        time: 3000
	                    });
	                    }
	                }
	            });
	        }
	    });

	}
	// 删除操作
	function deleteLentil(catalog){
		$("#delete_lenti").html('').append(catalog);
		$("#delete_button").unbind('click').click(function(){
			$.ajax({
	            "url": "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/lenti-ajax.php'; ?>",
	            "type": "POST",
	            "data": {action: "delete", catalog: catalog},
	            success: function(d) {//d为1或false
	                var obj = JSON.parse(d);
	                if (obj.query == "1") {
	                    //如果后台删除成功，则刷新表格
	                    layer.msg('<?php _e("Successful","LentiManager"); ?>!', {
	                        icon: 1,
	                        time: 1000
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
	//点击页面内的添加按钮时
	function addLentil(){
		action = 'add';
		$(".valid_log").html('');//提示文字
		$("input,textarea").css({
			'border': '1px solid #ddd',
			'borderColor': '#ddd'
		}).val('');
		document.getElementById("form_add").reset();
	   	$("#addModalLabel").text('<?php _e("Add Lentivirus","LentiManager"); ?>');
	   	$("#modal_save_button").unbind('click').click(function(){
	   		var formCheck = CheckForm();
	   		form_add = $("#form_add").serialize();
	   		if(formCheck==1){
	   			$.ajax({
	                type: "GET",
	                dataType: "html",
	                url: "<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/Service/lenti-ajax.php'; ?>",
	                data: "action=add&"+form_add,
	                success: function(d){//d为json字符串{query:1,query_record:1}
	                    // console.log("ajax request done");
	                    var obj = JSON.parse(d);
	                    if(obj.query=="1"){
	                        layer.msg('<?php _e("Successful","LentiManager"); ?>!', {
	                            icon: 1,
	                            time: 5000
	                        });
	                        $('#addModal').modal('hide');//隐藏弹窗
	                        table.ajax.reload( null, false );//重新载入列表
	                        $("input,textarea").css({
								'border': '1px solid #ddd',
								'borderColor': '#ddd'
							}).val('');
	                    } else {
	                    	layer.msg('<?php _e("Failed!<br/>Please try again after refresh","LentiManager"); ?>!', {
		                        icon: 2,
		                        time: 3000
		                    });
	                    }
	                }
	            });
	   		}
	   	});
	   
	}
	// 上传操作
	function uploadLentiviurs() {
		$("#uploadModal").modal();
		$("input,textarea").css({
			'border': '1px solid #ddd',
			'borderColor': '#ddd'
		}).val('');
		$("#upload_button").unbind('click').click(function(){
			var url = '<?php echo WP_PLUGIN_URL . "/" . dirname(dirname(plugin_basename(__FILE__))) . "/Service/lenti-ajax.php";  ?>?action=uploads';
			var data = new FormData();
			var file = document.getElementById("Files").files[0];
			// var file = $("#Files").val();
			// data.append("action","uploads");
			data.append("file",file);
			console.log(url);
			console.log(file);
			$.ajax({
				url: url,
				type: 'POST',
				processData: false ,
				contentType : false,
				dataType: 'json',
				data: data,
				success:function (msg) {
					if(msg.No==200){
							layer.msg('<?php _e("Successful","LentiManager"); ?>!'+msg.Msg, {
	                            icon: 1,
	                            time: 1000
	                        });
	                        $('#uploadModal').modal('hide');//隐藏弹窗
	                		table.ajax.reload( null, false );//重新载入列表
	                        $("input,textarea").css({
								'border': '1px solid #ddd',
								'borderColor': '#ddd'
							}).val('');
	                 }else{
	                 	layer.msg('<?php _e("Failed","LentiManager"); ?>!<br/>'+msg.Msg, {
	                        icon: 2,
	                        time: 3000
	                    }); 
	                 }
				}
			});
			
		});
	}
</script>