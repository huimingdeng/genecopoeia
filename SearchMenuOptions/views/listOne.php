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
		foreach($title as $k => $v){
			echo ($v['menu_name']==$menu_name)?("<div id=\"".$v."\" class=\"contentBody col-md-12\" style=\"display:block;\">\n"):("<div id=\"".$v."\" class=\"contentBody col-md-12\" style=\"display:none;\">\n");
	?>
	<table class="table table-hover table-striped" id="<?php echo $v['menu_name'].'-body';?>">
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
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<?php
			echo "</div>\n";
		}
	 ?>
</div>