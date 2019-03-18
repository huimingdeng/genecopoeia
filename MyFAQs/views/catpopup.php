<div class="modal fade" id="editModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="add-modal-dialog">
        <div class="modal-content">
            <form method="post" id="editForm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="deleteModalLabel">
                        <?php _e('Edit',"myfaqs"); ?>
                        <code><?php echo $data['name'];?></code>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                        <label for="name"><?php _e('Name', 'myfaqs');?>: </label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $data['name'];?>">
                    </div>
                    <div class="form-group">
                        <label for="slug"><?php _e('Slug','myfaqs');?>: </label>
                        <input type="text" class="form-control" id="slug" name="slug" value="<?php echo $data['slug'];?>">
                    </div>
                    <div class="form-group">
                        <label for="parent"><?php _e('Parent for Category', 'myfaqs');?>:</label>
                        <select name="parent" id="parent" class="form-control">
                            <option value="0" <?php if($data['parent']==0) echo 'selected = "selected"';?>><?php _e('Non', 'myfaqs');?></option>
                            <?php if(!empty($categories)){
                                foreach ($categories as $category) {
                                    if($data['parent']==$category['id']){
                                        echo "<option value='".$category['id']."' selected = \"selected\">".$category['slug']."</option>\n";
                                    }else{
                                        echo "<option value='".$category['id']."'>".$category['slug']."</option>\n";
                                    }
                                }
                            }?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary addLentil" onclick="Category.save();" id="modal_save_button"><?php _e("Save","myfaqs"); ?></button>
                    <button type="button" class="btn btn-default close-edit-modal" data-dismiss="modal"><?php _e("Cancel","myfaqs"); ?></button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal -->
</div>
<!-- add 添加模态框  end -->
<script type="text/javascript">
    jQuery(document).ready(function ($) {
//        $("#editModal").modal();

    });
</script>