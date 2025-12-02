"use strict";

var SavoMartProductConfiguration = function () {

    var initAttributeDataTable = function (e) {
        SavoMartJson.options.datatables.ajax = {
            "url": $("#listAttributes").data('url'),
            "type": "POST",
            "data": function (data) {
                data._token = _token;
                data.status = $('#status').val();
                data.attribute_set_id = $('#selectedAttributeSetId').val();
                data.checked_attribute_ids = SavoMartProductConfiguration.attributeArr;
            }
        };
        SavoMartJson.options.datatables.columns = [{
            "orderable": false,
            "data": function (row, type, val, meta) {
                let checkedStatus = row.checked_status;
                return '<div class="form-check form-check-sm form-check-custom form-check-solid">\
                        <input ' + checkedStatus + ' class="form-check-input attribute_id" type="checkbox" data-attribute-list-id="' + row.id + '" data-initialized="false" data-kt-check="true" value="' + row.id + '" />\
                    </div>';
            },
            name: "id"
        },
        { data: "code", name: "code", orderable: true, searchable: true, className: "attribute_code" },
        { data: "name", name: "name", orderable: true, searchable: true, className: "attribute_name" },
        ];
        SavoMartJson.options.datatables.columnDefs = [{
            targets: 2,
            createdCell: function (td, cellData, rowData, row, col) {
                $(td).attr('data-name', rowData.name);
            }
        }];
        SavoMartJson.options.datatables.order = [
            [1, "asc"]
        ];
        SavoMartJson.dataTables['configListAttributes'] = $('#listAttributes').DataTable(SavoMartJson.options.datatables);
        SavoMartJson.dataTables['configListAttributes'].on('draw', function () {
            SavoMartMenu.createInstances();
            SavoMartProductConfiguration.handleAttributeSelection();
            SavoMartProductConfiguration.handleAttributeCheckBoxFunction();
        });
    }

    var initSummaryDataTable = function (e) {
        SavoMartJson.dataTables['listConfigurationSummary'] = $('#listConfigurationSummary').DataTable(SavoMartJson.options.datatables);
    }

    var validateConfigureForm = () => {

        SavoMartJson.options.FormValidation.fields = {
            'configure_attributes[]': {
                validators: {
                    notEmpty: {
                        message: 'Please select attribute.'
                    }
                }
            },
        }

        SavoMartStepper.validations[1] = FormValidation.formValidation(document.getElementById('product_configuration_form'), SavoMartJson.options.FormValidation)
    }

    var handleStepChange = () => {

        SavoMartStepper.stepperObj.on('kt.stepper.changed', function (stepper) {

            if (stepper.getCurrentStepIndex() === 2 && stepper.getPassedStepIndex() != 3) {
                const attributelist = document.getElementById('listAttributeContainer');
                const attributeIds = document.getElementById('configure_attributes').value;
                const attributeValuesUrl = attributelist.getAttribute('data-url');
                $.ajax({
                    method: "GET",
                    url: attributeValuesUrl,
                    data: {
                        attributeIds: attributeIds,
                    },
                    success: function (data) {
                        $("#attributeValuesContainer").html(data.html);
                        SavoMartProductConfiguration.handleAttributeOptionControl();
                    },
                });

            }

            if (stepper.getCurrentStepIndex() === 3) {
                const productSummeryContainer = document.getElementById('configurationSummaryContainer');
                const attributeIds = document.getElementById('configure_attributes').value;
                const productSummeryUrl = productSummeryContainer.getAttribute('data-url');

                const pruductName = document.getElementById('pruduct-name').value;
                const pruductSku = document.getElementById('product-sku').value;
                const pruductPrice = document.getElementById('pruduct-base-price').value;
                const pruductQty = document.getElementById('quantity').value;


                var productOptions = [];

                $.ajax({
                    method: "POST",
                    url: productSummeryUrl,
                    data: $("#product_configuration_form").serialize() + '&pruduct_name=' + pruductName + '&pruduct_qty=' + pruductQty + '&pruduct_sku=' + pruductSku + '&pruduct_price=' + pruductPrice,
                    success: function (data) {
                        $("#configurationSummaryContainer").html(data.html);
                    },
                });
            }

            $(document).on('click', '[data-kt-stepper-action="submit"]', function (e) {
                SavoMartDrawer.hideAll();

                if ($('.is-edit').attr('data-edit-product-section') == '1') {
                    var productCurrentVariation = document.getElementById('editVariationContainer');
                } else {
                    var productCurrentVariation = document.getElementById('addVariationContainer');
                }

                const attributeIds = document.getElementById('configure_attributes').value;
                const productVariationUrl = productCurrentVariation.getAttribute('data-url');

                const pruductName = document.getElementById('pruduct-name').value;
                const pruductSku = document.getElementById('product-sku').value;
                const pruductPrice = document.getElementById('pruduct-base-price').value;
                const pruductQty = document.getElementById('quantity').value;

                var productOptions = [];

                $.ajax({
                    method: "POST",
                    url: productVariationUrl,
                    data: $("#product_configuration_form").serialize() + '&pruduct_name=' + pruductName + '&pruduct_qty=' + pruductQty + '&pruduct_sku=' + pruductSku + '&pruduct_price=' + pruductPrice,
                    success: function (data) {
                        if ($('.is-edit').attr('data-edit-product-section') == '1') {
                            $("#available_product_varient_table").hide();
                            $("#editVariationContainer").html(data.html);
                            SavoMartProductConfiguration.handleVariationProducts();
                            SavoMartProductConfiguration.handleUniqueSkuValidation();
                        } else {

                            $("#addVariationContainer").html(data.html);
                            SavoMartProductConfiguration.handleVariationProducts();
                            SavoMartProductConfiguration.handleUniqueSkuValidation();
                        }
                    },
                });
            });
        });
    }

    var handleAttributeSearch = () => {
        const filterSearch = document.querySelector('[data-kt-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            SavoMartJson.dataTables['configListAttributes'].search(e.target.value).draw();
        });
    }

    var handleProductionVariationRows = () => {

        $(document).on('click', '[data-variation-row-delete]', function (e) {
            $(this).closest('[data-variation-row]').remove();
        });

        $(document).on('click', '[data-attribute-row-delete]', function (e) {
            $(this).closest('[data-attribute-container]').remove();
        });

        $(document).on('change', '#product_variations_checkbox', function (e) {
            if (this.checked) {
                $(this).val('publish');
            } else {
                $(this).val('draft');
            }
        });
    }

    return {
        init: function () {
            validateConfigureForm();
            SavoMartStepper.initStepper();
            handleStepChange();
            initAttributeDataTable();
            initSummaryDataTable();
            handleAttributeSearch();
            handleProductionVariationRows();
        }
    }
}();

