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
            console.dir(response);
            console.dir(jQuery('#AddNewC').serialize());
        }
    };

    jQuery.ajax(show_xhr);

};

var Category = new Category();

jQuery(document).ready(function () {
    Category.init();//400 bad
});