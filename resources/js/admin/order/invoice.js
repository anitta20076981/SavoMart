"use strict";
require("../../admin/layouts/app");

var SavoMartOrderView = function () {
    var invoiceFormvalidateForm = function (e) {
        SavoMartJson.validators['invoiceForm'] = FormValidation.formValidation(document.getElementById('invoiceForm'), SavoMartJson.options.FormValidation);

    }

    return {
        init: function () {
            invoiceFormvalidateForm();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartOrderView.init();
});