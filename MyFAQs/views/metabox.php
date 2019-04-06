<?php 
// 测试查询10条faq数据
global $wpdb;
$sql = "SELECT id,title FROM _faq_question order by editdate DESC limit 1,10;";
$test = $wpdb->get_results($sql, ARRAY_A);
?>
<div class="metabox">
	<input type="button" class="btn" value="<?php _e('Add', 'myfaqs'); ?>">
	<div class="metahtml">
		<!-- ajax content list -->
	</div>
	<?php if(false){ ?><!-- return content -->
	<ul class="bd">
		<li class="meta-title"><span class="box"><input type="checkbox" class="select"></span><span class="tit"><?php _e('Title', 'myfaqs'); ?></span><span class="ac"><?php _e('Action', 'myfaqs'); ?></span></li>
		<?php foreach($test as $tit){ ?>
			<li class="meta-body">
				<span><input type="checkbox" name="ids[]" value="<?php echo $tit['id'];?>"></span>
				<span><?php echo $tit['title']; ?></span>
				<span><a href="javascript:void(0);" onclick=""><?php _e('setting', 'myfaqs'); ?></a></span>
			</li>
		<?php } ?>
	</ul>
	<ul class="pg"><li><a href="javascript:void(0);">1</a><a href="javascript:void(0);">2</a><a href="javascript:void(0);">3</a></li></ul>
	<?php } ?>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
        $("input.select").change(function(event) {
            $("input[name='ids[]']").prop('checked', $(this).is(':checked')?true:false);
        });
    });
</script>