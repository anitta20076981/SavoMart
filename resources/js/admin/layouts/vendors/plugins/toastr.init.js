toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toastr-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};


if ($("#sessionSuccess").length) {
    toastr.success($("#sessionSuccess").text());
}
if (localStorage.getItem("success")) {
    toastr.success(localStorage.getItem("success"));
    localStorage.clear('success');
}
if ($("#sessionError").length) {
    toastr.error($("#sessionError").text());
}
if (localStorage.getItem("error")) {
    toastr.error(localStorage.getItem("error"));
    localStorage.clear('error');
}