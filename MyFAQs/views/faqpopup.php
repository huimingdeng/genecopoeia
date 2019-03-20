<style type="text/css">#add-modal-dialog{width:50%;}</style>
<div class="modal fade" id="faqModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="add-modal-dialog">
        <div class="modal-content">
            <form method="post" id="addForm">
            	<div class="modal-header">
            		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="deleteModalLabel">
                    	<?php if(!empty($data)){ ?>
	                        <?php _e('Edit Faq',"myfaqs"); ?>
	                        <code><?php echo mb_substr($data['title'], 0, 10, 'utf8').'...';?></code>
                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    	<?php }else{ ?>
                    		<?php _e('Add Faq', "myfaqs"); ?>
                    	<?php } ?>
                    </h4>
            	</div>
            	<div class="modal-body">
            		<div class="form-group">
            			<label for="title"><span class="text-danger">*</span>&nbsp;<?php _e('Title', 'myfaqs'); ?></label>
            			<input type="text" class="form-control" id="title" name="title" value="<?php echo (!empty($data))?($data['title']):('');?>">
            		</div>
            		<div class="form-group">
            			<label for="answer"><span class="text-danger">*</span>&nbsp;<?php _e('Answer', 'myfaqs'); ?></label>
                        <span class="character_entity_tip"><?php _e("Note: Please use <code class='form_code_1'>&amp;trade;</code> instead of <code class='form_code_2'>&trade;</code> and use <code class='form_code_1'>&amp;micro;</code> instead of <code class='form_code_2'>&micro;</code>, refer to the <a href='http://www.w3cschool.cn/htmltags/ref-entities.html' target='_blank'>Manual</a>.","myfaqs"); ?></span>
            			<textarea class="form-control" id="answer" name="answer" rows="3"><?php echo (!empty($data))?($data['answer']):('');?></textarea>
            		</div>
            		<div class="form-group">
            			<label for="category"><span class="text-danger">*</span>&nbsp;<?php _e("Category", 'myfaqs'); ?></label>
            			<select name="category" id="category" class="form-control">
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
                    <button type="button" class="btn btn-primary addLentil" onclick="<?php echo (!empty($data))?("Faqs.save();"):"Faqs.add();";?>" id="modal_save_button"><?php _e("Save","myfaqs"); ?></button>
                    <button type="button" class="btn btn-default close-edit-modal" data-dismiss="modal"><?php _e("Cancel","myfaqs"); ?></button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal -->
</div>