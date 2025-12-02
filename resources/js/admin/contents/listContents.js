"use strict";
require('../layouts/app');
require('../../../plugins/datatable/datatable');

var SavoMartContentsListContents = function () {

    var initUserList = function () {

        SavoMartJson.options.datatables.ajax = {
            "url": $("#listContents").data('url'),
            "type": "POST",
            "data": function (data) {
                data._token = _token;
                data.status = $('#status').val();
            }
        };
        SavoMartJson.options.datatables.columns = [
            { data: "DT_RowIndex", orderable: false, searchable: false },
            { data: "name", name: "name", orderable: true, searchable: true },
            { data: "title", name: "title", orderable: false, searchable: true },
            { data: "slug", name: "slug", orderable: false, searchable: true },
            { data: "category.name", name: "category.name", orderable: false, searchable: true },
            { data: "status", orderable: true, searchable: true },
            { data: "action", orderable: false, searchable: false },
        ];
        SavoMartJson.options.datatables.order = [
            [1, "asc"]
        ];
        SavoMartJson.dataTables['listContents'] = $('#listContents').DataTable(SavoMartJson.options.datatables);
        SavoMartJson.dataTables['listContents'].on('draw', function () {
            SavoMartDatatable.handleDeleteRows();
            SavoMartMenu.createInstances();
        });
    }

    return {
        init: function () {
            initUserList();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartContentsListContents.init();
});