"use strict";
require("../../admin/layouts/app");
require('../../../plugins/tinymce/tinymce');
require('../../../plugins/datatable/datatable');
require('../../../plugins/dropzone/dropzone');


window.SavoMartImageInput = require('../layouts/components/image-input.js');

var SavoMartOrderReturnEdit = function () {

    var validateForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {
            'reason': {
                validators: {
                    notEmpty: {
                        message: 'Reason is required'
                    }
                }
            },
            'location': {
                validators: {
                    notEmpty: {
                        message: 'Location is required'
                    }
                }
            },
            'products[][product_id]': {
                validators: {
                    notEmpty: {
                        message: 'Please select any one product to return'
                    }
                }
            },
        };
        SavoMartJson.validators['orderReturnEditForm'] = FormValidation.formValidation(document.getElementById('orderReturnEditForm'), SavoMartJson.options.FormValidation);
    }

    var initProductList = function () {

        SavoMartJson.options.datatables.ajax = {
            "url": $("#listProducts").data('url'),
            "type": "POST",
            "data": function (data) {
                data._token = _token;
                data.order_return_id = $('#order_return_id').val();
                data.order_id = $('#order_no').val();

            }
        };
        SavoMartJson.options.datatables.columns = [{
            "orderable": false,
            "data": function (row, type, val, meta) {
                if (row.return_status == "yes") {
                    return '<div class="form-check form-check-sm form-check-custom me-3">\
                        <input class="form-check-input" type="checkbox" data-initialized="false" data-kt-check="true" value="' + row.id + '" checked disabled/>\
                    </div>';
                }
            },
            name: "id"
        },
        { data: "product", name: "name", orderable: true, searchable: true },
        { data: "quantity", name: "quantity", orderable: false, searchable: true },
        ];
        SavoMartJson.options.datatables.scrollY = "400px";
        SavoMartJson.options.datatables.scrollCollapse = true;

        SavoMartJson.options.datatables.order = [
            [2, "asc"]
        ];
        SavoMartJson.dataTables['listProducts'] = $('#listProducts').DataTable(SavoMartJson.options.datatables);
        SavoMartJson.dataTables['listProducts'].on('draw', function () {
            SavoMartDatatable.handleDeleteRows();
            SavoMartMenu.createInstances();
            SavoMartOrderReturnEdit.handleProductSelect();
        });

    }

    var handleSearchProductsTable = function () {
        const filterSearch = document.querySelector('[data-kt-order-products-filter="search"]');
        if (filterSearch && $(filterSearch).length) {
            filterSearch.addEventListener('keyup', function (e) {
                SavoMartJson.dataTables['listProducts'].search(e.target.value).draw();
            });
        }
    }


    var handleShippingForm = function () {
        const element = document.getElementById('shipping-address-section');
        const checkbox = document.getElementById('same_as_billing');

        checkbox.addEventListener('change', e => {
            if (e.target.checked) {
                element.classList.add('d-none');
                $("#same_as_billing").val("1");
            } else {
                element.classList.remove('d-none');

            }
        });
    }

    var hangleRejectBtnClick = function () {
        $(document).on("click", ".order-return-reject-status-button", function () {
            var returnStatus = $('.order-return-reject-status-button').data('status-check');
            Swal.fire({
                text: "Are you sure your return is rejected ?",
                icon: "warning",
                html: 'Reason :<textarea id="rejectReason"  name="reason" class="swal2-input"></textarea>',
                buttonsStyling: false,
                confirmButtonText: "Yes, Rejct!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                },
                showCancelButton: true
            }).then(function (result) {
                if (result.value) {
                    var returnStatus = $('.order-return-reject-status-button').data('status-check');
                    var rejectReason = $('#rejectReason').val();
                    $.ajax({
                        url: $('.order-return-reject-status-button').data('status-change-url'),
                        type: "get",
                        data: {
                            _token: _token,
                            returnStatus: returnStatus,
                            rejectReason: rejectReason,
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.status == 1) {
                                Swal.fire({
                                    icon: "success",
                                    text: "Your Return Is Rejected",
                                    buttons: {
                                        ok: "Ok",
                                    },
                                }).then((data) => {
                                    if (data.value == true) {
                                        window.location.reload(true);
                                    }
                                });
                            }
                        }
                    });
                }
            });

        });
    }

    return {
        init: function () {
            validateForm();
            hangleConfirmBtnClick();
            hangleCompleteBtnClick();
            initProductList();
            handleSearchProductsTable();
            // handleShippingForm();
            hangleRejectBtnClick();
            SavoMartOrderReturnEdit.handleProductSelect();

        }
    }
}();

