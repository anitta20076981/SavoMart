"use strict";
require('../layouts/app');

var SavoMartSettingsConfiguration = function () {
    return {
        init: function () { }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartSettingsConfiguration.init();
});