"use strict";
require("../../admin/layouts/app");
require('../../../plugins/tinymce/tinymce');
require('../../../plugins/datatable/datatable');
require('../../../plugins/dropzone/dropzone');

window.SavoMartImageInput = require('../layouts/components/image-input.js');

var SavoMartOrderReturns = function () {

    var validateForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {
            'order_no': {
                validators: {
                    notEmpty: {
                        message: 'Order Number is required'
                    }
                }
            },
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
        SavoMartJson.validators['orderReturnForm'] = FormValidation.formValidation(document.getElementById('orderReturnForm'), SavoMartJson.options.FormValidation);
    }

    var initProductList = function () {
        SavoMartJson.options.datatables.ajax = {
            "url": $("#listOrderProducts").data('url'),
            "type": "POST",
            "data": function (data) {
                data._token = _token;
                data.order_id = $('#order_no').val();
            }
        };
        SavoMartJson.options.datatables.columns = [{
            "orderable": false,
            "data": function (row, type, val, meta) {
                if (row.return_status == "yes") {
                    return '<div class="form-check form-check-sm form-check-custom me-3">\
                        <input class="form-check-input" type="checkbox" disabled="disabled" data-initialized="false" data-kt-check="true" value="' + row.id + '" />\
                    </div>';
                } else {
                    return '<div class="form-check form-check-sm form-check-custom me-3">\
                        <input class="form-check-input" type="checkbox" data-initialized="false" data-kt-check="true" value="' + row.id + '" />\
                    </div>';
                }

            }
        },

        {
            "orderable": false,
            "data": function (row, type, val, meta) {
                if (row.return_status == "yes") {
                    return row.product + '<div class="form-check form-check-sm form-check-custom me-3 returned-status">\
                        Already returned\
                    </div>';
                } else {
                    return row.product;
                }

            }
        },

        { data: "order_quantity", name: "order_quantity", orderable: false, searchable: true },

        ];
        SavoMartJson.options.datatables.scrollY = "400px";
        SavoMartJson.options.datatables.scrollCollapse = true;
        SavoMartJson.options.datatables.order = [
            [2, "asc"]
        ];
        SavoMartJson.dataTables['listOrderProducts'] = $('#listOrderProducts').DataTable(SavoMartJson.options.datatables);
        SavoMartJson.dataTables['listOrderProducts'].on('draw', function () {
            SavoMartDatatable.handleDeleteRows();
            SavoMartMenu.createInstances();
            SavoMartOrderReturns.handleProductSelect();
        });
    }

    var handleSearchProductsTable = function () {
        const filterSearch = document.querySelector('[data-kt-order-products-filter="search"]');
        if (filterSearch && $(filterSearch).length) {
            filterSearch.addEventListener('keyup', function (e) {
                SavoMartJson.dataTables['listOrderProducts'].search(e.target.value).draw();
            });
        }
    }

    return {
        init: function () {
            validateForm();
            initProductList();
            handleSearchProductsTable();
        }
    }
}();

SavoMartOrderReturns.handleProductSelect = () => {
    const productTable = document.getElementById('listOrderProducts');
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

            SavoMartOrderReturns.detectEmpty();
            checkbox.setAttribute("data-initialized", "true");
        });
    });

    SavoMartOrderReturns.detectEmpty = () => {
        const message = target.querySelector('span');
        const products = target.querySelectorAll('[data-order-filter="product"]');

        if (products.length < 1) {
            message.classList.remove('d-none');

            totalPrice.innerText = '0.00';
        } else {
            message.classList.add('d-none');

            SavoMartOrderReturns.calculateTotal(products);
        }
    }

    SavoMartOrderReturns.calculateTotal = (products) => {
        let countPrice = 0;

        products.forEach(product => {
            const price = parseFloat(product.querySelector('[data-order-filter="price"]').innerText);
            const qty = parseFloat(product.querySelector('[data-product-qty="true"]').value);
            countPrice = parseFloat(qty * price);
            $("#total_price").val(countPrice);
        });

        totalPrice.innerText = countPrice.toFixed(2);
    }
}

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartImageInput.init();
    SavoMartOrderReturns.init();
});

$("#order_no").on("change", function (e) {
    SavoMartJson.dataTables['listOrderProducts'].ajax.reload();
});