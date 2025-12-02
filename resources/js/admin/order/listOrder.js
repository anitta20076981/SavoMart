"use strict";
require("../../admin/layouts/app");
require("../../../plugins/datatable/datatable");

var OrderList = function () {

    var initOrderList = function () {

        SavoMartJson.options.datatables.ajax = {
            "url": $("#listOrders").data('url'),
            "type": "POST",
            "data": function (data) {
                data._token = _token;
                data.status = $('#status').val();
                data.customer_id = $('#customer_id').val();
            }
        };
        SavoMartJson.options.datatables.columns = [
            { data: "DT_RowIndex", orderable: false, searchable: false },
            { data: "created_at", name: "created_at", orderable: true, searchable: true },
            { data: "order_no", name: "order_no", orderable: true, searchable: true },
            { data: "customer.name", name: "customer.first_name", orderable: false, searchable: true },
            { data: "order_status", name: "order_status", orderable: false, searchable: false },
            // { data: "paymentMethod", name: "paymentMethod.name", orderable: false, searchable: true },
            { data: "action", orderable: false, searchable: false },
        ];
        SavoMartJson.options.datatables.order = [
            [1, "asc"]
        ];
        SavoMartJson.dataTables['listOrders'] = $('#listOrders').DataTable(SavoMartJson.options.datatables);
        SavoMartJson.dataTables['listOrders'].on('draw', function () {
            SavoMartDatatable.handleDeleteRows();
            SavoMartMenu.createInstances();
        });
    }

    return {
        init: function () {
            initOrderList();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    OrderList.init();
});