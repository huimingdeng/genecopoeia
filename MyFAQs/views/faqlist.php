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
<ul class="pg"><li><a href="javascript:void(0);">1</a><a href="javascript:void(0);">2</a><a href="javascript:void(0);">3</a></li></ul>