"use strict";

var customerAddressAdd = function () {
    var validateForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {
            'first_name': {
                validators: {
                    notEmpty: {
                        message: 'First Name is required'
                    }
                }
            },
            'last_name': {
                validators: {
                    notEmpty: {
                        message: 'Last Name is required'
                    }
                }
            },
            'street_address': {
                validators: {
                    notEmpty: {
                        message: 'Address is required'
                    }
                }
            },
            'country_id': {
                validators: {
                    notEmpty: {
                        message: 'Country is required'
                    }
                }
            },
            'state_id': {
                validators: {
                    notEmpty: {
                        message: 'State is required'
                    }
                }
            },
            'city': {
                validators: {
                    notEmpty: {
                        message: 'City is required'
                    }
                }
            },
            'postel_code': {
                validators: {
                    notEmpty: {
                        message: 'Pin code is required'
                    }
                }
            },
            'contact': {
                validators: {
                    notEmpty: {
                        message: 'Contact is required'
                    }
                }
            },

        };
        SavoMartJson.validators['customerAddAddressForm'] = FormValidation.formValidation(document.getElementById('customerAddAddressForm'), SavoMartJson.options.FormValidation);
    }

    var handleAddressFormSubmit = function () {
        var addressForm = document.getElementById('customerAddAddressForm');
        addressForm.addEventListener('submit', function (e) {
            e.preventDefault();
            SavoMartJson.validators['customerAddAddressForm'].validate().then(function (status) {
                var submitButton = document.querySelector('.fv-button-submit');
                if (status == 'Valid') {
                    submitButton.setAttribute('data-kt-indicator', 'on');
                    submitButton.disabled = true;
                    $("#customerAddAddressForm").ajaxSubmit({
                        dataType: 'JSON',
                        success: function (data) {
                            submitButton.removeAttribute('data-kt-indicator');
                            if (data.status == 1) {
                                $("#customerAddressModal").modal("hide");
                                $('#listCustomerAddress').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: "success",
                                    text: "Customer Address Added Successfully",
                                    buttons: {
                                        ok: "Ok",
                                    },
                                }).then((value) => {
                                    if (value == "ok") {
                                        $('#listCustomerAddress').DataTable().ajax.reload();
                                    }
                                });
                            } else {
                                submitButton.disabled = true;
                                swal("Failed!", data.message, "error");
                            }
                        }
                    });
                }
            });
        });
    }

    return {
        init: function () {
            SavoMartApp.createSelect2();
            $("#customerAddressModal").modal();
            $("#customerAddressModal").modal("show");
            $("#customerAddressModal").removeAttr("tabindex");
            validateForm();
            handleAddressFormSubmit();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    customerAddressAdd.init();
});