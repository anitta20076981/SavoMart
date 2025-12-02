"use strict";

// Class definition
var SavoMartFormValidation = function () { };

SavoMartFormValidation.handleFormSubmit = function () {
    var submitButton = document.querySelector('.fv-button-submit');
    if (submitButton && $(submitButton).length) {

        submitButton.addEventListener('click', function (e) {
            e.preventDefault();
            SavoMartJson.validators[$(submitButton).parents("form").attr("id")].validate().then(function (status) {
                $("a.nav-link").removeClass("tab-pane-error");
                if (status == 'Valid') {
                    submitButton.setAttribute('data-kt-indicator', 'on');
                    submitButton.disabled = true;
                    $(submitButton).parents("form").submit();
                } else {
                    $("a.nav-link[href='#" + $(".fv-plugins-bootstrap5-row-invalid:first").parents(".tab-pane").attr("id") + "']").addClass('tab-pane-error');
                }
            });
        });
    }
}

SavoMartFormValidation.init = function () {
    SavoMartFormValidation.handleFormSubmit();
};

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartFormValidation.init();
});

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = SavoMartFormValidation;
}
