"use strict";
require('../layouts/app');
require('../../../plugins/datatable/datatable');

var SavoMartRoleList = function () {

    var initUserList = function () {

        SavoMartJson.options.datatables.ajax = {
            "url": $("#rolesUsersTable").data('url'),
            "type": "POST",
            "data": function (data) {
                data._token = _token;
                data.role_id = $("#rolesUsersTable").data('role_id');
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
        SavoMartJson.dataTables['rolesUsersTable'] = $('#rolesUsersTable').DataTable(SavoMartJson.options.datatables);
        SavoMartJson.dataTables['rolesUsersTable'].on('draw', function () {
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
    SavoMartRoleList.init();
});
