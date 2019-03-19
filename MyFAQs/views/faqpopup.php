<div class="modal fade" id="faqModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="add-modal-dialog">
        <div class="modal-content">
            <form method="post" id="addForm">
            	<div class="modal-header">
            		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="deleteModalLabel">
                    	<?php if(!empty($data)){ ?>
	                        <?php _e('Edit Faq',"myfaqs"); ?>
	                        <code><?php echo $data['name'];?></code>
                    	<?php }else{ ?>
                    		<?php _e('Add Faq', "myfaqs"); ?>
                    	<?php } ?>
                    </h4>
            	</div>
            	<div class="modal-body">
            		<div class="form-group">
            			<label for="title"><?php _e('Title', 'myfaqs'); ?></label>
            			<input type="text" class="form-control" id="title" name="title" value="<?php echo (!empty($data))?($data['title']):('');?>">
            		</div>
            		<div class="form-group">
            			<label for="answer"><?php _e('Answer', 'myfaqs'); ?></label>
            			<input type="text" class="form-control" id="answer" name="answer" value="<?php echo (!empty($data))?($data['answer']):('');?>">
            		</div>
            		<div class="form-group">
            			<label for="category"><?php _e("Category", 'myfaqs'); ?></label>
            			<select name="category" id="category" class="form-control">
            				<option value="0"><?php _e('Non', 'myfaqs'); ?></option>
            				<?php if(!empty($categories)){
                                foreach ($categories as $category) {
                                    if($data['category']==$category['id']){
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
                    <button type="button" class="btn btn-primary addLentil" onclick="Faqs.save();" id="modal_save_button"><?php _e("Save","myfaqs"); ?></button>
                    <button type="button" class="btn btn-default close-edit-modal" data-dismiss="modal"><?php _e("Cancel","myfaqs"); ?></button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal -->
</div>