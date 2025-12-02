"use strict";
require("../../admin/layouts/app");
require("../../../plugins/tinymce/tinymce");
require("../../../plugins/dropzone/dropzone");
require("../../../plugins/jstree/jstree");
window.noUiSlider = require("nouislider/dist/nouislider.min.js");
window.SavoMartStepper = require("../../admin/layouts/components/stepper");
window.SavoMartImageInput = require("../../admin/layouts/components/image-input.js");
window.SavoMartDatatable = require("../../../plugins/datatable/datatable");

var SavoMartProductEdit = (function () {
    var validateForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {
            thumbnail: {
                validators: {
                    extension: "jpeg,jpg,png",
                    type: "image/jpeg,image/png",
                    message: "thumbnail picture is not valid",
                },
            },
            productName: {
                validators: {
                    notEmpty: {
                        message: "English Name is required",
                    },
                },
            },
            name_ar: {
                validators: {
                    notEmpty: {
                        message: "Arabic Name is required",
                    },
                },
            },
            sku: {
                validators: {
                    notEmpty: {
                        message: "Sku is required",
                    },
                },
            },
            price: {
                validators: {
                    numeric: {
                        message: "Enter Valid Price",
                        thousandsSeparator: "",
                        decimalSeparator: ".",
                    },
                    stringLength: {
                        min: 1,
                        max: 10,
                        message: "Price Must be 10 Digits",
                    },
                    notEmpty: {
                        message: "Price is required",
                    },
                    greaterThan: {
                        message: "Price must be Greater than 0",
                        min: 0,
                    },
                },
            },
            discounted_price: {
                validators: {
                    notEmpty: {
                        enabled: false,
                        message: "Discount Price is required",
                    },
                    greaterThan: {
                        enabled: false,
                        message: "Discount Price is required",
                        min: 0,
                        max: $("#pruduct-base-price").val(),
                    },
                },
            },
            special_price: {
                validators: {
                    notEmpty: {
                        enabled: false,
                        message: "Special Price is required",
                    },
                    greaterThan: {
                        enabled: false,
                        message: "Special Price is required",
                        min: 0,
                    },
                },
            },
            special_price_from: {
                validators: {
                    notEmpty: {
                        enabled: false,
                        message: "From Date is required",
                    },
                },
            },
            special_price_to: {
                validators: {
                    notEmpty: {
                        enabled: false,
                        message: "To Date is required",
                    },
                },
            },
            quantity: {
                validators: {
                    numeric: {
                        message: "Enter Valid Quantity",
                        thousandsSeparator: "",
                        decimalSeparator: ".",
                    },
                    stringLength: {
                        min: 1,
                        max: 10,
                        message: "Quantity Must be 10 Digits",
                    },
                    // notEmpty: {
                    //     message: 'Quantity is required'
                    // },
                    greaterThan: {
                        message: "Quantity must be Greater than 0",
                        min: 0,
                    },
                },
            },
        };

        // SavoMartJson.validators['editProductForm'] = FormValidation.formValidation(document.getElementById('editProductForm'), SavoMartJson.options.FormValidation);
        SavoMartJson.productFormValidation = SavoMartJson.options.FormValidation;
        SavoMartJson.validators["editProductForm"] = FormValidation.formValidation(
            document.getElementById("editProductForm"),
            SavoMartJson.options.FormValidation
        );
    };

    var discountPanel = function () {
        var discountOption = $(
            "input[name=discount_option]:checked",
            "#editProductForm"
        ).val();
        SavoMartProductEdit.handleDiscountOptionChange(discountOption);
        document
            .querySelectorAll('input[name="discount_option"]')
            .forEach(function (elem) {
                elem.addEventListener("change", function (e) {
                    $("#discounted_price").val(0);
                    $("#special_price").val(0);
                    discountOption = e.target.value;
                    SavoMartProductEdit.handleDiscountOptionChange(discountOption);
                });
            });

        $("#pruduct-base-price,#discounted_price,#discount_percentage").on({
            keyup: function () {
                specialPrice();
            },
            change: function () {
                specialPrice();
            },
        });
    };

    var handleProductionVariationRows = () => {
        $(document).on("click", "[data-variation-row-delete]", function (e) {
            $(this).closest("[data-variation-row]").remove();
        });
    };
    var handleStatus = function () {
        const target = document.getElementById("product_status");
        const select = document.getElementById("productStatus_select");
        const statusClasses = ["bg-success", "bg-warning", "bg-danger"];
        $(select).on("change", function (e) {
            const value = e.target.value;
            switch (value) {
                case "publish": {
                    target.classList.remove(...statusClasses);
                    target.classList.add("bg-success");
                    break;
                }
                case "draft": {
                    target.classList.remove(...statusClasses);
                    target.classList.add("bg-warning");
                    break;
                }
                case "suspend": {
                    target.classList.remove(...statusClasses);
                    target.classList.add("bg-danger");
                    break;
                }
                default:
                    break;
            }
        });
    };
    var handleUiSlider = function () {
        var total = parseFloat($("#special_price").val());
        var slider = $(".noUi-sm");
        slider[0].noUiSlider.on("update", function (values, handle) {
            var basePrice = $("#pruduct-base-price").val();
            var discount_option = $(
                "input[name=discount_option]:checked",
                "#editProductForm"
            ).val();
            if (discount_option == "percentage") {
                var percentage = $("#discount_percentage").val();
                var dicsountedPrice = (basePrice * percentage) / 100;
                total = basePrice - dicsountedPrice;
            }
            if (total < 0) {
                $("#discounted_price-error-div").html(
                    '<div id="discounted_price-error" class="red-text">Discount Price Must be less than Base price</div>'
                );
                $("#btnSubmit").attr("disabled", "disabled");
                return false;
            } else {
                $("#discounted_price-error-div").html("");
                $("#btnSubmit").removeAttr("disabled");
            }
            $("#special_price").val(total.toFixed(2));
        });
    };
    var handleProductAcceptOrReject = function () {
        $(".product-accept-button").on("click", function (e) {
            var elem = this;
            Swal.fire({
                text: "Are you sure you want to Publish Product ?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, Publish!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-success",
                    cancelButton: "btn fw-bold btn-active-light-primary",
                },
                inputPlaceholder: "Write something",
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: $(elem).data("url"),
                        type: "post",
                        data: {
                            _token: _token,
                            product_id: $("#product_id").val(),
                            action: "publish",
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.status == 1) {
                                localStorage.setItem("success", data.message);
                                location.reload();
                            } else {
                                Swal.fire("Failed!", data.message, "error");
                                location.reload();
                            }
                        },
                    });
                }
            });
        });
        $(".product-reject-button").on("click", function (e) {
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
                    cancelButton: "btn fw-bold btn-active-light-primary",
                },
                inputPlaceholder: "Write something",
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: $(elem).data("url"),
                        type: "post",
                        data: {
                            _token: _token,
                            product_id: $("#product_id").val(),
                            action: "reject",
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.status == 1) {
                                localStorage.setItem("success", data.message);
                                location.reload();
                            } else {
                                Swal.fire("Failed!", data.message, "error");
                                location.reload();
                            }
                        },
                    });
                }
            });
        });
    };

    var handleReturn = function () {
        $("#is_return").on("change", function () {
            var checkbox = $(this);
            var url = checkbox.data("url");
            var isChecked = checkbox.is(":checked");
            if (isChecked == true) {
                $(".returnPolicy").show();
            } else {
                $(".returnPolicy").hide();
            }
        });
    };

    var categoryTreeLoad = function () {

        var elem = document.getElementById("categoryTreeDiv");

        $.ajax({
            url: $(elem).data("tree-load-url"),
            type: "get",
            data: {
            },
            dataType: "json",
            success: function (data) {
                console.log(data.scripts);
                $("#categoryTreeDiv").html(data.html);
                SavoMartJsTree.createJstree();
            },
        });


    };

    return {
        init: function () {
            if ($("#returnType").val() == "no") {
                $(".returnPolicy").hide();
            }
            validateForm();
            discountPanel();
            handleStatus();
            handleUiSlider();
            handleProductionVariationRows();
            handleProductAcceptOrReject();
            handleReturn();
            categoryTreeLoad();
        },
    };
})();

