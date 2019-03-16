<div class="wrap">
    <?php include "header.php"; ?>
    <h2><?php echo $title;?> &nbsp;&nbsp;<a href="#" class="btn btn-primary"><?php _e('Add New', 'myfaqs');?></a></h2>
    <content>
        <div class="row">
            <div class="col-md-10">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th><?php _e('Title','myfaqs');?></th>
                        <th><?php _e('Answer', 'myfaqs');?></th>
                        <th><?php _e('Category', 'myfaqs');?></th>
                        <th><?php _e('EditDate', 'myfaqs');?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data)){

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
</div>
