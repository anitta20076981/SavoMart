"use strict";

// Class definition
var SavoMartFormRepeater = function () { };

SavoMartFormRepeater.init = function () { };

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartFormRepeater.init();
});

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = SavoMartFormRepeater;
}