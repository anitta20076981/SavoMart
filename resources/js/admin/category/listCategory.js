"use strict";
require('../layouts/app');
require('../../../plugins/datatable/datatable');

var SavoMartCategoryList = function () {

    var initCategoryList = function () {

        SavoMartJson.options.datatables.ajax = {
            "url": $("#listCategories").data('url'),
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
            { data: "name_ar", name: "name_ar", orderable: true, searchable: true },
            { data: "icon", name: "icon", orderable: true, searchable: true },
            { data: "parent_category", name: "parent_category", orderable: false, searchable: false },
            { data: "status", orderable: false, searchable: false },
            { data: "action", orderable: false, searchable: false },
        ];
        SavoMartJson.options.datatables.order = [
            [1, "asc"]
        ];
        SavoMartJson.dataTables['listCategories'] = $('#listCategories').DataTable(SavoMartJson.options.datatables);
        SavoMartJson.dataTables['listCategories'].on('draw', function () {
            SavoMartDatatable.handleDeleteRows();
            SavoMartMenu.createInstances();
        });
    }

    return {
        init: function () {
            initCategoryList();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartCategoryList.init();
});
