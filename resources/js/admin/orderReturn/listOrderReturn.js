"use strict";
require("../../admin/layouts/app");
require('../../../plugins/datatable/datatable');

var OrderList = function () {

    var initOrderReturnList = function () {

        SavoMartJson.options.datatables.ajax = {
            "url": $("#listOrderReturns").data('url'),
            "type": "POST",
            "data": function (data) {
                data._token = _token;
                data.status = $('#status').val();
            }
        };
        SavoMartJson.options.datatables.columns = [
            { data: "DT_RowIndex", orderable: false, searchable: false },
            { data: "order.order_no", name: "order.order_no", orderable: true, searchable: true },
            { data: "order.customer.name", name: "order.customer.first_name", orderable: false, searchable: true },
            { data: "return_status", name: "return_status", orderable: false, searchable: false },
            { data: "action", orderable: false, searchable: false },
        ];
        SavoMartJson.options.datatables.order = [
            [1, "asc"]
        ];
        SavoMartJson.dataTables['listOrderReturns'] = $('#listOrderReturns').DataTable(SavoMartJson.options.datatables);
        SavoMartJson.dataTables['listOrderReturns'].on('draw', function () {
            SavoMartDatatable.handleDeleteRows();
            SavoMartMenu.createInstances();
        });
    }

    return {
        init: function () {
            initOrderReturnList();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    OrderList.init();
});