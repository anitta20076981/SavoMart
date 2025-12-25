"use strict";
require("../../admin/layouts/app");
require("../../../plugins/datatable/datatable");

var SavoMartProductsListPages = (function () {
    var initUserList = function () {
        SavoMartJson.options.datatables.ajax = {
            url: $("#listProducts").data("url"),
            type: "POST",
            data: function (data) {
                data._token = _token;
                data.status = $("#status").val();
                data.category_id = $("#category_id").val();
                data.stock_status = $("#stock_status").val();
            },
        };
        SavoMartJson.options.datatables.columns = [
            { data: "DT_RowIndex", orderable: false, searchable: false },
            { data: "name", name: "name", orderable: true, searchable: true },
            { data: "sku", name: "sku", orderable: false, searchable: true },
            { data: "type", name: "type", orderable: true, searchable: true },
            { data: "status", orderable: true, searchable: true },
            { data: "action", orderable: false, searchable: false },
        ];
        SavoMartJson.options.datatables.order = [[1, "asc"]];
        SavoMartJson.dataTables["listProducts"] = $("#listProducts").DataTable(
            SavoMartJson.options.datatables
        );
        SavoMartJson.dataTables["listProducts"].on("draw", function () {
            SavoMartDatatable.handleDeleteRows();
            SavoMartMenu.createInstances();

        });
    };

    return {
        init: function () {
            initUserList();
        },
    };
})();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartProductsListPages.init();
});
