/**
 * Created by huimingdeng on 2019/3/16.
 */

function Faqs() {
    this.myaction = 'faqs';
    this.operation = 'init';
    this.ajaxponit = 'myfaqs';
}

Faqs.prototype.init = function () {

};
/**
 * Show the add popover
 */
Faqs.prototype.addPopup = function(){
    this.operation = 'popup';
    var _self = this;
    var data = { "operation" : _self.operation, "type" : _self.myaction, "action" : _self.ajaxponit , "data" : ''};

    var add_xhr = {
        type : 'post',
        async : true,
        data : data,
        url : ajaxurl,
        dataType : 'html',
        success : function(response){
            // alert(response);
            jQuery('.wrap aside').html(response);
            jQuery("#faqModal").modal();
        },
        error : function(response){
            alert(JSON.stringify(response));
        }
    };

    jQuery.ajax(add_xhr);
};
/**
 * Add operation
 */
Faqs.prototype.add = function () {
    this.operation = 'add';
    var _self = this;
    var data = { "operation" : _self.operation, "type" : _self.myaction, "action" : _self.ajaxponit, 'data': jQuery('#addForm').serialize()};

    var add_xhr = {
        type: 'post',
        async: true, // false,
        data: data,
        url: ajaxurl,
        dataType: 'JSON',
        success: function(response) {
            if(response.status==200){
                window.location.href = '/wp-admin/admin.php?page=faqs';
            }else{
                alert(response.msg);
            }
        }
    };

    jQuery.ajax(add_xhr);
};
/**
 * Gets the modification information and displays it in the popover.
 * @param  Integer id 
 * @return html
 */
Faqs.prototype.edit = function (id) {
    this.operation = 'popup';

    var _self = this;
    var data = { "operation" : _self.operation, "type" : _self.myaction, "action" : _self.ajaxponit,'data' : id };

    var edit_xhr = {
        type: 'post',
        async: true,
        data: data,
        url: ajaxurl,
        dataType: 'html',
        success:function (response) {
            jQuery('.wrap aside').html(response);
            jQuery("#faqModal").modal();
        },
        error:function(response){
            alert(JSON.stringify(response));
        }
    };
    jQuery.ajax(edit_xhr);
};
/**
 * Perform modifications or deletions based on the type.
 * @return success|failure
 */
Faqs.prototype.save = function(){
    this.operation = 'edit';
    var _self = this;
    var data = { "operation" : _self.operation, "type" : _self.myaction, "action" : _self.ajaxponit, 'data' : jQuery("#addForm").serialize()};
    // alert(JSON.stringify(data));
    var edit_xhr = {
        type: 'post',
        async: true,
        data: data,
        url: ajaxurl,
        dataType: 'JSON',
        success: function (response){
            if(response.status==200){
                window.location.href = '/wp-admin/admin.php?page=faqs';
            }else{
                alert(response.msg);
            }
        },
        error: function(response){
            alert(JSON.stringify(response));
        }
    };

    jQuery.ajax(edit_xhr);
};
/**
 * [delete description]
 * @param  Integer id 
 * @return sucess|failure    message
 */
Faqs.prototype.delete = function (id) {
    this.operation = 'delete';
    var _self = this;
    var data = { "operation" : _self.operation, "type" : _self.myaction, "action" : _self.ajaxponit, 'data' : id};

    var bool = confirm("Delete the "+id+" ?");

    var del_xhr = {
        type: 'post',
        async: true,
        data: data,
        url: ajaxurl,
        dataType: 'JSON',
        success:function (response) {
            if(response.status==200){
                window.location = '/wp-admin/admin.php?page=faqs';
            }else{
                alert(response.msg);
            }
        },
        error:function (response) {
            alert(JSON.stringify(response));
        }
    };
    if(bool)
        jQuery.ajax(del_xhr);
};

var Faqs = new Faqs();

jQuery(document).ready(function () {
    Faqs.init();
});