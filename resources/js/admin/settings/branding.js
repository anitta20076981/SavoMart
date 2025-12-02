"use strict";
require('../layouts/app');
window.autosize = require('autosize/dist/autosize.min.js');
window.SavoMartImageInput = require('../layouts/components/image-input.js');

var SavoMartSettingsBranding = function () {
    return {
        init: function () { }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartImageInput.init();
    SavoMartSettingsBranding.init();
});