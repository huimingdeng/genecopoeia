
	<div class="btn-list">
		<input type="button" class="btn btn-save" value="<?php _e('Save', 'myfaqs'); ?>">
		<input type="button" class="btn btn-close" onclick="Traces.close();" value="<?php _e('Close', 'myfaqs'); ?>">
	</div>
	<ul class="bd">
		<li class="meta-title"><span class="box"><input type="checkbox" class="select"></span><span class="tit"><?php _e('Title', 'myfaqs'); ?></span><span class="ac"><?php _e('Action', 'myfaqs'); ?></span></li>
	<?php foreach($faqs as $tit){ ?>
	    <li class="meta-body">
	        <span><input type="checkbox" name="ids[]" value="<?php echo $tit['id'];?>"></span>
	        <span><?php echo $tit['title']; ?></span>
	        <span><a href="javascript:void(0);" onclick=""><?php _e('setting', 'myfaqs'); ?></a></span>
	    </li>
	<?php } ?>
	</ul>
	<ul class="pg"><?php echo $pght; ?></ul>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			
			$('.btn-save').click(function(event) {
				// var set = new Array();

				// $("input[name='ids[]']:checked").each(function(index, el) {
				// 	set.push($(this).val());
				// });

				var data = {
			        "operation": 'add',
			        "type": Traces.myaction,
			        "action": Traces.ajaxponit,
			        'data': $("input[name='ids[]']:checked").serialize()+'&postid='+$('input[name=postid]').val()
			    };

			    var add_xhr = {
			        type: 'post',
			        async: true, // false,
			        data: data,
			        url: ajaxurl,
			        dataType: 'JSON',
			        success: function(response) {
			        	console.log(response);
			        }
			    };
			    // console.log(data);
			    jQuery.ajax(add_xhr);
			}); 
		});
	</script>