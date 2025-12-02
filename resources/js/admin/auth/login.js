"use strict";
require('../layouts/app');

var SavoMartlAuthLogin = function () {

    var validateForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {
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
            'password': {
                validators: {
                    notEmpty: {
                        message: 'The password is required'
                    }
                }
            }
        };
        SavoMartJson.validators['loginForm'] = FormValidation.formValidation(document.getElementById('loginForm'), SavoMartJson.options.FormValidation);
    }
    return {
        init: function () {
            validateForm();
        }
    };
}();

// On document ready
SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartlAuthLogin.init();
});