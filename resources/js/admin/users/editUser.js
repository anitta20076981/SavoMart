"use strict";
require('../layouts/app');
window.SavoMartImageInput = require('../layouts/components/image-input.js');

var SavoMartUsersEditUser = function () {
    var validateForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {
            // 'profile_picture': {
            //     validators: {
            //         notEmpty: {
            //             extension: 'jpeg,jpg,png,',
            //             message: 'Profile picture is not valid'
            //         }
            //     }
            // },
            'name': {
                validators: {
                    notEmpty: {
                        message: 'Name is required'
                    }
                }
            },
            // 'role_id': {
            //     validators: {
            //         notEmpty: {
            //             message: 'Role is required'
            //         }
            //     }
            // },
            'email': {
                validators: {
                    regexp: {
                        regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                        message: 'The value is not a valid email address',
                    },
                    notEmpty: {
                        message: 'Email address is required'
                    }
                }
            },
            'phone': {
                validators: {
                    notEmpty: {
                        message: 'Phone number is required'
                    },
                    regexp: {
                        regexp: /^[0-9]{10}$/, // Adjust the regular expression based on your phone number format
                        message: 'The value is not a valid phone number'
                    }
                }
            },
            'password': {
                validators: {
                    stringLength: {
                        min: 6,
                        message: 'The password length minimum of  6 characters'
                    }
                }
            },
            'password_confirm': {
                validators: {
                    identical: {
                        compare: function () {
                            return $('[name="password"]').val();
                        },
                        message: 'The password and its confirm are not the same'
                    }
                }
            }
        };
        SavoMartJson.validators['userForm'] = FormValidation.formValidation(document.getElementById('userForm'), SavoMartJson.options.FormValidation);
    }
    return {
        init: function () {
            validateForm();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartImageInput.init();
    SavoMartUsersEditUser.init();
});