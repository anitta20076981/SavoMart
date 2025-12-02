"use strict";
require('../layouts/app');
require('../../../plugins/datatable/datatable');

var SavoMartAttributeListAttribute = function () {

    var initAttributeList = function () {

        SavoMartJson.options.datatables.ajax = {
            "url": $("#listAttributeSets").data('url'),
            "type": "POST",
            "data": function (data) {
                data._token = _token;
                data.status = $('#status').val();
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
        SavoMartJson.dataTables['listAttributeSets'] = $('#listAttributeSets').DataTable(SavoMartJson.options.datatables);
        SavoMartJson.dataTables['listAttributeSets'].on('draw', function () {
            SavoMartDatatable.handleDeleteRows();
            SavoMartMenu.createInstances();
        });
    }

    var handleAttributeSearch = () => {
        const filterSearch = document.querySelector('[data-kt-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            SavoMartJson.dataTables['listAttributeSets'].search(e.target.value).draw();
        });
    }

    return {
        init: function () {
            initAttributeList();
            handleAttributeSearch();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartAttributeListAttribute.init();
});