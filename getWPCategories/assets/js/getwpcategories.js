
function GetWPCategories()
{
	this.inited = false; 
	this.menu_name = '';
	this.sn = 0;
	this.operation = 'init';
}

GetWPCategories.prototype.init = function() 
{
	this.inited = true;
};


GetWPCategories.prototype.generated = function()
{
	var type = jQuery('input[name=type]:checked').val();
	var filename = jQuery('input[name=filename]').val();
	if ('' != filename) {
		data = { action: 'getwpcatoptions', operation: type, filename: filename };
		var show_xhr = {
			type: 'post',
			async: true, // false,
			data: data,
			url: ajaxurl,
			dataType: 'json',
			success: function(response) {
				if(response.status==200){
					jQuery('input[name=filename]').val('');
					alert(response.path);
				}else{
					alert(response.info);
				}
			}
		};

		jQuery.ajax(show_xhr);
	}
};

var GetWPCategories = new GetWPCategories();

jQuery(document).ready(function($) {
	GetWPCategories.init();
});