SavoMartProductEdit.handleDiscountOptionChange = function (discountOption) {
    const t = document.getElementById("edit_product_discount_percentage"),
        o = document.getElementById("edit_product_discount_fixed"),
        q = document.getElementById("edit_product_special_price"),
        v = document.getElementById("edit_product_discount_date");
    switch (discountOption) {
        case "percentage":
            v.classList.remove("d-none"),
                q.classList.remove("d-none"),
                t.classList.remove("d-none"),
                o.classList.add("d-none");
            SavoMartJson.validators["editProductForm"].enableValidator(
                "discounted_price"
            );
            SavoMartJson.validators["editProductForm"].enableValidator(
                "special_price"
            );
            SavoMartJson.validators["editProductForm"].enableValidator(
                "special_price_from"
            );
            SavoMartJson.validators["editProductForm"].enableValidator(
                "special_price_to"
            );
            break;
        case "fixed_price":
            v.classList.remove("d-none"),
                q.classList.remove("d-none"),
                t.classList.add("d-none"),
                o.classList.remove("d-none");
            SavoMartJson.validators["editProductForm"].enableValidator(
                "discounted_price"
            );
            SavoMartJson.validators["editProductForm"].enableValidator(
                "special_price"
            );
            SavoMartJson.validators["editProductForm"].enableValidator(
                "special_price_from"
            );
            SavoMartJson.validators["editProductForm"].enableValidator(
                "special_price_to"
            );
            break;
        default:
            t.classList.add("d-none"),
                o.classList.add("d-none"),
                q.classList.add("d-none"),
                v.classList.add("d-none");
            SavoMartJson.validators["editProductForm"].disableValidator(
                "discounted_price"
            );
            SavoMartJson.validators["editProductForm"].disableValidator(
                "special_price"
            );
            SavoMartJson.validators["editProductForm"].disableValidator(
                "special_price_from"
            );
            SavoMartJson.validators["editProductForm"].disableValidator(
                "special_price_to"
            );
            break;
    }
};

