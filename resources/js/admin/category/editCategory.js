"use strict";
require('../layouts/app');
window.SavoMartImageInput = require('../layouts/components/image-input.js');

var SavoMartCategoryEdit = function () {
    var validateForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {
            'name': {
                validators: {
                    notEmpty: {
                        message: 'English Name is required'
                    }
                }
            },
            'name_ar': {
                validators: {
                    notEmpty: {
                        message: 'Arabic Name is required'
                    }
                }
            },
        };
        SavoMartJson.validators['CategoryForm'] = FormValidation.formValidation(document.getElementById('CategoryForm'), SavoMartJson.options.FormValidation);
    }
    var statusEvent = function (e) {
        const target = document.getElementById('category_status');
        $("#category_status_select").change(function (e) {
            switch (e.target.value) {
                case "active":
                    {
                        target.classList.remove(...['bg-success', 'bg-warning', 'bg-danger']);
                        target.classList.add('bg-success');
                        break;
                    }
                case "inactive":
                    {
                        target.classList.remove(...['bg-success', 'bg-warning', 'bg-danger']);
                        target.classList.add('bg-danger');
                        break;
                    }
                default:
                    break;
            }
        });
    }
    return {
        init: function () {
            validateForm();
            statusEvent();

        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartCategoryEdit.init();
    SavoMartImageInput.init();

});
