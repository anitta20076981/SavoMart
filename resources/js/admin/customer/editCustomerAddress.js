"use strict";

var CustomerAddressEdit = function () {
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
        SavoMartJson.validators['customerAddAddressEditForm'] = FormValidation.formValidation(document.getElementById('customerAddAddressEditForm'), SavoMartJson.options.FormValidation);
    }
    var handleAddressFormSubmit = function () {
        var addressForm = document.getElementById('customerAddAddressEditForm');
        addressForm.addEventListener('submit', function (e) {
            e.preventDefault();
            SavoMartJson.validators['customerAddAddressEditForm'].validate().then(function (status) {
                var submitButton = document.querySelector('.fv-button-submit');
                if (status == 'Valid') {
                    submitButton.setAttribute('data-kt-indicator', 'on');
                    submitButton.disabled = true;
                    $("#customerAddAddressEditForm").ajaxSubmit({
                        dataType: 'JSON',
                        success: function (data) {
                            submitButton.removeAttribute('data-kt-indicator');
                            if (data.status == 1) {
                                $("#customerAddressEditModal").modal("hide");
                                $('#listCustomerAddress').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: "success",
                                    text: "Customer Address Updated Successfully",
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
            $("#customerAddressEditModal").modal();
            $("#customerAddressEditModal").modal("show");
            $("#customerAddressEditModal").removeAttr("tabindex");
            validateForm();
            handleAddressFormSubmit();
        }
    }
}();
SavoMartUtil.onDOMContentLoaded(function () {
    CustomerAddressEdit.init();
});