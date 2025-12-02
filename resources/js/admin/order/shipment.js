"use strict";
require("../../admin/layouts/app");

var SavoMartOrderView = function () {
    var shipmentFormvalidateForm = function (e) {
        SavoMartJson.validators['shipmentForm'] = FormValidation.formValidation(document.getElementById('shipmentForm'), SavoMartJson.options.FormValidation);

    }

    return {
        init: function () {
            shipmentFormvalidateForm();
            // hangleDeliverBtnClick();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartOrderView.init();
});