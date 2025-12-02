"use strict";
require('../layouts/app');
require('../../../plugins/tinymce/tinymce');
window.SavoMartImageInput = require('../layouts/components/image-input.js');

var SavoMartApplicationEdit = function () {

    var validateForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {

            'name': {
                validators: {
                    notEmpty: {
                        message: 'Name is required'
                    }
                }
            },
            'description': {
                validators: {
                    notEmpty: {
                        message: 'Description is required'
                    }
                }
            },
            'logo': {
                validators: {
                    extension: 'jpeg,jpg,png',
                    type: 'image/jpeg,image/png',
                }
            },
        };

        SavoMartJson.validators['ApplicationForm'] = FormValidation.formValidation(document.getElementById('ApplicationForm'), SavoMartJson.options.FormValidation);
    }

    const target = document.getElementById('application_status');
    const select = document.getElementById('application_status_select');
    const statusClasses = ['bg-success', 'bg-warning', 'bg-danger'];

    $(select).on('change', function (e) {
        const value = e.target.value;

        switch (value) {
            case "active":
                {
                    target.classList.remove(...statusClasses);
                    target.classList.add('bg-success');
                    break;
                }

            case "inactive":
                {
                    target.classList.remove(...statusClasses);
                    target.classList.add('bg-danger');
                    break;
                }

            default:
                break;
        }
    });
    return {
        init: function () {
            validateForm();
        }
    }
}();


SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartImageInput.init();
    SavoMartApplicationEdit.init();

});