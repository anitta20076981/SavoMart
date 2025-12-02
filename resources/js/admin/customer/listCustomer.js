"use strict";
require('../layouts/app');
require('../../../plugins/datatable/datatable');

var SavoMartCustomerList = function () {

    var initCustomerList = function () {

        SavoMartJson.options.datatables.ajax = {
            "url": $("#listCustomer").data('url'),
            "type": "POST",
            "data": function (data) {
                data._token = _token;
                data.status = $('#status').val();
                data.vendor_status = $('#vendor_status').val();
            }
        };
        SavoMartJson.options.datatables.columns = [
            { data: "DT_RowIndex", orderable: false, searchable: false },
            { data: "first_name", name: 'first_name', orderable: true, searchable: true },
            { data: "phone", name: 'phone', orderable: true, searchable: true },
            { data: "approve", name: "approve", orderable: true, searchable: true },
            { data: "status", orderable: true, searchable: true },
            { data: "action", orderable: false, searchable: false },
        ];
        SavoMartJson.options.datatables.order = [
            [1, "asc"]
        ];
        SavoMartJson.dataTables['listCustomer'] = $('#listCustomer').DataTable(SavoMartJson.options.datatables);
        SavoMartJson.dataTables['listCustomer'].on('draw', function () {
            SavoMartDatatable.handleDeleteRows();
            SavoMartMenu.createInstances();
        });
    }

    var approveButton = function () {


        $(document).on("change", ".update-list-approval", function () {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var value = '';
            var dataId = $(this).data('id');
            if ($(this).is(':checked')) {
                value = 1;
            } else {
                value = 0;
            }

            $.ajax({
                type: 'POST',
                url: $(".update-list-approval").data('url'),
                data: {
                    id: dataId,
                    value: value,
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    // Handle the response, if needed
                },
                error: function (error) {
                    console.error('Error:', error);
                }

            });
        });

    }

    return {
        init: function () {
            initCustomerList();
            approveButton();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartCustomerList.init();
});
