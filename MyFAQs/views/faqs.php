<div class="wrap">
    <?php include "header.php"; ?>
    <h2><?php echo $title;?> &nbsp;&nbsp;<a href="javascript:void(0);" onclick="Faqs.addPopup()" class="btn btn-primary"><?php _e('Add New', 'myfaqs');?></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="btn btn-warning" onclick="Faqs.export();"><?php _e('Generate Json File', 'myfaqs'); ?></a>&nbsp;<?php $file = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'faqs.json'; if(file_exists($file)){ $url = dirname(plugin_dir_url(__FILE__)).'/assets/json/faqs.json';?> <a href="<?php echo $url; ?>" class="btn btn-success"><?php _e("Download Json File", 'myfaqs'); ?></a> <?php } ?></h2>
    <content>
        <form method="GET" action="<?php echo admin_url('admin.php'); ?>" class="form-inline">
            <div class="row">
                <input type="hidden" name="page" value="faqs">
                <div class="col-md-3 col-md-offset-9 pull-rignt">
                    <div class="form-group">
                        <input type="text" class="form-control" autocomplete="off" name="s" id="search" placeholder="<?php _e("Search FAQs...",'myfaqs'); ?>">
                        <input type="submit" class="btn btn-default" value="<?php _e("Search",'myfaqs'); ?>">
                    </div>
                </div>
            </div>
            <div class="row" style="margin-bottom: 5px;">
                <div class="col-md-2">
                    <div class="form-group">
                        <select name="action" style="height: 34px;" class="form-control">
                            <option value="-1"><?php _e("Bulk Action", 'myfaqs'); ?></option>
                            <option value="trash"><?php _e("Delete All", 'myfaqs'); ?></option>
                        </select>
                        <input type="submit" class="btn btn-default" value="<?php _e("Apply", 'myfaqs'); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name="cat" style="height: 34px;" class="form-control">
                            <option value="0"><?php _e("All Categories", 'myfaqs'); ?></option>
                            <option value="trash"><?php _e("Delete All", 'myfaqs'); ?></option>
                        </select>
                        <input type="submit" class="btn btn-default" value="<?php _e("Filter", 'myfaqs'); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover table-striped table-responsive table-bordered">
                        <thead>
                        <tr>
                            <th ><input type="checkbox" class="select"></th>
                            <th width="15%"><?php _e('Title','myfaqs');?></th>
                            <th width="40%"><?php _e('Answer', 'myfaqs');?></th>
                            <th><?php _e('Category', 'myfaqs');?></th>
                            <th><?php _e('EditDate', 'myfaqs');?></th>
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
                    <?php echo $page; ?>
                </div>
            </div>
        </form>
    </content>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("#search").keydown(function(event) {
                if (event.keyCode == 13) {
                    // alert($(this).val());
                }
            });
            
       
        });
    </script>
    <aside></aside>
</div>
