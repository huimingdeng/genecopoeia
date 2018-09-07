<div class="modal fade" id="addModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="add-modal-dialog">
        <div class="modal-content">
            <form id="form_add" class="form-inline" >
	            <div class="modal-header">
	                <div style="display: inline-block;">
	                    <h4 class="modal-title" id="addModalLabel" style="display: inline;margin-right: 20px;">
	                    </h4>
	                </div>
	                <div style="display: inline-block;float: right;margin-right:10px;">
	                    <button type="button" class="btn btn-primary addLentil" id="modal_save_button" onclick="SearchMenuOptions.add()">Save</button>
	                    <button type="button" class="btn btn-default close-edit-modal" data-dismiss="modal">Cancel</button>
	                </div>
	            </div>
            	<!-- modal-header -->
                <div class="modal-body">
                    <input type="hidden" name="action" value="searchmenuoptions">
                    <input type="hidden" name="operation" value="addOne">
                    <!-- sn -->
                    <div class="form-group " style="width:24.5%">
                    	<label for="sn">SN&nbsp;<span id="sn_log" class="sn_log"></span></label>
                    	<input type="text" class="form-control" readonly="readonly" id="sn" name="sn" data-toggle="tooltip" data-placement="top" title="Primary key self-increment.">
                    </div> 
                	<!-- Menu Name -->
                    <div class="form-group " style="width:74.5%">
                    	<label for="menu_name">Menu Name&nbsp;<span id="menu_name_log" class="valid_log"></span></label>
                    	<input type="text" class="form-control" id="menu_name" name="menu_name" data-toggle="tooltip" data-placement="top" onBlur="SearchMenuOptions.checkIsNotNull(this);" title="Required.">
                    </div>
					<!-- Classify Name -->
					<div class="form-group" style="width:74.5%">
						<label for="classify_name">Classify Name&nbsp;<span id="classify_name_log" class="valid_log"></span></label>
						<input type="text" id="classify_name" name="classify_name" class="form-control" data-toggle="tooltip" data-placement="top" onBlur="SearchMenuOptions.checkIsNotNull(this);" title="Required.">
					</div>
					<!-- Classify Order -->
					<div class="form-group" style="width:48.5%">
						<label for="classify_order">Classify Order&nbsp;<span id="classify_order_log" class="valid_log"></span></label>
						<input type="text" id="classify_order" name="classify_order" data-toggle="tooltip" data-placement="top" title="Required. Need to be a positive integer." onBlur="SearchMenuOptions.checkisInteger(this);" class="form-control">
					</div>
					<!-- Item Name -->
					<div class="form-group" style="width:48.5%">
						<label for="item_name">Item Name&nbsp;<span id="item_name_log" class="valid_log"></span></label><input type="text" id="item_name" name="item_name" data-toggle="tooltip" data-placement="top" title="Required. Item Name." onBlur="SearchMenuOptions.checkIsNotNull(this);" class="form-control">
					</div>
                    <!-- Item Display Name -->
                    <div class="form-group" style="width:48.5%">
						<label for="item_display_name">Item Display Name&nbsp;<span id="item_display_name_log" class="valid_log"></span></label>
						<input type="text" id="item_display_name" name="item_display_name" data-toggle="tooltip" data-placement="top" title="Required. Item Display Name" onBlur="SearchMenuOptions.checkIsNotNull(this);" class="form-control">
					</div>
					<!-- Item Value -->
					<div class="form-group" style="width:48.5%">
						<label for="item_value">Item Value&nbsp;<span id="item_value_log" class="valid_log"></span></label>
						<input type="text" id="item_value" name="item_value" data-toggle="tooltip" data-placement="top" onBlur="SearchMenuOptions.checkIsNotNull(this);" title="Required. Item Display Name." class="form-control">
					</div>
                    <!-- Item Order -->
                    <div class="form-group" style="width:48.5%">
						<label for="item_order">Item Order&nbsp;<span id="item_order_log" class="valid_log"></span></label>
						<input type="text" id="item_order" name="item_order" placeholder="" data-toggle="tooltip" data-placement="top" onBlur="SearchMenuOptions.checkisInteger(this);" title="Required. Item Order" class="form-control">
					</div>
                    
					<!-- Compare Mode -->
					<div class="form-group" style="width:48.5%">
						<label for="compare_mode">Compare Mode&nbsp;<span id="symbol_link_log" class="compare_mode_log"></span></label>
						<input type="text" id="compare_mode" name="compare_mode" data-toggle="tooltip" data-placement="top" title="Optional." class="form-control">
					</div>
                </div>
                <!-- modal-body -->
            </form>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal -->
</div>