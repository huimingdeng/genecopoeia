
function SearchMenuOptions(){
	this.inited = false; 
	this.menu_name = '';
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
}

SearchMenuOptions.prototype.show = function()
{
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
}

SearchMenuOptions.prototype.showOne = function()
{
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
}

SearchMenuOptions.prototype.add = function()
{

}

SearchMenuOptions.prototype.edit = function()
{

}

SearchMenuOptions.prototype.delete = function()
{

}


SearchMenuOptions.prototype.toogleTable = function(obj,name){
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
}


var SearchMenuOptions = new SearchMenuOptions();

jQuery(document).ready(function() {
	SearchMenuOptions.init();
});