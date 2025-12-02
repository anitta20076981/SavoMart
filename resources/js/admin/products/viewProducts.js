"use strict";

var SavoMartProductViewProduct = function () {
    return {
        init: function () {
            $("#productModal").modal();
            $("#productModal").modal("show");
            $("#productModal").removeAttr("tabindex");
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartProductViewProduct.init();
});
