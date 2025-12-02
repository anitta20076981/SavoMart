"use strict";
require('../layouts/app');
require('../../../plugins/datatable/datatable');

var SavoMartApplicationListUser = function () {

    var initApplicationList = function () {

        SavoMartJson.options.datatables.ajax = {
            "url": $("#listApplications").data('url'),
            "type": "POST",
            "data": function (data) {
                data._token = _token;
                data.status = $('#status').val();
            }
        };
        SavoMartJson.options.datatables.columns = [
            { data: "DT_RowIndex", orderable: false, searchable: false },
            { data: "name", name: "name", orderable: true, searchable: true },
            { data: "status", orderable: false, searchable: false },
            { data: "action", orderable: false, searchable: false },
        ];
        SavoMartJson.options.datatables.order = [
            [1, "asc"]
        ];
        SavoMartJson.dataTables['listApplications'] = $('#listApplications').DataTable(SavoMartJson.options.datatables);
        SavoMartJson.dataTables['listApplications'].on('draw', function () {
            SavoMartDatatable.handleDeleteRows();
            SavoMartMenu.createInstances();
        });
    }

    return {
        init: function () {
            initApplicationList();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartApplicationListUser.init();
});