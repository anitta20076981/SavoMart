"use strict";

const { inArray } = require('jquery');

require('../layouts/app');
require('../../../plugins/tinymce/tinymce');
window.SavoMartImageInput = require('../layouts/components/image-input.js');

var SavoMartContentEditContents = function () {
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
            'title': {
                validators: {
                    notEmpty: {
                        message: 'Title is required'
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
                    notEmpty: {
                        enabled: false,
                        message: 'The file required',
                    },

                    file: {
                        enabled: false,
                        extension: 'doc,pdf,xls,ppt,zip,mp3,gif,jpeg,jpg,png,webp,txt,m4v',
                        type: 'application/msword,application/pdf,application/vnd.ms-excel,application/vnd.ms-powerpoint,application/zip,audio/mpeg,audio/mp3,image/gif,image/jpeg,image/webp,text/plain,video/x-m4v',
                        message: 'The selected file is not valid'
                    },
                }
            },

        };
        SavoMartJson.validators['ContentsForm'] = FormValidation.formValidation(document.getElementById('ContentsForm'), SavoMartJson.options.FormValidation);
        document.getElementById('ContentsForm').querySelector('[name="file"]').addEventListener('change', function (e) {
            $('.invalid-feedback').html(' ');
            ($("#file_remove").val() != null) ? FormValidation.formValidation(document.getElementById('ContentsForm'), SavoMartJson.options.FormValidation).enableValidator('file') : FormValidation.formValidation(document.getElementById('ContentsForm'), SavoMartJson.options.FormValidation).disableValidator('file');
            FormValidation.formValidation(document.getElementById('ContentsForm'), SavoMartJson.options.FormValidation).revalidateField('file');
        });
        document.getElementById('ContentsForm').querySelector('#removeFile').addEventListener('click', function (e) {
            $('.invalid-feedback').html(' ');
            FormValidation.formValidation(document.getElementById('ContentsForm'), SavoMartJson.options.FormValidation).enableValidator('file');
            FormValidation.formValidation(document.getElementById('ContentsForm'), SavoMartJson.options.FormValidation).revalidateField('file');
        });

        const target = document.getElementById('contents_status');
        const select = document.getElementById('contents_status_select');
        const statusClasses = ['bg-success', 'bg-warning', 'bg-danger'];

        $(select).on('change', function (e) {
            const value = e.target.value;

            switch (value) {
                case "active":
                    {
                        target.classList.remove(...statusClasses);
                        target.classList.add('bg-success');
                        hideDatepicker();
                        break;
                    }

                case "inactive":
                    {
                        target.classList.remove(...statusClasses);
                        target.classList.add('bg-danger');
                        hideDatepicker();
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
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartImageInput.init();
    SavoMartContentEditContents.init();

});