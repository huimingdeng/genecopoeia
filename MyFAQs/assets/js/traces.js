/**
 * Created by huimingdeng on 2019/3/16.
 */

function Traces() {
    this.myaction = 'traces';
    this.operation = 'init';
    this.ajaxponit = 'myfaqs';
    this.show = true;
}
// 用于查询当前类型的短代码数量并返回信息
Traces.prototype.init = function() {

};

Traces.prototype.list = function() {
    this.operation = 'popup';
    var _self = this;
    var data = {
        "operation": _self.operation,
        'type': _self.myaction,
        "action": _self.ajaxponit,
        'data': 1
    };

    var init_xhr = {
        type: 'post',
        async: true,
        url: ajaxurl,
        dataType: 'HTML',
        data: data,
        success: function(response) {
            jQuery('.metahtml').html(response);
        },
        error: function(response) { }
    };

    if(_self.show){
        jQuery.ajax(init_xhr);
        _self.show = false;
    }
};

Traces.prototype.page = function(p){
    this.operation = 'popup';
    var _self = this;
    var data = {
        "operation": _self.operation,
        'type': _self.myaction,
        "action": _self.ajaxponit,
        'data': p
    };

    var page_xhr = {
        type: 'post',
        async: true,
        url: ajaxurl,
        dataType: 'HTML',
        data: data,
        success: function(response) {
            jQuery('.metahtml').html(response);
        },
        error: function(response) { }
    };

    jQuery.ajax(page_xhr);

};

Traces.prototype.close = function() {
    var _self = this;
    _self.show = true;
    jQuery('.metahtml').html('');
};



Traces.prototype.edit = function() {
    this.operation = 'edit';

    var _self = this;
    var data = {
        "operation": _self.operation,
        "type": _self.myaction,
        "action": _self.ajaxponit,
        'data': 'edit_data'
    };

    var edit_xhr = {
        type: 'post',
        async: true,
        data: data,
        url: ajaxurl,
        dataType: 'JSON',
        success: function(response) {

        }
    };
    jQuery.ajax(edit_xhr);
};

Traces.prototype.delete = function() {
    this.operation = 'delete';
    var _self = this;
    var data = {
        "operation": _self.operation,
        "type": _self.myaction,
        "action": _self.ajaxponit,
        'data': 'deleted_id'
    };

    var del_xhr = {
        type: 'post',
        async: true,
        data: data,
        url: ajaxurl,
        dataType: 'JSON',
        success: function(response) {

        },
        error: function(response) {

        }
    };
    jQuery.ajax(del_xhr);
};

var Traces = new Traces();

jQuery(document).ready(function() {
    Traces.init();
});