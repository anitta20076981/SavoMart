"use strict";

var SavoMartProductReviewView = function () {

    var buttonHandle = function () {
        $(".review-accept-button").on("click", function (e) {

            if ($('#title').val() != '') {
                var elem = this;
                Swal.fire({
                    text: "Do you want to publish ?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, Publish!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-success",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    },
                    inputPlaceholder: "Write something"
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: $(elem).data("url"),
                            type: "get",
                            data: {
                                _token: _token,
                                review_id: $('#review_id').val(),
                                title: $('#title').val(),
                                action: "publish",
                            },
                            dataType: "json",
                            success: function (data) {
                                if (data.status == 1) {
                                    localStorage.setItem("success", "Review Published Successfully!");
                                    location.reload();
                                } else {
                                    Swal.fire("Failed!", data.message, "error");
                                    location.reload();
                                }

                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    text: "Please Add Title ",
                    icon: "warning",
                    showCancelButton: false,
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                    },
                    inputPlaceholder: "Write something"
                });
            }
        });
        $(".review-reject-button").on("click", function (e) {
            var elem = this;
            Swal.fire({
                text: "Are you sure you want to reject ?",
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
                        url: $(elem).data("url"),
                        type: "get",
                        data: {
                            _token: _token,
                            review_id: $('#review_id').val(),
                            action: "reject",
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.status == 1) {
                                localStorage.setItem("success", "Review Rejected Successfully!");
                                location.reload();
                            } else {
                                Swal.fire("Failed!", data.message, "error");
                                location.reload();
                            }
                        }
                    });
                }
            });
        });
    }
    return {
        init: function () {
            $("#reviewModal").modal();
            $("#reviewModal").modal("show");
            $("#reviewModal").removeAttr("tabindex");
            buttonHandle();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartProductReviewView.init();
});