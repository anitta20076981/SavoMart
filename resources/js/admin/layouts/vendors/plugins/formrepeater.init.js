SavoMartJson.options.formRepeater = {
    initEmpty: false,

    show: function () {
        $(this).slideDown();
    },

    hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
    }
};

if ($('#shipment_details').length) {
    $('#shipment_details').repeater(SavoMartJson.options.formRepeater);
}

if ($('#payment_details').length) {
    $('#payment_details').repeater(SavoMartJson.options.formRepeater);
}