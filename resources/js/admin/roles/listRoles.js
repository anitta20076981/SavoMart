"use strict";
require('../layouts/app');
require('../../../plugins/datatable/datatable');

var SavoMartRoleList = function () {

    return {
        init: function () { }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartRoleList.init();
});