
function SearchMenuOptions(){
	this.inited = false; 
	this.menu_name = '';
	this.operation = 'init';
}

SearchMenuOptions.prototype.init = function()
{
	this.inited = true;
	var _self = this;
	if(isTest){
		_self.showOne();
	}else{
		_self.show();
	}
};

SearchMenuOptions.prototype.show = function()
{

	this.operation = 'showmenu';
	var _self = this;
	data = { action: 'searchmenuoptions', operation: 'showmenu', menu_name: this.menu_name };
	var show_xhr = {
		type: 'post',
		async: true, // false,
		data: data,
		url: ajaxurl,
		dataType: 'json',
		success: function(response) {
			// jQuery("#list").html(response.msg.title).find("li").eq(0).addClass('active');
			// jQuery(".toogleBody").html(response.msg.body);
			// jQuery(".toogleBody").find(".contentBody").eq(0).show();
			jQuery("#listBody").html(response.msg);
		}
	};

	jQuery.ajax(show_xhr);
};

SearchMenuOptions.prototype.showOne = function()
{
	this.operation = 'showOne';
	var _self = this;
	data = { action: 'searchmenuoptions', operation: 'showOne', menu_name: this.menu_name };
	var show_xhr = {
		type: 'post',
		async: true, // false,
		data: data,
		url: ajaxurl,
		dataType: 'json',
		success: function(response) {
			// jQuery("#list").html(response.msg.title).find("li").eq(0).addClass('active');
			// jQuery(".toogleBody").html(response.msg.body);
			// jQuery(".toogleBody").find(".contentBody").eq(0).show();
			jQuery("#listBody").html(response.msg);
		}
	};

	jQuery.ajax(show_xhr);
};
// 添加弹窗
SearchMenuOptions.prototype.addOne = function()
{
	this.operation = 'addOnePage';
	var _self = this;
	data = { action: 'searchmenuoptions', operation: 'addOnePage'};
	var addOnePage_xhr = {
		type: 'post',
		async: true,
		data: data,
		url: ajaxurl,
		dataType: 'json',
		success: function(response){
			jQuery("#addModal").remove();
			jQuery('#listBody').append(response.msg);
			jQuery("#addModalLabel").text("Add Search Menu");
			jQuery("#addModal").modal();
		}
	};
	jQuery.ajax(addOnePage_xhr);
};

SearchMenuOptions.prototype.add = function()
{
	this.operation = 'addOne';
	var _self = this;
	var checkform = _self.checkForm();
	if(checkform==1){
		var data = jQuery("#form_add").serialize(); 
		var addOne_xhr = {
			type: 'post',
			async: true,
			data: data,
			url: ajaxurl,
			dataType: 'json',
			success: function(response){
				if(response.msg=='1'){
					jQuery("#addModal").modal('hide');
				}
				console.log(response);
			}
		};
		jQuery.ajax(addOne_xhr);
	}
	
};

// 修改弹窗
SearchMenuOptions.prototype.editOne = function(sn)
{
	this.operation = 'editOnePage';
	var _self = this;
	data = { action: 'searchmenuoptions', operation: _self.operation, sn: sn};
	var editOnePage_xhr = {
		type: 'post',
		async: true,
		data: data,
		url: ajaxurl,
		dataType: 'json',
		success: function(response){
			jQuery("#editModal").remove();
			jQuery('#listBody').append(response.msg);
			jQuery("#editModalLabel").text("Edit Search Menu");
			jQuery("#editModal").modal();
		}
	};
	jQuery.ajax(editOnePage_xhr);
};

SearchMenuOptions.prototype.edit = function()
{
	this.operation = 'editOne';
	var _self = this;
	var checkform = _self.checkForm();

	if(checkform==1){
		var data = jQuery("#form_edit").serialize(); 
		var editOne_xhr = {
			type: 'post',
			async: true,
			data: data,
			url: ajaxurl,
			dataType: 'json',
			success: function(response){
				if(response.msg=='1'){
					jQuery("#editModal").modal('hide');
					// jQuery(".dataTable").dataTable().ajax.reaload();
				}
				console.log(response);
			}
		};
		jQuery.ajax(editOne_xhr);
	}
};

