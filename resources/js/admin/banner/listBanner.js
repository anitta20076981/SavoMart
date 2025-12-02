"use strict";
require('../layouts/app');
require('../../../plugins/datatable/datatable');

var SavoMartBannerList = function () {

    var initBannerList = function () {

        SavoMartJson.options.datatables.ajax = {
            "url": $("#listBanner").data('url'),
            "type": "POST",
            "data": function (data) {
                data._token = _token;
                data.status = $('#status').val();
            }
        };
        SavoMartJson.options.datatables.columns = [
            { data: "DT_RowIndex", orderable: false, searchable: false },
            { data: "name", name: "name", orderable: true, searchable: true },
            { data: "status", orderable: true, searchable: true },
            { data: "action", orderable: false, searchable: false },
        ];
        SavoMartJson.options.datatables.order = [
            [1, "asc"]
        ];
        SavoMartJson.dataTables['listBanner'] = $('#listBanner').DataTable(SavoMartJson.options.datatables);
        SavoMartJson.dataTables['listBanner'].on('draw', function () {
            SavoMartDatatable.handleDeleteRows();
            SavoMartMenu.createInstances();
        });
    }

    return {
        init: function () {
            initBannerList();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartBannerList.init();
});