SavoMartProductConfiguration.attributeArr = [];

SavoMartProductConfiguration.handleVariationProducts = function () {
    SavoMartImageInput.init();
    SavoMartApp.createSelect2();
    console.log(SavoMartJson.productFormValidation);
    if ($('.is-edit').attr('data-edit-product-section') == '1')
        SavoMartJson.validators['editProductForm'] = FormValidation.formValidation(document.getElementById('addProductForm'), SavoMartJson.productFormValidation);
    else
        SavoMartJson.validators['addProductForm'] = FormValidation.formValidation(document.getElementById('addProductForm'), SavoMartJson.productFormValidation);

}

SavoMartProductConfiguration.handleAttributeSelection = () => {
    const attributeTable = document.getElementById('listAttributes');
    const target = document.getElementById('listSelectedAttributes');
    const attributeInput = document.getElementById('configure_attributes');

    const checkboxes = attributeTable.querySelectorAll('.form-check-input[data-initialized="false"]');
    // SavoMartProductConfiguration.attributeArr = [];

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', e => {
            var closestRow = $(checkbox).closest('tr');
            var attributeName = closestRow.find('.attribute_name').data('name');
            var attributeId = closestRow.find('.attribute_id').val();

            var innerListClasses = ['nav-item', 'my-1', 'btn', 'btn-outline', 'btn-outline-dashed', 'btn-active-light-primary', 'me-5'];

            var innerList = document.createElement('li');
            innerList.classList.add(...innerListClasses);
            innerList.setAttribute("data-select-attribute-id", attributeId);
            innerList.innerHTML += attributeName;

            if (e.target.checked) {
                target.appendChild(innerList);
                SavoMartProductConfiguration.attributeArr.push(attributeId);
            } else {
                var selectedattribute = target.querySelector('[data-select-attribute-id="' + attributeId + '"]');
                if (selectedattribute) {
                    target.removeChild(selectedattribute);
                }
                SavoMartProductConfiguration.attributeArr = SavoMartProductConfiguration.attributeArr.filter(function (item) {
                    return item !== attributeId
                })
            }
            if (SavoMartProductConfiguration.attributeArr.length > 0) {
                var attributeArray = SavoMartProductConfiguration.attributeArr.toString();
                attributeInput.setAttribute("value", attributeArray);
            } else {
                attributeInput.setAttribute("value", '');
            }
        });
    });
}

SavoMartProductConfiguration.handleAttributeCheckBoxFunction = () => {
    if ($('.is-edit').attr('data-edit-product-section') == '1') {
        var inputVarientAttributeIds = $("#available_varient_attributes").val();
        var varientAttibuteIds = JSON.parse(inputVarientAttributeIds);

        $("#configure_attributes").val(varientAttibuteIds);

        $.each(varientAttibuteIds, function (index, value) {
            $('.form-check-input[data-attribute-list-id="' + value + '"]').attr('checked', true);
        });
    }
}

