"use strict";
require("../../admin/layouts/app");
require('../../../plugins/tinymce/tinymce');
require('../../../plugins/dropzone/dropzone');
window.SavoMartImageInput = require('../layouts/components/image-input.js');

var SavoMartPageAddPages = function () {
    var validateForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {
            'thumbnail': {
                validators: {
                    // notEmpty: {
                    //     message: 'Thumbnail picture is required'
                    // }
                    file: {
                        extension: 'jpg,jpeg,png',
                        type: 'image/jpeg,image/png',
                        message: 'The selected file is not valid'
                    },

                }
            },
            'name': {
                validators: {
                    notEmpty: {
                        message: 'Name is required'
                    }
                }
            },
            'slug': {
                validators: {
                    notEmpty: {
                        message: 'Slug is required'
                    }
                }
            },
            'file': {
                validators: {
                    // notEmpty: {
                    //     message: 'File is required'
                    // },
                    file: {
                        extension: 'jpeg,jpg,png,pdf,xlsx,xls,ppt,doc,docx,bmp,gif',
                        type: 'image/jpeg,image/png, application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel/application/vnd.ms-powerpoint/video/mp4',
                        message: 'The selected file is not valid'
                    },
                }
            },

        };
        SavoMartJson.validators['PagesForm'] = FormValidation.formValidation(document.getElementById('PagesForm'), SavoMartJson.options.FormValidation);
    }
    return {
        init: function () {
            validateForm();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartPageAddPages.init();
    SavoMartImageInput.init();
});