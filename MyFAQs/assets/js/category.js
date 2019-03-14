/**
 * Created by huimingdeng on 2019/3/14.
 */

function Category() {
    this.action = 'category';
    this.operation = 'init';
}

Category.prototype.init = function () {
    /*var _self = this;
    data = {
        "operation":_self.operation,
        "data":"test"
    };
    ajax_xhr = {
        type:'post',
        async:true,
        data:data,
        url:ajaxurl,
        dataType:'json',
        success:function (response) {
            console.dir(response);
        }
    }
    jQuery.ajax(ajax_xhr);*/
};

Category.prototype.add = function () {
    this.operation = 'add';
    var _self = this;
    data = {
        "operation":_self.operation,
        "data":jQuery("form").serialize()
    };
    console.log(data);
    ajax_xhr = {
        type:'post',
        async:true,
        data:data,
        url:ajaxurl,
        dataType:'json',
        success:function (response) {
            console.dir(response);
        }
    }
    jQuery.ajax(ajax_xhr);

};

var Category = new Category();

jQuery(document).ready(function () {
    Category.init();//400 bad
});