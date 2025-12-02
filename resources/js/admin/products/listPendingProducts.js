"use strict";
require('../../layouts/admin');
require('../../../plugins/datatable/datatable');

var SavoMartListPendingProducts = function () {

    var initUserList = function () {

        SavoMartJson.options.datatables.ajax = {
            "url": $("#listPendingProducts").data('url'),
            "type": "POST",
            "data": function (data) {
                data._token = _token;
                data.category_id = $('#category_id').val();
                data.stock_status = $('#stock_status').val();
            }
        };
        SavoMartJson.options.datatables.columns = [
            { data: "DT_RowIndex", orderable: false, searchable: false },
            { data: "name", name: "name", orderable: true, searchable: true },
            { data: "sku", name: "sku", orderable: false, searchable: true },
            { data: "type", name: "type", orderable: true, searchable: true },
            { data: "stock_status", name: "stock_status", orderable: true, searchable: true },
            { data: "quantity", name: "quantity", orderable: false, searchable: false },
            { data: "price", name: "price", orderable: false, searchable: true },
            { data: "customer.name", name: "customer.name", orderable: false, searchable: true },
            { data: "status", orderable: true, searchable: true },
            { data: "action", orderable: false, searchable: false },
        ];
        SavoMartJson.options.datatables.order = [
            [1, "asc"]
        ];
        SavoMartJson.dataTables['listPendingProducts'] = $('#listPendingProducts').DataTable(SavoMartJson.options.datatables);
        SavoMartJson.dataTables['listPendingProducts'].on('draw', function () {
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
    SavoMartListPendingProducts.init();
});