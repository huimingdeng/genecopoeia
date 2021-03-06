<div class="wrap">
    <?php include "header.php"; ?>
    <h2><?php echo $title;?> &nbsp;&nbsp;<a href="javascript:void(0);" onclick="Faqs.addPopup()" class="btn btn-primary"><?php _e('Add New', 'myfaqs');?></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="btn btn-warning" onclick="Faqs.export();"><?php _e('Generate Json File', 'myfaqs'); ?></a>&nbsp;<?php $file = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'faqs.json'; if(file_exists($file)){ $url = dirname(plugin_dir_url(__FILE__)).'/assets/json/faqs.json';?> <a href="<?php echo $url; ?>" class="btn btn-success"><?php _e("Download Json File", 'myfaqs'); ?></a> <?php } ?></h2>
    <content>
        <form method="GET" action="<?php echo admin_url('admin.php'); ?>" class="form-inline">
            <div class="row">
                <input type="hidden" name="page" value="faqs">
                <!-- <input type="hidden" name="p" value="<?php echo $p; ?>"> -->
                <div class="col-md-3 col-md-offset-9 pull-rignt">
                    <div class="form-group">
                        <input type="text" class="form-control" autocomplete="off" name="s" id="search" placeholder="<?php _e("Search FAQs...",'myfaqs'); ?>">
                        <input type="submit" class="btn btn-default" value="<?php _e("Search",'myfaqs'); ?>">
                    </div>
                </div>
            </div>
            <div class="row" style="margin-bottom: 5px;">
                <div class="col-md-4">
                    <div class="form-group">
                        <select name="cat" style="height: 34px;" class="form-control">
                            <option value="0"><?php _e("All Categories", 'myfaqs'); ?></option>
                            <?php foreach($categories as $category){ 
                                if($cat == $category['id']){
                                    ?>
                                    <option selected = "selected" value = "<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                    <?php
                                }else{?>
                                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                            <?php }
                            } ?>
                        </select>
                        <input type="submit" class="btn btn-default" value="<?php _e("Filter", 'myfaqs'); ?>">
                    </div>
                </div>
                <div class="col-md-2 col-md-offset-6 pull-rignt">
                    <div class="pull-rignt" style="text-align: right;">
                        <label><span class="text-muted"><?php echo sprintf(_n( '%s item', '%s items', $total), $total); ?></span></label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover table-striped table-responsive table-bordered">
                        <thead>
                        <tr>
                            <th ><input type="checkbox" class="select"></th>
                            <th width="15%">
                                <a href="<?php echo $uri.'&orderby=title&order=asc'; ?>" aria-label='<?php if($order[0]=='title'&&$order[1]=='asc'){echo 'desc';}else{echo 'asc';} ?>' name="title" class="dropup">
                                    <span><?php _e('Title','myfaqs');?></span>
                                    <span class="<?php if($order[0]=='title') echo 'caret'; ?>"></span>
                                </a>
                            </th>
                            <th width="40%">
                                <a href="<?php echo $uri.'&orderby=answer&order=asc'; ?>" aria-label='<?php if($order[0]=='answer'&&$order[1]=='asc'){echo 'desc';}else{echo 'asc';} ?>' name="answer" class="dropup">
                                    <span><?php _e('Answer', 'myfaqs');?></span>
                                    <span class="<?php if($order[0]=='answer') echo 'caret'; ?>"></span>
                                </a>
                            </th>
                            <th>
                                <a href="<?php echo $uri.'&orderby=category&order=asc'; ?>" aria-label='<?php if($order[0]=='category'&&$order[1]=='asc'){echo 'desc';}else{echo 'asc';} ?>' name="category" class="dropup">
                                    <span><?php _e('Category', 'myfaqs');?></span>
                                    <span class="<?php if($order[0]=='category') echo 'caret'; ?>"></span>
                                </a>
                            </th>
                            <th>
                                <a href="<?php echo $uri.'&orderby=editdate&order=asc'; ?>" aria-label='<?php if($order[0]=='editdate'&&$order[1]=='asc'){echo 'desc';}else{echo 'asc';} ?>' name="editdate" class="dropup">
                                    <span><?php _e('EditDate', 'myfaqs');?></span>
                                    <span class="<?php if($order[0]=='editdate') echo 'caret'; ?>"></span>
                                </a>
                            </th>
                            <th><?php _e('Action', 'myfaqs'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($data)){
                                foreach($data as $faq){?>
                                <tr>
                                    <td><input type="checkbox" name="ids[]" value="<?php echo $faq['id']; ?>"></td>
                                    <td>
                                        <?php echo mb_substr($faq['title'], 0, 10, 'utf8').'...'; ?>
                                    </td>
                                    <td ><?php echo mb_substr( htmlentities($faq['answer']), 0, 80, 'utf8').'...'; ?></td>
                                    <td><?php echo $faq['name'] ?></td>
                                    <td><?php echo $faq['editdate']; ?></td>
                                    <td><a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="Faqs.edit(<?php echo $faq['id']; ?>);"><?php _e('Edit', 'myfaqs'); ?></a>&nbsp;<a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="if(confirm('<?php _e("Are you sure you want to delete it?",'myfaqs'); ?>')===true){ Faqs.delete(<?php echo $faq['id']; ?>); }"><?php _e('Delete', 'myfaqs'); ?></a></td>
                                </tr>
                                <?php }
                            }else{
                                ?>
                                <tr>
                                    <td colspan="6" align="center"><?php _e('No data', 'myfaqs');?>....</td>
                                </tr>
                                <?php
                            }?>
                        </tbody>
                    </table>
                    <?php echo $pages; ?>
                </div>
            </div>
        </form>
    </content>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("#search").keydown(function(event) {
                if (event.keyCode == 13) {
                }
            });
            var defult = '<?php echo $order[0];?>';
            $('.table th>a').hover(function(){
                var _self = this;
                var url = $(_self).attr('href');
                
                var uri_v = $(_self).attr('aria-label');
                url = url.replace(/(asc|desc)$/i, uri_v );
                $(_self).attr('href',url);
                $(_self).children('span:last-child').addClass('caret');
                if(uri_v == 'desc'){$(_self).removeClass('dropup');}
            },function(){
                var _self = this;
                var uri_k = $(_self).attr('name');
                if(uri_k!=defult)
                    $(this).children('span:last-child').removeClass('caret');
            });


       
        });
    </script>
    <aside></aside>
</div>
