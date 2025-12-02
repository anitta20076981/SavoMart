"use strict";
require('../layouts/app');
window.ApexCharts = require('apexcharts/dist/apexcharts.min.js');
require('../../../plugins/datatable/datatable');
require('../../../plugins/@amcharts/amcharts5');
require('../../../plugins/@amcharts/amcharts5-geodata');
require('../layouts/widgets/cards/widget-8');
require('../layouts/widgets/cards/widget-9');
require('../layouts/widgets/maps/widget-1');
require('../layouts/widgets/charts/widget-5');
require('../layouts/widgets/charts/widget-13');
require('../layouts/widgets/charts/widget-14');
require('../layouts/widgets/charts/widget-15');
require('../layouts/widgets/widgets');

var SavoMartDashboardHome = function () {

    // Public methods
    return {
        init: function () { }
    }
}();

// On document ready
SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartDashboardHome.init();
});
