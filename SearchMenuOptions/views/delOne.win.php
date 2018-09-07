<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="delete-modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="deleteModalLabel">
                    Delete the Menu Item
                </h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                Are you sure to delete the menu item
                <span class="code"><?php echo $data['sn'] ?></span>&nbsp;?
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="SearchMenuOptions.delete();">Delete</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"> Cancel </button>
            </div>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-fade -->
</div>