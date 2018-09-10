	<style type="text/css">
		.subhead{ border-bottom:1px dashed #ccc;}
	</style>
	<?php  if('previewdraft' == $action){?>
	<h1><?php echo $query->title; ?></h1>
	<?php } ?>
	<p class="subhead">来源:&nbsp;<a href="<?php echo $query->source_url; ?>" target="_blank" title="<?php echo $query->source; ?>"><?php echo $query->source; ?></a>&nbsp;|&nbsp;作者:&nbsp;<?php echo (empty($query->author))?('佚名'):($query->author); ?>&nbsp;|&nbsp;时间:&nbsp;<?php echo (!empty($query->source_date))?(date("M d,Y",strtotime($query->source_date))):(''); ?></p>
	<?php echo implode('', $data); ?>