SearchMenuOptions.prototype.delete = function()
{

};


SearchMenuOptions.prototype.toogleTable = function(obj,name)
{
	jQuery(obj).parent().addClass('active');
	jQuery(obj).parent().siblings('li').removeClass('active');
	this.menu_name = name;
	var _self = this;
	console.log(_self.menu_name);
	if(isTest){
		_self.showOne();
	}
	jQuery('#'+name).show();
	jQuery('#'+name).siblings('.contentBody').hide();
};

// 检查对象是否为空
SearchMenuOptions.prototype.checkIsNotNull = function (obj)
{
	var objname = jQuery(obj).attr('name');
	var objvalue = document.getElementById(objname).value.trim();
    if (objvalue.length==0) {//为空时
        var objCheck = false;
        jQuery("#"+objname+"_log").html('*required!');//提示文字
        jQuery("#"+objname+"_log").css({color:"#d44950"});//提示着色
        jQuery("#"+objname).css("borderColor","#d44950");//框着色
    } else {//不为空时
        var objCheck = true;
        jQuery("#"+objname+"_log").html('');//提示文字
        // jQuery("#desc_log").css({color:"#d44950"});//提示着色
        jQuery("#"+objname).css("borderColor","");//框着色
    }
    return objCheck;
};

// 检查对象不为空且为整型
SearchMenuOptions.prototype.checkisInteger = function(obj) {
	var objname = jQuery(obj).attr('name');
	var objvalue = document.getElementById(objname).value.trim();
	if (objvalue.length==0) {//为空时
        var objCheck = false;
        jQuery("#"+objname+"_log").html('*required!');//提示文字
        jQuery("#"+objname+"_log").css({color:"#d44950"});//提示着色
        jQuery("#"+objname).css("borderColor","#d44950");//框着色
    } else {
    	var reg_int = /^[1-9]\d*$/;
    	var is_int = objvalue.match(reg_int);
    	if(is_int==null){
    		var objCheck = false;
	        jQuery("#"+objname+"_log").html('*required integer! ');//提示文字
	        jQuery("#"+objname+"_log").css({color:"#d44950"});//提示着色
	        jQuery("#"+objname).css("borderColor","#d44950");//框着色
    	}else{
    		var objCheck = true;
       		jQuery("#"+objname+"_log").html('');//提示文字
            jQuery("#"+objname).css("borderColor","");//框着色
    	}
    }
    return objCheck;
};

SearchMenuOptions.prototype.checkForm = function()
{
	var _self = this;
	var checkMenuName = _self.checkIsNotNull(jQuery('#menu_name'));
	var checkClassifyName  = _self.checkIsNotNull(jQuery('#classify_name'));
	var checkClassifyOrder  = _self.checkisInteger(jQuery('#classify_order'));
	var checkItemName = _self.checkIsNotNull(jQuery('#item_name'));
	var checkItemDisplayName = _self.checkIsNotNull(jQuery('#item_display_name'));
	var checkItemValue = _self.checkIsNotNull(jQuery('#item_value'));
	var checkItemOrder = _self.checkisInteger(jQuery('#item_order'));
	var checkCompareMode = _self.checkIsNotNull(jQuery('#compare_mode'));
	if(checkMenuName&&checkClassifyName&&checkClassifyOrder&&checkItemName&&checkItemDisplayName&&checkItemValue&&checkItemOrder&&checkCompareMode){
		return true;
	}else{
		return false;
	}
};


var SearchMenuOptions = new SearchMenuOptions();

jQuery(document).ready(function() {
	SearchMenuOptions.init();
});