"use strict";
require('../layouts/app');

var SavoMartAttributesAddAttributeSet = function () {
    var handleWindowOnload = function (e) {
        $(window).on("load", function () {
            const statusIndicator = document.getElementById('attribute_status_indicator');
            const statusClasses = ['bg-success', 'bg-danger'];
            statusIndicator.classList.remove(...statusClasses);
        });
    }

    var handleAddAttributeSetFormValidation = function (e) {
        SavoMartJson.options.FormValidation.fields = {
            'name': {
                validators: {
                    notEmpty: {
                        message: 'Name is required'
                    }
                }
            },
            'status': {
                validators: {
                    notEmpty: {
                        message: 'Status is required'
                    }
                }
            },
        };
        SavoMartJson.validators['attributeSetForm'] = FormValidation.formValidation(document.getElementById('attributeSetForm'), SavoMartJson.options.FormValidation);
    }

    var handleAttributeStatus = function (e) {
        const target = document.getElementById('attribute_status_indicator');
        const select = document.getElementById('attribute_status_select');
        const statusClasses = ['bg-success', 'bg-danger'];
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
    }


    return {
        init: function () {
            handleAddAttributeSetFormValidation();
            handleAttributeStatus();
            handleWindowOnload();
        }
    }
}();


SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartAttributesAddAttributeSet.init();
});