"use strict";
require("../../admin/layouts/app");

require("../../../plugins/tinymce/tinymce");
require("../../../plugins/dropzone/dropzone");
require("../../../plugins/jstree/jstree");
window.noUiSlider = require("nouislider/dist/nouislider.min.js");
window.SavoMartStepper = require("../../admin/layouts/components/stepper");
window.SavoMartDatatable = require("../../../plugins/datatable/datatable");
window.SavoMartImageInput = require("../../admin/layouts/components/image-input.js");


var SavoMartOrderAdd = function () {

    var validateForm = function (e) {

        $(document).on("click", ".saveOrder", function () {
            var orderItemsTableOptions = {
                paging: false,
                ordering: false,
                info: false,
                search: false,
                sDom: "ltipr",
            };
            var uiTable = $("#orderItems").dataTable(orderItemsTableOptions);

            var rowCount = uiTable.fnGetData().length;

            if (rowCount == 0) {
                $("#add_item-error-div").html(
                    '<small class="form-text red-text text-danger">Add at least 1 item</small>'
                );
                return false;
            } else {
                SavoMartJson.options.FormValidation.fields = {
                    'customer_id': {
                        validators: {
                            notEmpty: {
                                message: 'Customer is required'
                            }
                        }
                    },
                    'shipping_address_address_1': {
                        validators: {
                            notEmpty: {
                                message: 'Address is required'
                            }
                        }
                    },
                    'street': {
                        validators: {
                            notEmpty: {
                                message: 'Street is required'
                            }
                        }
                    },
                    'shipping_address_contact': {
                        validators: {
                            notEmpty: {
                                message: 'Number is required'
                            }
                        }
                    },
                };

                SavoMartJson.validators['orderForm'] = FormValidation.formValidation(document.getElementById('orderForm'), SavoMartJson.options.FormValidation);
            }
        })

    }

    var handleCustomerChange = function () {
        $("#customer_id").on("change", function (e) {
            var customerId = $('#customer_id').val();
            var url = $('#customer_id').data('customer-details-url');
            $.ajax({
                url: url,
                type: "get",
                data: {
                    customer_id: customerId,
                },
                dataType: "json",
                success: function (data) {
                    $('.billing-address-section .customerAddressCard').html(data.html.billing_address);
                    $('.shipping-address-section .customerAddressCard').html(data.html.shipping_address);
                    $(".address-div").hide();
                    $('.customer-address-radio').change();
                }
            });
        });

        $("#customer_id").on('select2:select', function (e) {
            var data = e.params.data;
            $(".customer-card").html(data.card);
        });
    }

    var handleSelect = function () {
        $("#customer_id").on("change", function (e) {
            var customerId = $('#customer_id').val();
            var url = $('#customer_id').data('customer-details-url');
            $.ajax({
                url: url,
                type: "get",
                data: {
                    customer_id: customerId,
                },
                dataType: "json",
                success: function (data) {
                    $('.billing-address-section .customerAddressCard').html(data.html.billing_address);
                    $('.shipping-address-section .customerAddressCard').html(data.html.shipping_address);
                    $(".address-div").hide();
                    $('.customer-address-radio').change();
                }
            });
        });

        $("#customer_id").on('select2:select', function (e) {
            var data = e.params.data;
            $(".customer-card").html(data.card);
        });
    }

    var handleProductAdd = function () {

        $(".addProductBtn").click(function () {

            var fieldsToValidate = [
                { fieldId: '#category_id', errorDivId: '#category_id-error-div', errorMessage: 'Please select category' },
                { fieldId: '#product_id', errorDivId: '#product_id-error-div', errorMessage: 'Please select product' },
                { fieldId: '#quantity', errorDivId: '#quantity-error-div', errorMessage: 'Please enter quantity' }
            ];

            var $validate = true;

            for (var i = 0; i < fieldsToValidate.length; i++) {
                var fieldInfo = fieldsToValidate[i];
                var field = $(fieldInfo.fieldId);
                var errorDiv = $(fieldInfo.errorDivId);

                if (!field.val()) {
                    errorDiv.html(fieldInfo.errorMessage);
                    $validate = false;
                } else {
                    errorDiv.html("");
                }
            }

            if ($validate === false) {
                return false;
            }
            $.ajax({
                url: $(".addProductBtn").data('product-details-url'),
                type: "GET",
                data: {
                    product_id: $("#product_id option:selected").val(),
                },
                success: function (response) {
                    if (response.data.stock_status == "outofstock") {
                        $("#quantity-error-div").html("Check stock status.");
                    } else if (parseInt($("#quantity").val()) > response.data.quantity) {
                        $("#quantity-error-div").html("Requested quantity is not available.");
                    } else {
                        var oTable = $('#orderItems').dataTable();
                        var rowcount = oTable.fnGetData().length;

                        // Continue with your logic if validation passes

                        $("#orderItems")
                            .DataTable()
                            .row.add([
                                '<input type="hidden" class="product_id" value="' + $("#product_id option:selected").val() + '" name="products[' + rowcount + '][product_id]">' + $("#product_id option:selected").text(),
                                '<input type="hidden" class="category_id  product-select-' + rowcount + '" value="' + $("#category_id option:selected").val() + '" name="products[' + rowcount + '][category_id]">' + $("#category_id option:selected").text(),
                                '<input type="hidden" class="quantity  product-select-' + rowcount + '" value="' + $("#quantity").val() + '" name="products[' + rowcount + '][quantity]">' + $("#quantity").val(),
                                '<div class="deleteRow justify-content-between mr-7 btn btn-danger"> <span class="delete-row-btn"><i class="material-icons">clear</i></span></div>',
                            ])
                            .draw();

                        $("#product_id").val("").change();
                        $("#category_id").val("").change();
                        $("#quantity").val("");
                        $("#add_item-error-div").html("");
                    }

                },
                error: function () {
                    $("#quantity-error-div").html("An error occurred while checking the quantity.");
                }
            });

        });

        $("#orderItems").on("click", ".deleteRow", function () {

            $("#orderItems")
                .DataTable()
                .row($(this).closest("tr"))
                .remove()
                .draw(false);
            var oTable = $('#orderItems').dataTable();
            var rowCount = oTable.fnGetData().length;
            if (rowCount) {
                rowcount = rowCount
            }

        });
    }

    return {
        init: function () {
            validateForm();
            handleCustomerChange();
            handleSelect();
            handleProductAdd();
        }
    }
}();


SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartImageInput.init();
    SavoMartOrderAdd.init();
});