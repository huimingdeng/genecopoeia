<div class="metabox">
	<input type="hidden" name='postid' value="<?php echo $postid; ?>">
	<input type="button" class="btn" onclick="Traces.list();" value="<?php _e('Add', 'myfaqs'); ?>">
	<div class="metahtml">
		<!-- ajax content list -->
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
        $("input.select").live('change',function(event) {
            $("input[name='ids[]']").prop('checked', $(this).is(':checked')?true:false);
        });
    });
</script>