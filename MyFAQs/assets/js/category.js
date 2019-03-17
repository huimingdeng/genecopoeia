/**
 * Created by huimingdeng on 2019/3/14.
 */

function Category() {
    this.myaction = 'category';
    this.operation = 'init';
    this.ajaxponit = 'myfaqs';
};

Category.prototype.init = function () {

};

Category.prototype.add = function () {
    this.operation = 'add';
    var _self = this;
    var data = { "operation":_self.operation, "type":_self.myaction, "action":_self.ajaxponit, 'data':jQuery('#AddNewC').serialize() };
    
    var show_xhr = {
        "type": 'post',
        "async": true, // false,
        "data": data,
        "url": ajaxurl,
        "dataType": 'JSON',
        "success": function(response) {
            if(response.status==200){
                alert(response.msg);
                window.location.href = '/wp-admin/admin.php?page=categories';
            }else{
                alert(response.msg);
            }
        }
    };

    jQuery.ajax(show_xhr);

};

Category.prototype.edit = function () {
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

Category.prototype.delete = function () {
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
}

var Category = new Category();

jQuery(document).ready(function () {
    Category.init();
});