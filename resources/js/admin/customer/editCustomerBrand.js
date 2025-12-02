"use strict";

var CustomerAddressEdit = function () {
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
        SavoMartJson.validators['customerBrandEditForm'] = FormValidation.formValidation(document.getElementById('customerBrandEditForm'), SavoMartJson.options.FormValidation);
    }

    var handleCustomerBrandEditFormSubmit = function () {
        var addressForm = document.getElementById('customerBrandEditForm');
        addressForm.addEventListener('submit', function (e) {
            e.preventDefault();
            SavoMartJson.validators['customerBrandEditForm'].validate().then(function (status) {
                var submitButton = document.querySelector('.fv-button-submit');
                if (status == 'Valid') {
                    submitButton.setAttribute('data-kt-indicator', 'on');
                    submitButton.disabled = true;
                    $("#customerBrandEditForm").ajaxSubmit({
                        dataType: 'JSON',
                        success: function (data) {
                            submitButton.removeAttribute('data-kt-indicator');
                            if (data.status == 1) {
                                $("#customerBrandEditModal").modal("hide");
                                $('#listCustomerBrands').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: "success",
                                    text: "Customer Brand Updated Successfully",
                                    buttons: {
                                        ok: "Ok",
                                    },
                                }).then((result) => {
                                    if (result.value == true) {
                                        console.log($('#listCustomerBrands').DataTable().ajax.reload());
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
            $("#customerBrandEditModal").modal();
            $("#customerBrandEditModal").modal("show");
            $("#customerBrandEditModal").removeAttr("tabindex");
            validateForm();
            handleCustomerBrandEditFormSubmit();
            SavoMartApp.handleFileRemove();
        }
    }
}();
SavoMartUtil.onDOMContentLoaded(function () {
    CustomerAddressEdit.init();
});