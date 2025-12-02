"use strict";

var CustomerAddressEdit = function () {
    var approveCustomerBrand = function () {
        $(".customer-brand-approve-button").on("click", function (e) {
            var elem = this;
            Swal.fire({
                text: "Are you sure you want to approve customer brand ?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, Approve!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-success",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                },
                inputPlaceholder: "Write something"
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: $(elem).data("status-change-url"),
                        type: "post",
                        data: {
                            _token: _token,
                            status: "approved",
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.status == 1) {
                                $("#customerBrandApproveModal").modal("hide");
                                $('#listCustomerBrands').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: "success",
                                    text: "Customer Brand Approved Successfully",
                                    buttons: {
                                        ok: "Ok",
                                    },
                                }).then((result) => {
                                    if (result == true) {
                                        $('#listCustomerBrands').DataTable().ajax.reload();
                                    }
                                });
                            } else {
                                Swal.fire("Failed!", data.message, "error");
                            }
                        }
                    });
                }
            });
        });
    }
    var rejectCustomerBrand = function () {
        $(".customer-brand-reject-button").on("click", function (e) {
            var elem = this;
            Swal.fire({
                text: "Are you sure you want to reject customer brand ?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, Reject!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                },
                inputPlaceholder: "Write something"
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: $(elem).data("status-change-url"),
                        type: "post",
                        data: {
                            _token: _token,
                            status: "rejected",
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.status == 1) {
                                $("#customerBrandApproveModal").modal("hide");
                                $('#listCustomerBrands').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: "success",
                                    text: "Customer Brand Rejected Successfully",
                                    buttons: {
                                        ok: "Ok",
                                    },
                                }).then((result) => {
                                    if (result == true) {
                                        $('#listCustomerBrands').DataTable().ajax.reload();
                                    }
                                });
                            } else {
                                Swal.fire("Failed!", data.message, "error");
                            }
                        }
                    });
                }
            });

        });
    }
    return {
        init: function () {
            $("#customerBrandApproveModal").modal();
            $("#customerBrandApproveModal").modal("show");
            $("#customerBrandApproveModal").removeAttr("tabindex");
            approveCustomerBrand();
            rejectCustomerBrand();


        }
    }
}();
SavoMartUtil.onDOMContentLoaded(function () {
    CustomerAddressEdit.init();
});
