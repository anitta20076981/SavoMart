"use strict";

var customerBrandAdd = function () {
    var validateForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {
            'brand_id': {
                validators: {
                    notEmpty: {
                        message: 'Brand is required'
                    }
                }
            },
        };
        SavoMartJson.validators['customerBrandAddForm'] = FormValidation.formValidation(document.getElementById('customerBrandAddForm'), SavoMartJson.options.FormValidation);
    }

    var handleAddressFormSubmit = function () {
        var addressForm = document.getElementById('customerBrandAddForm');
        addressForm.addEventListener('submit', function (e) {
            e.preventDefault();
            SavoMartJson.validators['customerBrandAddForm'].validate().then(function (status) {
                var submitButton = document.querySelector('.fv-button-submit');
                if (status == 'Valid') {
                    submitButton.setAttribute('data-kt-indicator', 'on');
                    submitButton.disabled = true;
                    $("#customerBrandAddForm").ajaxSubmit({
                        dataType: 'JSON',
                        success: function (data) {
                            submitButton.removeAttribute('data-kt-indicator');
                            if (data.status == 1) {
                                $("#customerBrandModal").modal("hide");
                                $('#listCustomerBrands').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: "success",
                                    text: "Customer Brand Added Successfully",
                                    buttons: {
                                        ok: "Ok",
                                    },
                                }).then((value) => {
                                    if (value == "ok") {
                                        $('#listCustomerBrands').DataTable().ajax.reload();
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
            $("#customerBrandModal").modal();
            $("#customerBrandModal").modal("show");
            $("#customerBrandModal").removeAttr("tabindex");
            validateForm();
            handleAddressFormSubmit();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    customerBrandAdd.init();
});
