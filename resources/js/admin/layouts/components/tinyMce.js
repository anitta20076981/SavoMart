"use strict";

var SavoMartTinyMceInput = function () { };

SavoMartTinyMceInput.initEditor = () => {

    if ($('[data-kt-tinymce-editor="true"][data-kt-initialized="false"]').length) {
        SavoMartJson.options.tinyMce.selector = '[data-kt-tinymce-editor="true"][data-kt-initialized="false"]';
        tinymce.init(SavoMartJson.options.tinyMce);
    }
}

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartTinyMceInput.initEditor();
});

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = SavoMartTinyMceInput;
}