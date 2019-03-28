<link rel="stylesheet" href="<?php echo dirname(plugin_dir_url(__FILE__)). '/assets/css/bootstrap.min.css';?>" type="text/css">
<h1>hello</h1>
<div class="wrap">
	<?php 
	 ?>
	<table class="table table-striped table-responsive" id="myfaqs-<?php echo $atts['class'];?>" title="<?php echo $atts['title']; ?>">
		<thead>
	        <tr>
	            <th ><input type="checkbox" name="select"></th>
	            <th width="15%"><?php _e('Title','myfaqs');?></th>
	            <th width="40%"><?php _e('Answer', 'myfaqs');?></th>
	            <th><?php _e('Category', 'myfaqs');?></th>
	            <th><?php _e('EditDate', 'myfaqs');?></th>
	            <th><?php _e('Action', 'myfaqs'); ?></th>
	        </tr>
        </thead>
        <tbody>
        	<tr>
        		<td></td>
        		<td><?php echo $faq['title']; ?></td>
        		<td><?php echo $faq['answer']; ?></td>
        		<td><?php echo $faq['category']; ?></td>
        		<td><?php echo $faq['editdate']; ?></td>
        		<td><?php echo $faq['id']; ?></td>
        	</tr>
        </tbody>
	</table>
</div>