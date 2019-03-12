<div class="wrap">
    <?php include "header.php"; ?>
    <h2><?php echo $title;?> &nbsp;&nbsp;<a href="#" class="btn btn-primary">Add New</a></h2>
    <content>
        <div class="row">
            <div class="col-md-10">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Answer</th>
                        <th>Category</th>
                        <th>EditDate</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data)){

                        }else{
                            ?>
                            <tr>
                                <td colspan="4" align="center">No data....</td>
                            </tr>
                            <?php
                        }?>
                    </tbody>
                </table>
            </div>
        </div>
    </content>
</div>
