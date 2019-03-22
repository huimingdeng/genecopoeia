/**
 * Created by huimingdeng on 2019/3/14.
 */

function Category() {
    this.myaction = 'category';
    this.operation = 'init';
    this.ajaxponit = 'myfaqs';
};

Category.prototype.init = function() {

};

Category.prototype.page = function(p) {

};

Category.prototype.add = function() {
    this.operation = 'add';
    var _self = this;
    var data = {
        "operation": _self.operation,
        "type": _self.myaction,
        "action": _self.ajaxponit,
        'data': jQuery('#AddNewC').serialize()
    };

    var show_xhr = {
        type: 'post',
        async: true, // false,
        data: data,
        url: ajaxurl,
        dataType: 'JSON',
        success: function(response) {
            if (response.status == 200) {
                // alert(response.msg);
                window.location.href = '/wp-admin/admin.php?page=categories';
            } else {
                alert(response.msg);
            }
        }
    };

    jQuery.ajax(show_xhr);

};
/**
 * Perform modifications or deletions based on the type.
 */
Category.prototype.save = function() {
    this.operation = 'edit';
    var _self = this;
    var data = {
        "operation": _self.operation,
        "type": _self.myaction,
        "action": _self.ajaxponit,
        'data': jQuery("#editForm").serialize()
    };
    // alert(JSON.stringify(data));
    var edit_xhr = {
        type: 'post',
        async: true,
        data: data,
        url: ajaxurl,
        dataType: 'JSON',
        success: function(response) {
            if (response.status == 200) {
                // alert(response.msg);
                window.location.href = '/wp-admin/admin.php?page=categories';
            } else {
                alert(response.msg);
            }
        },
        error: function(response) {
            alert(JSON.stringify(response));
        }
    };

    jQuery.ajax(edit_xhr);
};
/**
 * Get popup window
 * @param id
 */
Category.prototype.edit = function(id) {
    this.operation = 'popup';
    var _self = this;
    var data = {
        "operation": _self.operation,
        "type": _self.myaction,
        "action": _self.ajaxponit,
        'data': id
    };

    var edit_xhr = {
        type: 'post',
        async: true,
        data: data,
        url: ajaxurl,
        dataType: 'HTML',
        success: function(response) {
            jQuery('.wrap aside').html(response);
            jQuery("#editModal").modal();
        }
    };
    jQuery.ajax(edit_xhr);
};
/**
 * Deletion of a category
 * @param  Integer id  category's id
 * @return mixed
 */
Category.prototype.delete = function(id) {
    this.operation = 'delete';
    var _self = this;
    var data = {
        "operation": _self.operation,
        "type": _self.myaction,
        "action": _self.ajaxponit,
        'data': id
    };

    var bool = confirm("Delete the " + id + " ?");

    var del_xhr = {
        type: 'post',
        async: true,
        data: data,
        url: ajaxurl,
        dataType: 'JSON',
        success: function(response) {
            if (response.status == 200) {
                window.location = '/wp-admin/admin.php?page=categories';
            } else {
                alert(response.msg);
            }
        },
        error: function(response) {

        }
    };
    if (bool)
        jQuery.ajax(del_xhr);
}

var Category = new Category();

jQuery(document).ready(function() {
    Category.init();
});