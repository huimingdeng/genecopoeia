<style type="text/css">
	.metabox{width:100%; clear: both;}
	.metabox ul { width: 100%; border: 1px solid #ccc;}
	.metabox ul li {width: 100%; padding: 2px 2px; list-style: none; display: block; line-height: 30px;}
	.metabox ul li span{display: inline-block; margin-right: 5px; width:auto; text-align: center;}
	.metabox ul li.meta-body{ border-top: 1px solid #ccc; }
	.metabox ul li.meta-title>span:nth-child(1),.metabox ul li.meta-body>span:nth-child(1){ width:10%; }
	.metabox ul li.meta-title>span:nth-child(2),.metabox ul li.meta-body>span:nth-child(2){ width:60%; }
	.metabox ul li.meta-title>span:nth-last-child(1),.metabox ul li.meta-body>span:nth-last-child(1){ width:20%; }
	
</style>
<div class="metabox">
	<ul>
		<li class="meta-title"><span class="box"><input type="checkbox" class="select"></span><span class="tit"><?php _e('Title', 'myfaqs'); ?></span><span class="ac"><?php _e('Action', 'myfaqs'); ?></span></li>
		<li class="meta-body">
			<span><input type="checkbox" name="ids[]" value="<?php  ?>"></span>
			<span></span>
			<span><a href="javascript:void(0);" onclick=""><?php _e('setting', 'myfaqs'); ?></a></span>
		</li>
		<li class="meta-body">
			<span><input type="checkbox" name="ids[]" value="<?php  ?>"></span>
			<span></span>
			<span><a href="javascript:void(0);" onclick=""><?php _e('setting', 'myfaqs'); ?></a></span>
		</li>
	</ul>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
        $("input.select").change(function(event) {
            $("input[name='ids[]']").prop('checked', $(this).is(':checked')?true:false);
        });
    });
</script>