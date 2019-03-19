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

Faqs.prototype.add = function () {
    this.operation = 'add';
    var _self = this;
    var data = { "operation" : _self.operation, "type" : _self.myaction, "action" : _self.ajaxponit, 'data': 'add_data'};

    var add_xhr = {
        "type": 'post',
        "async": true, // false,
        "data": data,
        "url": ajaxurl,
        "dataType": 'JSON',
        "success": function(response) {

        }
    };

    jQuery.ajax(add_xhr);
};

Faqs.prototype.edit = function () {
    this.operation = 'edit';

    var _self = this;
    var data = { "operation" : _self.operation, "type" : _self.myaction, "action" : _self.ajaxponit,'data' : 'edit_data' };

    var edit_xhr = {
        type: 'post',
        async: true,
        data: data,
        url: ajaxurl,
        dataType: 'JSON',
        success:function (response) {

        }
    };
    jQuery.ajax(edit_xhr);
};

Faqs.prototype.delete = function () {
    this.operation = 'delete';
    var _self = this;
    var data = { "operation" : _self.operation, "type" : _self.myaction, "action" : _self.ajaxponit, 'data' : 'deleted_id'};

    var del_xhr = {
        type: 'post',
        async: true,
        data: data,
        url: ajaxurl,
        dataType: 'JSON',
        success:function (response) {

        },
        error:function (response) {

        }
    };
    jQuery.ajax(del_xhr);
};

var Faqs = new Faqs();

jQuery(document).ready(function () {
    Faqs.init();
});