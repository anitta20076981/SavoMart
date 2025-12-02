"use strict";
require('../layouts/app');
require('../../../plugins/tinymce/tinymce');
require('../../../plugins/formrepeater/formrepeater.js');
window.SavoMartImageInput = require('../layouts/components/image-input.js');


var SavoMartAttributesAddAttribute = function () {

    var handleWindowOnload = function (e) {
        $(window).on("load", function () {
            const statusIndicator = document.getElementById('attribute_status_indicator');
            const statusClasses = ['bg-success', 'bg-danger'];
            statusIndicator.classList.remove(...statusClasses);

            const optionContainer = document.getElementById('attribute_option_container');
            const inputTypeSelect = document.getElementById('attribute_input_type_select');
            const attributeOption = document.getElementById('has_options');
            const selectTypeValue = inputTypeSelect.value;
            switch (selectTypeValue) {
                case "dropdown":
                    {
                        optionContainer.classList.remove('d-none');
                        attributeOption.value = "1";
                        break;
                    }
                case "textswatch":
                    {
                        optionContainer.classList.remove('d-none');
                        attributeOption.value = "1";
                        $('.attribute_color_column').removeClass('d-none');
                        break;
                    }
                case "visualswatch":
                    {
                        optionContainer.classList.remove('d-none');
                        attributeOption.value = "1";
                        $('.attribute_color_column').removeClass('d-none');
                        break;
                    }
                default:
                    break;
            }

        });
    }

    var initFormRepeater = () => {
        SavoMartJson.options.formRepeater.show = function () {
            const inputTypeSelect = document.getElementById('attribute_input_type_select');
            if ($(inputTypeSelect).val() == 'visualswatch') {
                $(this).find('.attribute_color_column').removeClass('d-none');
            }
            if ($(inputTypeSelect).val() == 'dropdown') {
                $(this).find('.attribute_color_column').addClass('d-none');
            }
            $(this).slideDown();
        }
        $('#attribute-options-repeater').repeater(SavoMartJson.options.formRepeater);
    }

    var handleAddAttribueFormValidation = function (e) {
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
            'status': {
                validators: {
                    notEmpty: {
                        message: 'Status is required'
                    }
                }
            },
            'input_type': {
                validators: {
                    notEmpty: {
                        message: 'Input type is required'
                    }
                }
            }
        };
        SavoMartJson.validators['attributeForm'] = FormValidation.formValidation(document.getElementById('attributeForm'), SavoMartJson.options.FormValidation);
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

    var handleAttributeInputType = function (e) {
        const optionContainer = document.getElementById('attribute_option_container');
        const inputTypeSelect = document.getElementById('attribute_input_type_select');

        const attributeOption = document.getElementById('has_options');
        $(inputTypeSelect).on('change', function (e) {
            const value = e.target.value;

            switch (value) {
                case "textfield":
                    {
                        optionContainer.classList.add('d-none');
                        attributeOption.value = "0";
                        $('.attribute_color_column').addClass('d-none');
                        break;
                    }
                case "textarea":
                    {
                        optionContainer.classList.add('d-none');
                        attributeOption.value = "0";
                        $('.attribute_color_column').addClass('d-none');
                        break;
                    }
                case "texteditor":
                    {
                        optionContainer.classList.add('d-none');
                        attributeOption.value = "0";
                        $('.attribute_color_column').addClass('d-none');
                        break;
                    }
                case "date":
                    {
                        optionContainer.classList.add('d-none');
                        attributeOption.value = "0";
                        $('.attribute_color_column').addClass('d-none');
                        break;
                    }
                case "datetime":
                    {
                        optionContainer.classList.add('d-none');
                        attributeOption.value = "0";
                        $('.attribute_color_column').addClass('d-none');
                        break;
                    }
                case "yesno":
                    {
                        optionContainer.classList.add('d-none');
                        attributeOption.value = "0";
                        $('.attribute_color_column').addClass('d-none');
                        break;
                    }
                case "price":
                    {
                        optionContainer.classList.add('d-none');
                        attributeOption.value = "0";
                        $('.attribute_color_column').addClass('d-none');
                        break;
                    }
                case "dropdown":
                    {
                        optionContainer.classList.remove('d-none');
                        attributeOption.value = "1";
                        $('.attribute_color_column').addClass('d-none');
                        break;
                    }
                case "textswatch":
                    {
                        optionContainer.classList.remove('d-none');
                        attributeOption.value = "1";
                        $('.attribute_color_column').addClass('d-none');
                        break;
                    }
                case "visualswatch":
                    {
                        optionContainer.classList.remove('d-none');
                        attributeOption.value = "1";
                        $('.attribute_color_column').removeClass('d-none');
                        break;
                    }
                default:
                    break;
            }
        });
    }



    return {
        init: function () {
            initFormRepeater();
            handleWindowOnload();
            handleAddAttribueFormValidation();
            handleAttributeStatus();
            handleAttributeInputType();
        }
    }
}();





SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartImageInput.init();
    SavoMartAttributesAddAttribute.init();
});