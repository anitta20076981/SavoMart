"use strict";
require('../layouts/app');
window.SavoMartImageInput = require('../layouts/components/image-input.js');

var SavoMartCategoryAdd = function () {
    var categoryValidateForm = function (e) {
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
        SavoMartJson.validators['categoryForm'] = FormValidation.formValidation(document.getElementById('categoryForm'), SavoMartJson.options.FormValidation);
    }
    const target = document.getElementById('category_status');
    const select = document.getElementById('category_status_select');
    const statusClasses = ['bg-success', 'bg-warning', 'bg-danger'];

    $('#parent_category').on('select2:select', function (e) {
        var data = e.params.data;
        console.log(data);
    });
    $("#parent_category").select2({
        allowClear: true,
    });
    $("#parent_category").on("select2:select", function (e) {
        var option = e.params.data;
        addCloseIcon(option);
    });

    $("#parent_category").on("select2:unselect", function (e) {
        var option = e.params.data;
        removeCloseIcon(option);
    });

    function addCloseIcon(option) {
        var selectedOption = $("#parent_category").find(`[value="${option.id}"]`);
        selectedOption.addClass("with-close-icon");
    }

    function removeCloseIcon(option) {
        var selectedOption = $("#parent_category").find(`[value="${option.id}"]`);
        selectedOption.removeClass("with-close-icon");
    }

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
            categoryValidateForm();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartImageInput.init();
    SavoMartCategoryAdd.init();

});

// $(document).ready(function() {
//     $("#parent_category").select2({
//         templateSelection: function(data, container) {

//             // Handle click on clear icon
//             $option.find(".clear-icon").click(function() {
//                 $("#parent_category").val(null).trigger("change");
//             });

//             return $option;
//         }
//     });
// });
