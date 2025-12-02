"use strict";
require('../layouts/app');
require('../../../plugins/tinymce/tinymce');
require('../../../plugins/tagify/tagify');
window.SavoMartImageInput = require('../layouts/components/image-input.js');

var SavoMartCustomerEdit = function () {
    var validateCustomerForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {
            'first_name': {
                validators: {
                    notEmpty: {
                        message: 'First Name is required'
                    }
                }
            },
            'last_name': {
                validators: {
                    notEmpty: {
                        message: 'Last Name is required'
                    }
                }
            },
            'email': {
                validators: {
                    emailAddress: {
                        message: 'The value is not a valid email address'
                    },
                    notEmpty: {
                        message: 'Email address is required'
                    }
                }
            },
            'phone': {
                validators: {
                    notEmpty: {
                        message: 'Phone is required'
                    }
                }
            },

        };
        SavoMartJson.validators['customerUpdateForm'] = FormValidation.formValidation(document.getElementById('customerUpdateForm'), SavoMartJson.options.FormValidation);
    }



    return {
        init: function () {
            validateCustomerForm();

        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartImageInput.init();
    SavoMartCustomerEdit.init();
});
