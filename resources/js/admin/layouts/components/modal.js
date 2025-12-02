"use strict";

var SavoMartModal = function () { };

SavoMartModal.initModal = () => {
    const closeButton = document.querySelector('[data-kt-modal-action="close"]');
    closeButton.addEventListener('click', e => {
        e.preventDefault();
        $(closeButton).parents(".modal").modal("hide");
    });

    const cancelButton = document.querySelector('[data-kt-modal-action="cancel"]');
    cancelButton.addEventListener('click', e => {
        e.preventDefault();
        $(closeButton).parents(".modal").modal("hide");
    });
}

SavoMartModal.handleLoadRemoteHtml = function () {
    const loadButtons = document.querySelectorAll('[kt-load-remote-html="true"][kt-load-remote-init="false"]');
    if (loadButtons && $(loadButtons).length) {
        loadButtons.forEach(function (el) {
            el.setAttribute('kt-load-remote-init', 'true');
            el.addEventListener('click', function () {
                $.ajax({
                    method: "GET",
                    url: $(el).data('url'),
                    success: function (data) {
                        $("#model-area").html(data.html);
                        $.each(data.scripts, function (key, script) {
                            $.getScript(script);
                        });
                        SavoMartApp.createSelect2();
                        SavoMartForm.autoCompleteDisable();
                        SavoMartModal.initModal();
                        SavoMartMenu.createInstances();
                    },
                });
            });
        });
    }
}

SavoMartModal.init = function () {
    SavoMartModal.handleLoadRemoteHtml();
};

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartModal.init();
});

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = SavoMartModal;
}