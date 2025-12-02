"use strict";
require("../../admin/layouts/app");
require("../../../plugins/datatable/datatable");
window.SavoMartImageInput = require('../layouts/components/image-input.js');

var SavoMartOrderView = function () {

    return {
        init: function () {
            hangleDeliverBtnClick();
            hangleOrderCancelBtnClick();
            generatePaymentLink();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartImageInput.init();
    SavoMartOrderView.init();
});

var hangleDeliverBtnClick = function () {
    $(document).on("click", ".order-delivered-button", function () {
        Swal.fire({
            text: "Are you sure you want to deliver this item ?",
            icon: "success",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes!",
            cancelButtonText: "No, cancel",
            customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-active-light-primary"
            },
            inputPlaceholder: "Write something"
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: $('.order-delivered-button').data('delivered-url'),
                    type: "get",
                    data: {
                        _token: _token,
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.status == 1) {
                            Swal.fire({
                                icon: "success",
                                text: "Item Delivered Successfully",
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

var hangleOrderCancelBtnClick = function () {
    $(document).on("click", ".order-cancel-button", function () {
        Swal.fire({
            text: "Are you sure you want to cancel this order ?",
            icon: "success",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes!",
            cancelButtonText: "No, cancel",
            customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-active-light-primary"
            },
            inputPlaceholder: "Write something"
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: $('.order-cancel-button').data('cancel-url'),
                    type: "get",
                    data: {
                        _token: _token,
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.status == 1) {
                            Swal.fire({
                                icon: "success",
                                text: "Order Canceled Successfully",
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

var generatePaymentLink = function () {
    $(document).on("click", ".payment-link-generator", function () {
        SavoMartApp.showPageLoading();
        $.ajax({
            url: $('.payment-link-generator').data('url'),
            type: "get",
            data: {
                _token: _token,
            },
            dataType: "json",
            success: function (response) {
                SavoMartApp.hidePageLoading();
                if (response.status == 1) {
                    Swal.fire({
                        icon: "success",
                        title: "Payment link generated. Please copy for sending to customer.",
                        html: '<div class="d-flex">\
                        <input data-share-link-input id="share_link_input" type="text" class="form-control form-control-solid me-3 flex-grow-1" readonly value="' + response.data.short_url + '">\
                        <button data-share-link-button class="btn btn-light fw-bold flex-shrink-0 m-0" data-clipboard-target="#share_link_input">Copy</button>\
                        </div>',
                        buttons: {
                            ok: "Ok",
                        },
                    });
                    SavoMartApp.initClickCopying();
                }
            }
        });
    });
}

$(document).ready(function () {
    // Click event handler for plus button
    $(document).on("click", ".plus", function () {
        var quantityInput = $(this).siblings('.quantity-input');
        var orderQty = quantityInput.val();
        var availableQuantity = quantityInput.data('available-qty');
        var newQuantity = parseInt(orderQty) + 1;
        var orderStatus = quantityInput.data('order-status');
        if (orderStatus == "pending" && newQuantity <= parseFloat(availableQuantity)) {
            updateQuantity(quantityInput, newQuantity);
        } else {
            console.log("Quantity cannot be increased beyond available quantity.");
        }
    });


    // Click event handler for minus button
    $('.minus').click(function () {
        var quantityInput = $(this).siblings('.quantity-input');
        var orderQty = quantityInput.val();
        var availableQuantity = quantityInput.data('available-qty');
        var newQuantity = parseInt(orderQty) - 1;
        var orderStatus = quantityInput.data('order-status');
        if ((orderStatus == "pending") && (newQuantity > 0) && (newQuantity <= parseFloat(availableQuantity))) {
            updateQuantity(quantityInput, newQuantity);
        }
    });

    $('.quantity-input').on('input keyup', function (e) {
        var quantityInput = $(this);
        var newQuantity = parseInt(quantityInput.val());
        var availableQuantity = quantityInput.data('available-qty');
        var errorMessage = quantityInput.closest('td').find('.error-message');

        if (newQuantity > parseFloat(availableQuantity)) {
            errorMessage.text('This much of quantity not available at the moment');
        } else if (newQuantity <= 0 || newQuantity == "") {
            errorMessage.text('Enter valid quantity');
        } else {
            errorMessage.text('');
            updateQuantity(quantityInput, newQuantity);

        }
    });

    // Function to update quantity via AJAX
    function updateQuantity(quantityInput, newQuantity) {
        var url = quantityInput.data('url');
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: _token,
                quantity: newQuantity
            },
            success: function (response) {

                quantityInput.val(newQuantity);
                if (response.status == true) {
                    var price = quantityInput.closest('tr').find('.price');
                    var grandTotalElement = quantityInput.closest('tr').find('.total-price');
                    grandTotalElement.text((price.text() * newQuantity).toFixed(2));
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }
});