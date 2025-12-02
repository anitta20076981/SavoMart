"use strict";

var SavoMartFlatPicker = function () { };

SavoMartFlatPicker.initFlatPicker = function () {
    // Check if jQuery included
    if (typeof jQuery == 'undefined') {
        return;
    }

    // Check if flatpickr included
    if (typeof $.fn.flatpickr === 'undefined') {
        return;
    }

    var dateElements = [].slice.call(document.querySelectorAll('[data-kt-date-input="true"][data-kt-initialized="false"]'));
    dateElements.map(function (element) {
        SavoMartJson.options.flatpicker.minDate = null;
        SavoMartJson.options.flatpicker.maxDate = null;
        SavoMartJson.options.flatpicker.enableTime = false;
        SavoMartJson.options.flatpicker.dateFormat = $(element).data("format");
        if ($(element).data("kt-time-enabled") == true) {
            SavoMartJson.options.flatpicker.enableTime = true;
        }
        if ($(element).data("mindate")) {
            SavoMartJson.options.flatpicker.minDate = $(element).data("mindate");
        }
        if ($(element).data("maxdate")) {
            SavoMartJson.options.flatpicker.maxDate = $(element).data("maxdate");
        }
        element.flatpickr(SavoMartJson.options.flatpicker);
        element.setAttribute("data-kt-initialized", "true");
    });
}

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartFlatPicker.initFlatPicker();
});

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = SavoMartFlatPicker;
}