SavoMartProductConfiguration.handleAttributeOptionControl = () => {

    SavoMartJson.options.FormValidation.fields = {
        'attribute_options': {
            validators: {
                notEmpty: {
                    message: 'Please select attribute option(s).'
                }
            }
        },
    }
    SavoMartStepper.validations[2] = FormValidation.formValidation(document.getElementById('product_configuration_form'), SavoMartJson.options.FormValidation);

    $(document).on("click", "[data-attribute-selection]", function (e) {
        var attributeId = $(this).data('attribute-id');
        var selection = $(this).data('attribute-selection');
        if (selection == 'select-all') {
            var closestContainer = $(this).closest("[data-attribute-container='" + attributeId + "']");
            closestContainer.find(".btn[data-radio-name]").addClass('active');
            closestContainer.find(".option-check-box").attr('checked', true);
        } else {
            var closestContainer = $(this).closest("[data-attribute-container='" + attributeId + "']");
            closestContainer.find(".btn[data-radio-name]").removeClass('active');
            closestContainer.find(".option-check-box").attr('checked', false);
        }

        if ($("[data-attribute-container='" + attributeId + "'] :input.option-check-box:checkbox:checked").length) {
            closestContainer.attr('data-has-value', '1')
        } else {
            closestContainer.attr('data-has-value', '')
        }

        var attributeContainerLength = $("div[id^=attributeContainer]").length;

        if ($("[data-has-value='1']").length == attributeContainerLength) {
            $("#attribute_options").attr("value", "1");
        } else {
            $("#attribute_options").attr("value", "");
        }
    });

    const radioButtons = document.querySelectorAll('.btn[data-radio-name]');

    radioButtons.forEach((button, index) => {
        button.addEventListener('click', e => {

            if ($(button).find("input.option-check-box").prop("checked")) {
                $(button).removeClass("active");
                $(button).find("input.option-check-box").attr('checked', false);
            } else {
                $(button).addClass("active");
                $(button).find("input.option-check-box").attr('checked', true);
            }

            var attributeId = $(button).data('radio-attribute');
            var closestContainer = $(button).closest("[data-attribute-container='" + attributeId + "']");

            if ($("[data-attribute-container='" + attributeId + "'] :input.option-check-box:checkbox:checked").length) {
                closestContainer.attr('data-has-value', '1')
            } else {
                closestContainer.attr('data-has-value', '')
            }

            var attributeContainerLength = $("div[id^=attributeContainer]").length;

            if ($("[data-has-value='1']").length == attributeContainerLength) {
                $("#attribute_options").attr("value", "1");
            } else {
                $("#attribute_options").attr("value", "");
            }
            e.preventDefault();
        });
    });

    if ($('.is-edit').attr('data-edit-product-section') == '1') {
        var inputVarientOptionIds = $("#available_varient_attribute_options").val();
        var varientOptionIds = JSON.parse(inputVarientOptionIds);

        $.each(varientOptionIds, function (index, value) {

            var radio = $('.btn[data-radio-attribute-option="' + value + '"]');
            $('.option-check-box[data-check-attribute-option="' + value + '"]').attr('checked', true);
            radio.addClass("active");


            var attributeId = $(radio).data('radio-attribute');
            var closestContainer = $(radio).closest("[data-attribute-container='" + attributeId + "']");

            if ($("[data-attribute-container='" + attributeId + "'] :input.option-check-box:checkbox:checked").length) {
                closestContainer.attr('data-has-value', '1')
            } else {
                closestContainer.attr('data-has-value', '')
            }

            var attributeContainerLength = $("div[id^=attributeContainer]").length;

            if ($("[data-has-value='1']").length == attributeContainerLength) {
                $("#attribute_options").attr("value", "1");
            } else {
                $("#attribute_options").attr("value", "");
            }

        });


    }

}

SavoMartProductConfiguration.handleUniqueSkuValidation = () => {

    const varients = document.querySelectorAll('#addVariationContainer .varient-sku');

    varients.forEach((varient) => {

        // onload ajax call
        const inputValue = varient.value;
        SavoMartProductConfiguration.uniqueSkuAjaxCall(inputValue, varient);

        // onchange ajax call
        varient.addEventListener('keyup', e => {
            SavoMartProductConfiguration.uniqueSkuAjaxCall(varient.value, varient);
            e.preventDefault();
        });
    });

}


SavoMartProductConfiguration.uniqueSkuAjaxCall = (inputValue, varient) => {

    // Get Unique form url
    const formUrl = $('#product-sku').data('sku-unique-url');

    // Get the form element
    if ($('.is-edit').attr('data-edit-product-section') == '1') {
        var form = document.getElementById('editProductForm');
    } else {
        var form = document.getElementById('addProductForm');
    }

    // Construct a new FormData object
    const formData = new FormData(form);
    formData.append('varient_sku', varient.value);

    $.ajax({
        url: formUrl,
        type: "POST",
        dataType: "json",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.valid == false) {
                $(varient).closest(".fv-plugins-icon-container").find('.fv-plugins-message-container').text(data.message);
                var sumbitBtn = $('#btnSubmit');
                sumbitBtn.prop('disabled', true);

            } else {
                $(varient).closest(".fv-plugins-icon-container").find('.fv-plugins-message-container').text("");
                var sumbitBtn = $('#btnSubmit');
                sumbitBtn.prop('disabled', false);
            }
        }
    });
}


SavoMartUtil.onDOMContentLoaded(function () {
    ;
    SavoMartProductConfiguration.init();
});