"use strict";

var SavoMartFormHandlersInitialized = false;

// Class definition
var SavoMartForm = function (element, options) { };

SavoMartForm.autoCompleteDisable = function (selector = "input") {
    var elements = $(selector);
    if (elements && elements.length > 0) {
        elements.attr("autocomplete", "off");
    }
};

SavoMartForm.init = function () {
    SavoMartForm.autoCompleteDisable();
};

if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = SavoMartForm;
}
