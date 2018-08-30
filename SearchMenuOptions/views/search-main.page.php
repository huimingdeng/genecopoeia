<div class="wrap">
	<div class="row">
		<div class="col-md-12">
			<h2><span class="glyphicon glyphicon-th-list"></span>&nbsp;Search Menu Options &nbsp;<a href="<?php echo WP_PLUGIN_URL . '/' . dirname(dirname(plugin_basename(__FILE__))) . '/help%20manual.docx'; ?>" data-toggle="tooltip" data-placement="top" title="Instructions document."><span class="glyphicon glyphicon-question-sign"></span></a></h2>
		</div>
	</div>
	<div class="row">
		<nav class="navbar navbar-default">
			<div class="container">
				<ul>
					<li></li>
				</ul>
			</div>
		</nav>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var data = { action: 'spectrom_sync', operation: 'showmenu' };
		$.ajax({
			type: 'post',
			async: true, // false,
			data: data,
			url: ajaxurl,
			success: function(response) {
				console.log(response);
			}
		});
	});
</script>