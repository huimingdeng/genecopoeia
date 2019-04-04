<style type="text/css">
	.metabox{width:100%; clear: both;}
	.metabox ul.bd { width: 100%; border: 1px solid #ccc;}
	.metabox ul li {width: 100%; padding: 2px 0px; list-style: none; display: block; line-height: 30px; height: 30px; overflow: hidden; margin-bottom: 0px;}
	.metabox ul li span{ display: inline-block; margin-right: 5px; width:auto; text-align: center; height: 30px; overflow: hidden;}
	.metabox ul li.meta-body{ border-top: 1px solid #ccc; }
	.metabox ul li.meta-title>span:nth-child(1),.metabox ul li.meta-body>span:nth-child(1){ width:10%; }
	.metabox ul li.meta-title>span:nth-child(2),.metabox ul li.meta-body>span:nth-child(2){ width:60%; }
	.metabox ul li.meta-title>span:nth-last-child(1),.metabox ul li.meta-body>span:nth-last-child(1){ width:20%; }
	.metabox ul li.meta-body>span:nth-child(2) {text-align: left; text-indent: 2em;  }
	/*分页样式*/
	.metabox ul.pg { width: 100%; }
	.metabox ul.pg li { padding-left: 5px; }
	.metabox ul.pg a{display: inline-block; margin-right: 2px; padding: 2px;}
</style>
<?php 
// 测试查询10条faq数据
global $wpdb;
$sql = "SELECT id,title FROM _faq_question order by editdate DESC limit 1,10;";
$test = $wpdb->get_results($sql, ARRAY_A);
?>
<div class="metabox">
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
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
        $("input.select").change(function(event) {
            $("input[name='ids[]']").prop('checked', $(this).is(':checked')?true:false);
        });
    });
</script>