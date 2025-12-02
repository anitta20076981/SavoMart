"use strict";
require('../layouts/app');
require('../../../plugins/datatable/datatable');

var SavoMartUsersListUser = function () {

    var initUserList = function () {

        SavoMartJson.options.datatables.ajax = {
            "url": $("#listUsers").data('url'),
            "type": "POST",
            "data": function (data) {
                data._token = _token;
                data.status = $('#status').val();
                data.role_id = $('#roleId').val();
            }
        };
        SavoMartJson.options.datatables.columns = [
            { data: "DT_RowIndex", orderable: false, searchable: false },
            { data: "name", name: "name", orderable: true, searchable: true },
            { data: "phone", name: "phone", orderable: false, searchable: false },
            { data: "status", orderable: true, searchable: true },
            { data: "action", orderable: false, searchable: false },
        ];
        SavoMartJson.options.datatables.order = [
            [1, "asc"]
        ];
        SavoMartJson.dataTables['listUsers'] = $('#listUsers').DataTable(SavoMartJson.options.datatables);
        SavoMartJson.dataTables['listUsers'].on('draw', function () {
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
    SavoMartUsersListUser.init();
});
