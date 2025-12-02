"use strict";
require("../../admin/layouts/app");
require("../../../plugins/datatable/datatable");

var SavoMartPagesListPages = function () {

    var initUserList = function () {

        SavoMartJson.options.datatables.ajax = {
            "url": $("#listPages").data('url'),
            "type": "POST",
            "data": function (data) {
                data._token = _token;
                data.status = $('#status').val();
                data.category_id = $('#category_id').val();
            }
        };
        SavoMartJson.options.datatables.columns = [
            { data: "DT_RowIndex", orderable: false, searchable: false },
            { data: "name", name: "name", orderable: true, searchable: true },
            { data: "title", name: "title", orderable: false, searchable: true },
            { data: "slug", name: "slug", orderable: false, searchable: true },
            { data: "status", orderable: true, searchable: true },
            { data: "action", orderable: false, searchable: false },
        ];
        SavoMartJson.options.datatables.order = [
            [1, "asc"]
        ];
        SavoMartJson.dataTables['listPages'] = $('#listPages').DataTable(SavoMartJson.options.datatables);
        SavoMartJson.dataTables['listPages'].on('draw', function () {
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
    SavoMartPagesListPages.init();
});