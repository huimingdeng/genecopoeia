<?php 
require_once(dirname(__FILE__)."/../../../wp-blog-header.php");
header('HTTP/1.1 200 OK');

global $wpdb;

if($_REQUEST['action']=='insert'){

	$post_Id = $_REQUEST['postid'];
	$classify = $_REQUEST['list_search_bar_classify'];
	if(get_option('list_search_bar_plugs')){
		$list_search_bar_plugs_options = get_option('list_search_bar_plugs');
		$list_search_bar_plugs_options_array = json_decode($list_search_bar_plugs_options,true);
		// 得到数组的键值
		// $list_search_bar_plugs_object_search_keys = array_keys($list_search_bar_plugs_options_array);
		// foreach($list_search_bar_plugs_object_search_keys as $v){
		// 	if(strstr($v,"list_search_bar_plugs_".$post_Id)!==false){
		// 		$search_bar_class_end_no++;
		// 	}
		// }
	}else{
		$list_search_bar_plugs_options_array = array();
	}

	// $search_key = "list_search_bar_plugs_".$post_Id."-".$search_bar_class_end_no;
	$search_key = "list_search_bar_plugs_".$classify;

	$list_search_bar_plugs_options_array[$search_key] = array(
		'product_type'=>$_REQUEST['product_type'],
		'format'=>$_REQUEST['format'],
		'species'=>$_REQUEST['species']
	);

	$list_search_bar_plugs_new_options = json_encode($list_search_bar_plugs_options_array);
	update_option('list_search_bar_plugs',$list_search_bar_plugs_new_options);
	
	// $classify = $post_Id.'-'.$search_bar_class_end_no;
	
	$btn = <<<EOF
<a href="javascript:void(0);" class="btn btn-success UpdateSeach" value="$classify"title="Update list_search_bar_plugs_$classify" id="$classify">Update SearchBar$classify</a>
EOF;
	
	$html = <<<EOF
<input type="text" name="shortcode" class="form-control" id="input-$classify" value="[list_search_bar_plugs class='$classify']" />
EOF;

	echo json_encode(array('status'=>true,'notice'=>'Use this short tag in the post:','html'=>$html,'btn'=>$btn));

	// insert & updateOptions Operation end
	

}elseif($_REQUEST['action']=='updateOptions'){//未测试-邓晖明

	$post_Id = $_REQUEST['postid'];
	$classify = $_REQUEST['list_search_bar_classify'];
	if(get_option('list_search_bar_plugs')){
		$list_search_bar_plugs_options = get_option('list_search_bar_plugs');
		$list_search_bar_plugs_options_array = json_decode($list_search_bar_plugs_options,true);
		// 得到数组的键值
		$list_search_bar_plugs_object_search_keys = array_keys($list_search_bar_plugs_options_array);
		$search_key = "list_search_bar_plugs_".$classify;
		$update_bool = false;
		if(in_array($search_key,$list_search_bar_plugs_object_search_keys)){
			$list_search_bar_plugs_options_array[$search_key] = array(
				'product_type'=>$_REQUEST['product_type'],
				'format'=>$_REQUEST['format'],
				'species'=>$_REQUEST['species']
			);
			$list_search_bar_plugs_new_options = json_encode($list_search_bar_plugs_options_array);
			update_option('list_search_bar_plugs',$list_search_bar_plugs_new_options);
			$update_bool = true;
		}
	
		if($update_bool){
			echo json_encode(array('status'=>true,'notice'=>'Use this short tag in the post:[list_search_bar_plugs class='.$classify.']'));
		}else{
			echo json_encode(array('status'=>false,'notice'=>'Update failed, please check for an error.'));
		}
		
	}else{
		echo json_encode(array('status'=>false,'notice'=>'System Error....Please inform the administrator.'));
	}

	// updateOptions Operation end

}else if($_REQUEST['action']=='delete'){// delete Operation
	
	$classify = $_REQUEST['list_search_bar_classify'];
	
	if(get_option('list_search_bar_plugs')){
		$list_search_bar_plugs_options = get_option('list_search_bar_plugs');
		$list_search_bar_plugs_options_array = json_decode($list_search_bar_plugs_options,true);
		$search_key = "list_search_bar_plugs_".$classify;
		unset($list_search_bar_plugs_options_array[$search_key]);
		if(empty($list_search_bar_plugs_options_array)){
			delete_option('list_search_bar_plugs');
		}else{
			update_option('list_search_bar_plugs',json_encode($list_search_bar_plugs_options_array));
		}
		echo json_encode(array('status'=>true,'notice'=>'Search Bar Plugin Delete.','delbtn'=>$classify));
	}else{
		echo json_encode(array('status'=>false,'notice'=>'Sorry....'));
	}

}else if($_REQUEST['action']=='select'){
	
	$post_Id = $_REQUEST['postid'];
	$search_bar_class_end_no=1;//初始设置为1
	if(get_option('list_search_bar_plugs')){
		$list_search_bar_plugs_options = get_option('list_search_bar_plugs');
		$list_search_bar_plugs_options_array = json_decode($list_search_bar_plugs_options,true);
		
		$select_bool = false;
		$btn = $html ="";
		
		// 得到数组的键值
		$list_search_bar_plugs_object_search_keys = array_keys($list_search_bar_plugs_options_array);
		
		foreach($list_search_bar_plugs_object_search_keys as $v){
			if(strstr($v,"list_search_bar_plugs_".$post_Id)!==false){
				$classify = str_replace('list_search_bar_plugs_', '', $v);
				
				$btn .= <<<EOF
<a href="javascript:void(0);" class="btn btn-success UpdateSeach" value="$classify" title="Update list_search_bar_plugs_$classify" id="$classify">Update SearchBar$classify</a>\n
EOF;
				$html .= <<<EOF
<input type="text" name="shortcode" class="form-control" id="input-$classify" value="[list_search_bar_plugs class='$classify']" />\n
EOF;
				$search_bar_class_end_no++;
				$select_bool = true;
			}
		}

		
		
		if($select_bool){
			echo json_encode(array('status'=>true,'notice'=>$btn,'html'=>$html));
		}else{
			echo json_encode(array('status'=>false,'test'));
		}
		
	}else{
		echo json_encode(array('status'=>false));
	}
// The add Page 
}else if($_REQUEST['action']=='add'){
	$post_Id = $_REQUEST['postid'];
	$sql = "SELECT sn,classify_name,item_display_name FROM `left_menu_option` WHERE menu_name='search3' ORDER BY classify_order, item_order;";
	$menu = $wpdb->get_results($sql);
	$search_bar_class_end_no = 1;
	if(get_option('list_search_bar_plugs')){
		$list_search_bar_plugs_options = get_option('list_search_bar_plugs');
		$list_search_bar_plugs_options_array = json_decode($list_search_bar_plugs_options,true);
		// 得到数组的键值
		$list_search_bar_plugs_object_search_keys = array_keys($list_search_bar_plugs_options_array);
		foreach($list_search_bar_plugs_object_search_keys as $v){
			if(strstr($v,"list_search_bar_plugs_".$post_Id)!==false){
				// 已经存在，则需要设置为当前值从新自增
				$search_bar_class_end_no = str_replace('list_search_bar_plugs_'.$post_Id.'-', '', $v);
				$search_bar_class_end_no++;
			}
		}
		$search_key = "list_search_bar_plugs_".$post_Id."-".$search_bar_class_end_no;
		$product_type = $list_search_bar_plugs_options_array[$search_key]['product_type'];
		$format_array = $list_search_bar_plugs_options_array[$search_key]['format'];
		$species_array = $list_search_bar_plugs_options_array[$search_key]['species'];
	}

	$classify = $post_Id.'-'.$search_bar_class_end_no;

	ob_start();?>
	<div class="col-md-12" style="margin-bottom: 10px;">
		<button type="submit" class="btn btn-success">Save</button>
		<input type="button" class="btn btn-default close" value="Close">
	</div>
	<div class="col-xs-8 col-md-6">
		<table class="table table-hover table-bordered table-striped">
			<thead><tr><th colspan="2">Prodct Type</th></tr></thead>
			<tbody>
				<?php foreach ($menu as $k => $v) {
					if($v->classify_name=='Product Type'){?>
				<tr>
					<td><input type="radio" name="product_type" value="<?php echo $v->sn;?>" <?php echo (isset($product_type)&&!is_null($product_type)&&($product_type==$v->sn))?'checked':''; ?> /></td>
					<td><?php echo $v->item_display_name;?></td>
				</tr>
				<?php }
				}?>
			</tbody>
		</table>
	</div>

	<div class="col-xs-8 col-md-6">
		<table class="table table-hover table-bordered table-striped">
			<thead><tr><th colspan="2">Format</th></tr></thead>
			<tbody>
				<?php foreach ($menu as $k => $v) {
					if($v->classify_name=='Format'){?>
				<tr>
					<td><input type="checkbox" name="format[]" value="<?php echo $v->sn;?>" <?php echo (isset($format_array)&&!is_null($format_array)&&(in_array($v->sn,$format_array)))?"checked='checked'":'';?> /></td>
					<td><?php echo $v->item_display_name;?></td>
				</tr>
				<?php }
				}?>
			</tbody>
		</table>
		<table class="table table-hover table-bordered table-striped">
			<thead><tr><th colspan="2">Species</th></tr></thead>
			<tbody>
				<?php foreach ($menu as $k => $v) {
					if($v->classify_name=='Species'){?>
				<tr>
					<td><input type="checkbox" name="species[]" value="<?php echo $v->sn?>" <?php echo (isset($species_array)&&!is_null($species_array)&&(in_array($v->sn,$species_array)))?"checked='checked'":'';?> /></td>
					<td><?php echo $v->item_display_name;?></td>
				</tr>
				<?php }
				}?>
			</tbody>
		</table>
		<div class="col-md-12">
			<input type="hidden" name="action" value="insert">
			<input type="hidden" name="list_search_bar_classify" value="<?php echo $classify;?>">
			<input type="button" class="btn btn-default close" value="Close">
			<button type="submit" class="btn btn-success">Save</button>
		</div>
	</div>
<?php $html = ob_get_contents();
	ob_end_clean();
	echo json_encode(array('status'=>true,'html'=>$html));
// The update Page
}else if('update'==$_REQUEST['action']){
	$classify = $_REQUEST['list_search_bar_classify'];
	$sql = "SELECT sn,classify_name,item_display_name FROM `left_menu_option` WHERE menu_name='search3' ORDER BY classify_order, item_order;";
	$menu = $wpdb->get_results($sql);
	if(get_option('list_search_bar_plugs')){
		$list_search_bar_plugs_options = get_option('list_search_bar_plugs');
		$list_search_bar_plugs_options_array = json_decode($list_search_bar_plugs_options,true);
		$search_key = "list_search_bar_plugs_".$classify;
		$product_type = $list_search_bar_plugs_options_array[$search_key]['product_type'];
		$format_array = $list_search_bar_plugs_options_array[$search_key]['format'];
		$species_array = $list_search_bar_plugs_options_array[$search_key]['species'];
	}

	ob_start();?>
	<div class="col-md-12" style="margin-bottom: 10px;">
		<button type="submit" class="btn btn-success">Save</button>
		<?php if('update'==$_REQUEST['action']){?>
		<input type="button" class="btn btn-danger deleteSearch" value="delete">
		<?php }?>
		<input type="button" class="btn btn-default close" value="Close">
	</div>
	<div class="col-xs-8 col-md-6">
		<table class="table table-hover table-bordered table-striped">
			<thead><tr><th colspan="2">Prodct Type</th></tr></thead>
			<tbody>
				<?php foreach ($menu as $k => $v) {
					if($v->classify_name=='Product Type'){?>
				<tr>
					<td><input type="radio" name="product_type" value="<?php echo $v->sn;?>" <?php echo (isset($product_type)&&!is_null($product_type)&&($product_type==$v->sn))?'checked':''; ?> /></td>
					<td><?php echo $v->item_display_name;?></td>
				</tr>
				<?php }
				}?>
			</tbody>
		</table>
	</div>

	<div class="col-xs-8 col-md-6">
		<table class="table table-hover table-bordered table-striped">
			<thead><tr><th colspan="2">Format</th></tr></thead>
			<tbody>
				<?php foreach ($menu as $k => $v) {
					if($v->classify_name=='Format'){?>
				<tr>
					<td><input type="checkbox" name="format[]" value="<?php echo $v->sn;?>" <?php echo (isset($format_array)&&!is_null($format_array)&&(in_array($v->sn,$format_array)))?"checked='checked'":'';?> /></td>
					<td><?php echo $v->item_display_name;?></td>
				</tr>
				<?php }
				}?>
			</tbody>
		</table>
		<table class="table table-hover table-bordered table-striped">
			<thead><tr><th colspan="2">Species</th></tr></thead>
			<tbody>
				<?php foreach ($menu as $k => $v) {
					if($v->classify_name=='Species'){?>
				<tr>
					<td><input type="checkbox" name="species[]" value="<?php echo $v->sn?>" <?php echo (isset($species_array)&&!is_null($species_array)&&(in_array($v->sn,$species_array)))?"checked='checked'":'';?> /></td>
					<td><?php echo $v->item_display_name;?></td>
				</tr>
				<?php }
				}?>
			</tbody>
		</table>
		<div class="col-md-12">
			<input type="hidden" name="action" value="updateOptions">
			<input type="hidden" name="list_search_bar_classify" value="<?php echo $classify;?>">
			<input type="button" class="btn btn-default close" value="Close">
			<button type="submit" class="btn btn-success">Save</button>
		</div>
	</div>
<?php $html = ob_get_contents();
	ob_end_clean();
	echo json_encode(array('status'=>true,'html'=>$html));
}


