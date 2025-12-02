"use strict";

// Class definition
var SavoMartDatatable = function () { };

SavoMartDatatable.handleResetForm = function () {
    const resetButton = document.querySelector('[data-kt-table-filter="reset"]');
    if (resetButton && $(resetButton).length) {
        resetButton.addEventListener('click', function () {
            $('#kt-toolbar-filter').find("input, textarea, select").val("").change();
            SavoMartJson.dataTables[$(resetButton).closest(".card").find("table").attr("id")].draw();
        });
    }
}
SavoMartDatatable.handleFilterDatatable = function () {
    const filterButton = document.querySelector('[data-kt-table-filter="filter"]');
    if (filterButton && $(filterButton).length) {
        filterButton.addEventListener('click', function () {
            SavoMartJson.dataTables[$(filterButton).closest(".card").find("table").attr("id")].draw();
        });
    }
}
SavoMartDatatable.handleSearchDatatable = function () {
    const filterSearch = document.querySelector('[data-kt-table-filter="search"]');
    if (filterSearch && $(filterSearch).length) {
        filterSearch.addEventListener('keyup', function (e) {
            SavoMartJson.dataTables[$(filterSearch).closest(".card").find("table").attr("id")].search(e.target.value).draw();
        });
    }
}
SavoMartDatatable.handleRefreshDatatable = function () {
    const refresh = document.querySelector('[data-kt-table-filter="refresh"]');
    if (refresh && $(refresh).length) {
        refresh.addEventListener('click', function (e) {
            SavoMartJson.dataTables[$(refresh).closest(".card").find("table").attr("id")].draw();
        });
    }
}
SavoMartDatatable.handleDeleteRows = function () {
    const deleteButtons = document.querySelectorAll('[data-kt-table-delete="delete_row"]');
    if (deleteButtons && $(deleteButtons).length) {
        deleteButtons.forEach(d => {
            d.addEventListener('click', function (e) {
                e.preventDefault();
                Swal.fire({
                    text: "Are you sure you want to delete ?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    },
                    inputPlaceholder: "Write something"
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: $(d).data("url"),
                            type: "get",
                            dataType: "json",
                            success: function (data) {
                                if (data.status == 1) {
                                    SavoMartJson.dataTables[$(d).parents("table").attr("id")].draw();
                                    toastr.success(data.message);
                                }
                            }
                        });
                    }
                });
            })
        });
    }
}

SavoMartDatatable.init = function () {
    SavoMartDatatable.handleResetForm();
    SavoMartDatatable.handleFilterDatatable();
    SavoMartDatatable.handleSearchDatatable();
    SavoMartDatatable.handleRefreshDatatable();
};

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartDatatable.init();
});

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = SavoMartDatatable;
}
