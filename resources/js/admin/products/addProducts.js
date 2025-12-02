"use strict";
require("../../admin/layouts/app");

require("../../../plugins/tinymce/tinymce");
require("../../../plugins/dropzone/dropzone");
require("../../../plugins/jstree/jstree");
window.noUiSlider = require("nouislider/dist/nouislider.min.js");
window.SavoMartStepper = require("../../admin/layouts/components/stepper");
window.SavoMartDatatable = require("../../../plugins/datatable/datatable");
window.SavoMartImageInput = require("../../admin/layouts/components/image-input.js");

var SavoMartProductAdd = (function () {
    var validateForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {
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
        };
        SavoMartJson.validators["drawerPreValidation"] =
            FormValidation.formValidation(
                document.getElementById("addProductForm"),
                SavoMartJson.options.FormValidation
            );

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
                        message: "Name is required",
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
                    notEmpty: {
                        message: "Quantity is required",
                    },
                    greaterThan: {
                        message: "Quantity must be Greater than 0",
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
                        min: 1,
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
                        message: "To Date required",
                    },
                },
            },

            // 'categories': {
            //     callback: {
            //         message: 'Please Select A Category',
            //         callback: function(value, validator, $field) {
            //             if ($("input[name='categories[]']:checked").length == 0) {
            //                 return false;
            //             }
            //             return true;
            //         }
            //     }
            // },
        };

        SavoMartJson.productFormValidation = SavoMartJson.options.FormValidation;
        SavoMartJson.validators["addProductForm"] = FormValidation.formValidation(
            document.getElementById("addProductForm"),
            SavoMartJson.options.FormValidation
        );
    };

    var discountPanel = function () {
        var o, a;
        (() => {
            const e = document.querySelectorAll(
                'input[name="discount_option"]'
            ),
                t = document.getElementById("add_product_discount_percentage"),
                o = document.getElementById("add_product_discount_fixed"),
                q = document.getElementById("add_product_special_price"),
                v = document.getElementById("add_product_discount_date");
            e.forEach((e) => {
                e.addEventListener("change", (e) => {
                    $("#discounted_price").val(0);
                    $("#special_price").val(0);
                    switch (e.target.value) {
                        case "percentage":
                            v.classList.remove("d-none"),
                                q.classList.remove("d-none"),
                                t.classList.remove("d-none"),
                                o.classList.add("d-none");
                            SavoMartJson.validators[
                                "addProductForm"
                            ].enableValidator("discounted_price");
                            SavoMartJson.validators[
                                "addProductForm"
                            ].enableValidator("special_price");
                            SavoMartJson.validators[
                                "addProductForm"
                            ].enableValidator("special_price_from");
                            SavoMartJson.validators[
                                "addProductForm"
                            ].enableValidator("special_price_to");
                            break;
                        case "fixed_price":
                            v.classList.remove("d-none"),
                                q.classList.remove("d-none"),
                                t.classList.add("d-none"),
                                o.classList.remove("d-none");
                            SavoMartJson.validators[
                                "addProductForm"
                            ].enableValidator("discounted_price");
                            SavoMartJson.validators[
                                "addProductForm"
                            ].enableValidator("special_price");
                            SavoMartJson.validators[
                                "addProductForm"
                            ].enableValidator("special_price_from");
                            SavoMartJson.validators[
                                "addProductForm"
                            ].enableValidator("special_price_to");
                            break;
                        default:
                            t.classList.add("d-none"),
                                o.classList.add("d-none"),
                                q.classList.add("d-none"),
                                v.classList.add("d-none");
                            SavoMartJson.validators[
                                "addProductForm"
                            ].disableValidator("discounted_price");
                            SavoMartJson.validators[
                                "addProductForm"
                            ].disableValidator("special_price");
                            SavoMartJson.validators[
                                "addProductForm"
                            ].disableValidator("special_price_from");
                            SavoMartJson.validators[
                                "addProductForm"
                            ].disableValidator("special_price_to");
                            break;
                    }
                });
            });
        })();

        $("#pruduct-base-price,#discounted_price,#discount_percentage").on({
            keyup: function () {
                specialPrice();
            },
            change: function () {
                specialPrice();
            },
        });
    };

    var handleUiSlider = function () {
        var total = 0;
        var $slider = $(".noUi-sm");
        $slider[0].noUiSlider.on("update", function (values, handle) {
            var basePrice = $("#pruduct-base-price").val();
            var discount_option = $(
                "input[name=discount_option]:checked",
                "#addProductForm"
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
    var handleSubmitFunction = function () {
        $("#addProductForm").dirty("setAsClean");
        $(document).on("click", ".productSave", function () {
            var form = document.getElementById("addProductForm");
            if ($("#addProductForm").dirty("isDirty")) {
                Swal.fire({
                    title: "Do you want to save this product as draft?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary",
                    },
                }).then((result) => {
                    $("#addProductForm").dirty("setAsClean");
                    if (result.isConfirmed) {
                        var input = document.createElement("input");
                        input.setAttribute("name", "status");
                        input.setAttribute("value", "draft");
                        form.appendChild(input);
                        form.submit();
                    } else {
                        window.history.back();
                    }
                });
            } else {
                window.history.back();
            }
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
            $(".returnPolicy").hide();
            discountPanel();
            handleUiSlider();
            validateForm();
            handleSubmitFunction();
            handleReturn();
            categoryTreeLoad();
        },
    };
})();

var SavoMartProductAttribute = (function () {
    var handleAttributeSetChange = function (e) {
        const attributeSetSelect = document.getElementById(
            "product_attribute_set_select"
        );
        $(attributeSetSelect).change(function () {
            const setValue = attributeSetSelect.value;
            if (setValue) {
                $.ajax({
                    method: "GET",
                    url: $(attributeSetSelect).data("form-url"),
                    data: {
                        setId: setValue,
                    },
                    success: function (data) {
                        $("#attributes_container").html(data.html);
                        SavoMartForm.autoCompleteDisable();
                        // SavoMartFlatPicker.initFlatPicker();
                        SavoMartProductAttribute.initForm();
                    },
                });
            }
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
    SavoMartFlatPicker.initFlatPicker();
    SavoMartApp.createSelect2();
    SavoMartJson.validators["addProductForm"] = FormValidation.formValidation(
        document.getElementById("addProductForm"),
        SavoMartJson.productFormValidation
    );
};

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartProductAdd.init();
    SavoMartProductAttribute.init();
    SavoMartImageInput.init();
});

if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = SavoMartProductAdd;
}

function specialPrice() {
    var total = 0;
    var basePrice = $("#pruduct-base-price").val();
    var discount_option = $(
        "input[name=discount_option]:checked",
        "#addProductForm"
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
        $("#btnSubmit").removeAttr("disabled");
    }

    $("#special_price").val(total.toFixed(2));
}