var SavoMartProductAttribute = (function () {
    var handleAttributeSetChange = function (e) {
        const attributeSetSelect = document.getElementById(
            "product_attribute_set_select"
        );
        const productHiddenInput = document.getElementById("product_id");

        $(attributeSetSelect).change(function () {
            const setValue = attributeSetSelect.value;
            const productValue = productHiddenInput.value;
            $.ajax({
                method: "GET",
                url: $(attributeSetSelect).data("form-url"),
                data: {
                    setId: setValue,
                    productId: productValue,
                    isEdit: 1,
                },
                success: function (data) {
                    $("#attributes_container").html(data.html);
                    SavoMartForm.autoCompleteDisable();
                    SavoMartProductAttribute.initForm();
                },
            });
        });

        $(attributeSetSelect).change();
    };
    return {
        init: function () {
            handleAttributeSetChange();
        },
    };
})();

SavoMartProductAttribute.initForm = function () {
    SavoMartTinyMceInput.initEditor();
    SavoMartApp.createSelect2();
    SavoMartJson.validators["editProductForm"] = FormValidation.formValidation(
        document.getElementById("editProductForm"),
        SavoMartJson.options.FormValidation
    );
};

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartImageInput.init();
    SavoMartProductEdit.init();
    SavoMartProductAttribute.init();
});

function specialPrice() {
    var total = 0;
    var basePrice = $("#pruduct-base-price").val();
    var discount_option = $(
        "input[name=discount_option]:checked",
        "#editProductForm"
    ).val();
    if (discount_option == "fixed_price") {
        var dicsountedPrice = $("#discounted_price").val();
        total = basePrice - dicsountedPrice;
    } else if (discount_option == "percentage") {
        var percentage = $("#discount_percentage").val();
        var dicsountedPrice = (basePrice * percentage) / 100;
        total = basePrice - dicsountedPrice;
    }
    if (total < 0) {
        $("#discounted_price-error-div").html(
            '<div id="discounted_price-error" class="red-text">Discount Price Must be less than Base price</div>'
        );
        $("#btnSubmit").attr("disabled", "disabled");
        return false;
    } else {
        $("#discounted_price-error-div").html("");
        $("#special_price").val(total.toFixed(2));
        $("#btnSubmit").removeAttr("disabled");
    }
}
