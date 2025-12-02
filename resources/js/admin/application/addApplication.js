"use strict";
require('../layouts/app');
require('../../../plugins/tinymce/tinymce');
window.SavoMartImageInput = require('../layouts/components/image-input.js');

var SavoMartApplicationsAddApplication = function () {

    var validateForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {
            'name': {
                validators: {
                    notEmpty: {
                        message: 'Name is required'
                    }
                }
            },
            // 'description': {
            //     validators: {
            //         notEmpty: {
            //             message: 'Description is required'
            //         }
            //     }
            // },
            'logo': {
                validators: {
                    extension: 'jpeg,jpg,png',
                    type: 'image/jpeg,image/png',
                }
            },
        };
        SavoMartJson.validators['applicationForm'] = FormValidation.formValidation(document.getElementById('applicationForm'), SavoMartJson.options.FormValidation);
    }


    return {
        init: function () {
            validateForm();
        }
    }
}();



SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartImageInput.init();
    SavoMartApplicationsAddApplication.init();

});
