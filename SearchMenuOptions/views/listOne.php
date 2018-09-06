<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/assets/css/dataTables.bootstrap.css">
<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))); ?>/assets/js/dataTables.bootstrap.min.js"></script>
<ul id="list" class="nav nav-pills">
	<?php foreach ($title as $k => $v) {
			echo ($v['menu_name']==$menu_name)?("<li class=\"active\">\n"):("<li>\n");
			echo "<a href=\"javascript:void(0);\" title='".$v['menu_name']."' onclick=\"SearchMenuOptions.toogleTable(this,'".$v['menu_name']."')\" >".$v['menu_name']."</a>\n";
			echo "</li>\n";
		}
	 ?>
</ul>
<div class="toogleBody">
	<?php 
		
			echo ("<div id=\"".$menu_name."\" class=\"contentBody col-md-12\" style=\"display:block;\">\n");
	?>
	<table class="table table-hover table-striped" id="<?php echo $menu_name.'-body';?>">
		<thead>
			<tr>
				<th>sn</th>
				<th>menu_name</th>
				<th>classif_name</th>
				<th>classif_order</th>
				<th>item_name</th>
				<th>item_display_name</th>
				<th>item_value</th>
				<th>item_order</th>
				<th>compare_mode</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($newdata as $kk => $vv) {?>
				<tr>
					<td><?php echo $vv['sn'];?></td>
					<td><?php echo $vv['menu_name'];?></td>
					<td><?php echo $vv['classify_name'];?></td>
					<td><?php echo $vv['classify_order'];?></td>
					<td><?php echo $vv['item_name'];?></td>
					<td><?php echo $vv['item_display_name'];?></td>
					<td><?php echo $vv['item_value'];?></td>
					<td><?php echo $vv['item_order'];?></td>
					<td><?php echo $vv['compare_mode'];?></td>
					<td><a href="javascript:void(0);" class="btn btn-sm btn-success" onclick="SearchMenuOptions.editOne();"><span class="glyphicon glyphicon-edit"></span></a> &nbsp; <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="SearchMenuOptions.delOne();"><span class="glyphicon glyphicon-trash"></span></a></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php
		echo "</div>\n";
		
	 ?>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#<?php echo $menu_name.'-body';?>').DataTable({
            responsive: true,
            "dom": '<"container-fluid"<"row"<"col-md-2"l><"col-md-6"B><"col-md-2"f>>rt<"rol-md-2"><"col-md-2"i>p>',
        });
		
	});
</script>