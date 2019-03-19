<div class="wrap">
    <?php include "header.php"; ?>
    <h2><?php echo $title;?> &nbsp;&nbsp;<a href="javascript:void(0);" onclick="Faqs.addPopup()" class="btn btn-primary"><?php _e('Add New', 'myfaqs');?></a></h2>
    <content>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th><?php _e('Title','myfaqs');?></th>
                        <th><?php _e('Answer', 'myfaqs');?></th>
                        <th><?php _e('Category', 'myfaqs');?></th>
                        <th><?php _e('EditDate', 'myfaqs');?></th>
                        <th><?php _e('Action', 'myfaqs'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data)){
                            foreach($data as $faq){?>
                            <tr>
                                <td><?php echo $faq['title']; ?></td>
                                <td><?php echo $faq['answer']; ?></td>
                                <td><?php echo $faq['name'] ?></td>
                                <td><?php echo $faq['editdate']; ?></td>
                                <td><a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="Faqs.edit(<?php echo $faq['id']; ?>);"><?php _e('edit', 'myfaqs'); ?></a></td>
                            </tr>
                            <?php }
                        }else{
                            ?>
                            <tr>
                                <td colspan="4" align="center"><?php _e('No data', 'myfaqs');?>....</td>
                            </tr>
                            <?php
                        }?>
                    </tbody>
                </table>
            </div>
        </div>
    </content>
    <aside></aside>
</div>
