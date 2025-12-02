"use strict";
require('../layouts/app');
require('../../../plugins/tinymce/tinymce');
require('../../../plugins/tagify/tagify');
window.SavoMartImageInput = require('../layouts/components/image-input.js');

var SavoMartCustomerAdd = function () {
    var validateCustomerForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {
            'first_name': {
                validators: {
                    notEmpty: {
                        message: 'First Name is required'
                    }
                }
            },
            'last_name': {
                validators: {
                    notEmpty: {
                        message: 'Last Name is required'
                    }
                }
            },
            'email': {
                validators: {
                    emailAddress: {
                        message: 'The value is not a valid email address'
                    },
                    notEmpty: {
                        message: 'Email address is required'
                    }
                }
            },
            'phone': {
                validators: {
                    notEmpty: {
                        message: 'Phone is required'
                    }
                }
            },
            'password': {
                validators: {
                    notEmpty: {
                        message: 'The password is required'
                    },

                }
            },
            'confirm_password': {
                validators: {
                    notEmpty: {
                        message: 'The password confirmation is required'
                    },
                    callback: {
                        message: 'The password and its confirm are not the same',
                        callback: function (value, validator, $field) {
                            if ($('[name="confirm_password"]').val() == $('[name="password"]').val()) {
                                return true;
                            }
                            return false;
                        }
                    }
                }
            },
            'pin_code': {
                validators: {
                    numeric: {
                        message: 'Enter Valid Pin',
                        thousandsSeparator: '',
                        decimalSeparator: '.',
                    },
                    stringLength: {
                        min: 6,
                        max: 6,
                        message: 'The pin code must be more than 6 and less than 6 characters long',
                    },
                },
            },

            'aadhar_number': {
                validators: {
                    numeric: {
                        message: 'Enter Valid Aadhar Number',
                        thousandsSeparator: '',
                        decimalSeparator: '.',
                    },
                    stringLength: {
                        min: 12,
                        max: 12,
                        message: 'The Aadhar Number must be more than 12 and less than 12 characters long',
                    },
                },
            },

            'pan_number': {
                validators: {
                    stringLength: {
                        min: 4,
                        message: 'The Pan Number must be minimum of 4 characters long',
                    },
                },
            },

            'account_no': {
                validators: {
                    numeric: {
                        message: 'Enter Valid Account Number',
                        thousandsSeparator: '',
                        decimalSeparator: '.',
                    },
                    stringLength: {
                        min: 11,
                        max: 16,
                        message: 'The Account Number must be more than 11 and less than 16 characters long',
                    },
                },
            },
            'gst_number': {
                validators: {
                    callback: {
                        message: 'Gst Number is required',
                        callback: function (value, validator, $field) {
                            if (!$("#has_gst").is(":checked") || $('[name="gst_number"]').val()) {
                                return true;
                            }
                            return false;
                        }
                    }
                },
            },
            'gst_certificate': {
                validators: {
                    callback: {
                        message: 'Upload Gst Certificate',
                        callback: function (value, validator, $field) {
                            if (!$("#has_gst").is(":checked") || $('[name="gst_certificate"]').val()) {
                                return true;
                            }
                            return false;
                        }
                    }
                },
            },
            'gst_date_of_in_corparation': {
                validators: {
                    callback: {
                        message: 'Gst Date filed is required',
                        callback: function (value, validator, $field) {
                            if (!$("#has_gst").is(":checked") || $('[name="gst_date_of_in_corparation"]').val()) {
                                return true;
                            }
                            return false;
                        }
                    }
                },
            },


        };
        SavoMartJson.validators['customerForm'] = FormValidation.formValidation(document.getElementById('customerForm'), SavoMartJson.options.FormValidation);
    }

    var statusEvent = function (e) {
        const target = document.getElementById('customer_status');
        $("#customer_status_select").change(function (e) {
            switch (e.target.value) {
                case "active":
                    {
                        target.classList.remove(...['bg-success', 'bg-warning', 'bg-danger']);
                        target.classList.add('bg-success');
                        break;
                    }
                case "inactive":
                    {
                        target.classList.remove(...['bg-success', 'bg-warning', 'bg-danger']);
                        target.classList.add('bg-danger');
                        break;
                    }
                default:
                    break;
            }
        });
    }

    var initGSTRadioChange = function () {
        $(".gst-details").hide();
        $("#has_gst").on("change", function (e) {
            if ($("#has_gst").is(":checked")) {
                $(".gst-details").show();
                $(".nonGst_reason_for_exemption").hide();
            } else {
                $(".gst-details").hide();
                $(".nonGst_reason_for_exemption").show();
            }
        });
    }

    var initVendorSelectChange = function () {
        $(".vendor-details").hide();
        $('#is_vendor').on("change", function (e) {
            if ($('#is_vendor').val() == 1) {
                $(".vendor-details").show();
            } else {
                $(".vendor-details").hide();
            }
        });
    }

    return {
        init: function () {
            validateCustomerForm();
            statusEvent();
            initGSTRadioChange();
            initVendorSelectChange();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartImageInput.init();
    SavoMartCustomerAdd.init();
});