SavoMartOrderReturnEdit.handleProductSelect = () => {
    const productTable = document.getElementById('listProducts');
    const checkboxes = productTable.querySelectorAll('[type="checkbox"][data-initialized="false"]');
    const target = document.getElementById('order_selected_products');
    const totalPrice = document.getElementById('order_total_price');


    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', e => {
            const parent = checkbox.closest('tr');

            const product = parent.querySelector('[data-order-filter="product"]').cloneNode(true);

            const innerWrapper = document.createElement('div');

            const innerContent = product.innerHTML;

            const wrapperClassesAdd = ['col', 'my-2'];
            const wrapperClassesRemove = ['d-flex', 'align-items-center'];

            const additionalClasses = ['border', 'border-dashed', 'rounded', 'p-3', 'bg-body'];

            product.classList.remove(...wrapperClassesRemove);
            product.classList.add(...wrapperClassesAdd);

            product.innerHTML = '';

            innerWrapper.classList.add(...wrapperClassesRemove);
            innerWrapper.classList.add(...additionalClasses);

            innerWrapper.innerHTML = innerContent;

            product.appendChild(innerWrapper);

            const productId = product.getAttribute('data-product_id');
            const productValues = product.querySelector('[data-product-values="true"]');
            productValues.removeAttribute("hidden");
            productValues.querySelector('[data-product-id="true"]').setAttribute("name", "products[" + productId + "][product_id]");
            productValues.querySelector('[data-product-qty="true"]').setAttribute("name", "products[" + productId + "][quantity]");
            productValues.querySelector('[data-product-price="true"]').setAttribute("name", "products[" + productId + "][price]");
            productValues.querySelector('[data-order_item_id="true"]').setAttribute("name", "products[" + productId + "][order_item_id]");

            const productNodeId = product.getAttribute('data-order-product-node');

            if (e.target.checked) {
                target.appendChild(product);
            } else {
                const selectedProduct = target.querySelector('[data-order-product-node="' + productNodeId + '"]');
                if (selectedProduct) {
                    target.removeChild(selectedProduct);
                }
            }

            SavoMartOrderReturnEdit.detectEmpty();
            checkbox.setAttribute("data-initialized", "true");
        });
    });

    SavoMartOrderReturnEdit.detectEmpty = () => {
        const message = target.querySelector('span.product-empty');
        const products = target.querySelectorAll('[data-order-filter="product"]');

        if (products.length < 1) {
            message.classList.remove('d-none');

            totalPrice.innerText = '0.00';
        } else {
            message.classList.add('d-none');

            SavoMartOrderReturnEdit.calculateTotal(products);
        }
    }

    SavoMartOrderReturnEdit.calculateTotal = (products) => {
        let countPrice = 0;

        products.forEach(product => {
            const price = parseFloat(product.querySelector('[data-order-filter="price"]').innerText);

            countPrice = parseFloat(countPrice + price);
            $("#total_price").val(countPrice);
        });

        totalPrice.innerText = countPrice.toFixed(2);
    }
}

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartImageInput.init();
    SavoMartOrderReturnEdit.init();
});






$(document).on('click', '.new-address-btn', function (e) {
    var addressParent = $(this).parents(".address-section");
    addressParent.find(".address-div").show();
    $('.customer-address-radio').attr('checked', false);

});

$(document).on('change', '.orderQty', function (e) {
    var tot = 0;
    var grandtotal = 0;
    jQuery('#order_selected_products .added-product').each(function () {
        var productPrice = $(this).find('.product-price').val();
        var qty = $(this).find('.orderQty').val();
        grandtotal += productPrice * qty
    });
    $("#order_total_price").text(grandtotal);
    $("#total_price").val(grandtotal);
});


$("#order_no").on("change", function (e) {
    SavoMartJson.dataTables['listProducts'].ajax.reload();
});

var hangleConfirmBtnClick = function () {
    $(document).on("click", ".order-return-confirm-status-button", function () {
        Swal.fire({
            text: "Are you sure your return is confirmed ?",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes, change!",
            cancelButtonText: "No, cancel",
            customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-active-light-primary"
            },
            inputPlaceholder: "Write something"
        }).then(function (result) {
            if (result.value) {
                var returnStatus = $('.order-return-confirm-status-button').data('status-check');
                $.ajax({
                    url: $('.order-return-confirm-status-button').data('status-change-url'),
                    type: "get",
                    data: {
                        _token: _token,
                        returnStatus: returnStatus,
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.status == 1) {
                            Swal.fire({
                                icon: "success",
                                text: "Return Status Changed Successfully",
                                buttons: {
                                    ok: "Ok",
                                },
                            }).then((data) => {
                                if (data.value == true) {
                                    window.location.reload(true);
                                }
                            });
                        }
                    }
                });
            }
        });

    });
}
var hangleCompleteBtnClick = function () {
    $(document).on("click", ".order-return-complete-status-button", function () {
        var returnStatus = $('.order-return-complete-status-button').data('status-check');
        Swal.fire({
            text: "Are you sure your return is completed ?",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes, change!",
            cancelButtonText: "No, cancel",
            customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-active-light-primary"
            },
            inputPlaceholder: "Write something"
        }).then(function (result) {
            if (result.value) {
                var returnStatus = $('.order-return-complete-status-button').data('status-check');
                $.ajax({
                    url: $('.order-return-complete-status-button').data('status-change-url'),
                    type: "get",
                    data: {
                        _token: _token,
                        returnStatus: returnStatus,
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.status == 1) {
                            Swal.fire({
                                icon: "success",
                                text: "Your Return Is Completed",
                                buttons: {
                                    ok: "Ok",
                                },
                            }).then((data) => {
                                if (data.value == true) {
                                    window.location.reload(true);
                                }
                            });
                        }
                    }
                });
            }
        });

    });
}