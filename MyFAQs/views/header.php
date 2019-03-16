<ul class="nav nav-tabs">
    <li role="presentating" <?php if($actived=='categories'){?>class="active"<?php }?>><a href="/wp-admin/admin.php?page=categories" title=""><i class="glyphicon glyphicon-tags"></i>&nbsp;&nbsp;<?php _e('Categories', 'myfaqs');?></a></li>
    <li role="presentating" <?php if($actived=='faqs'){?>class="active"<?php }?>><a href="/wp-admin/admin.php?page=faqs" title=""><i class="glyphicon glyphicon-list"></i>&nbsp;&nbsp;<?php _e('Faqs', 'myfaqs');?></a></li>
    <li role="presentating" <?php if($actived=='traces'){?>class="active"<?php }?>><a href="/wp-admin/admin.php?page=traces" title=""><i class="glyphicon glyphicon-screenshot"></i>&nbsp;&nbsp;<?php _e('Traces', 'myfaqs');?></a></li>
</ul>
