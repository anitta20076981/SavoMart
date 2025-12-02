"use strict";
require('../layouts/app');
window.SavoMartImageInput = require('../layouts/components/image-input.js');
require('../../../plugins/dropzone/dropzone');
var SavoMartBannerAddBanner = function () {
    var validateForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {
            'name': {
                validators: {
                    notEmpty: {
                        message: 'Name is required'
                    }
                }
            },
            'banner_section_id': {
                validators: {
                    notEmpty: {
                        message: 'Banner section required'
                    }
                }
            },
        };
        SavoMartJson.validators['bannerForm'] = FormValidation.formValidation(document.getElementById('bannerForm'), SavoMartJson.options.FormValidation);
    }
    const target = document.getElementById('banner_status');
    const select = document.getElementById('banner_status_select');
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
    // Handle datepicker
    const datepicker = document.getElementById('banner_status_datepicker');

    const hideDatepicker = () => {
        datepicker.parentNode.classList.add('d-none');
    }
    return {
        init: function () {
            validateForm();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartImageInput.init();
    SavoMartBannerAddBanner.init();

});
