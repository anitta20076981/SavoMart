"use strict";

const SavoMartFormValidation = require("../layouts/components/formValidation");

var SavoMartRoleAdd = function () {
    var validateForm = function (e) {
        SavoMartJson.options.FormValidation.fields = {
            'name': {
                validators: {
                    notEmpty: {
                        message: 'Name is required'
                    }
                }
            },
        };
        SavoMartJson.validators['roleForm'] = FormValidation.formValidation(document.getElementById('roleForm'), SavoMartJson.options.FormValidation);
    }

    // Select all handler
    const handleSelectAll = () => {
        const selectAll = document.getElementById('roleForm').querySelector('#kt_roles_select_all');
        const allCheckboxes = document.getElementById('roleForm').querySelectorAll('[type="checkbox"]');

        selectAll.addEventListener('change', e => {
            allCheckboxes.forEach(c => {
                c.checked = e.target.checked;
            });
        });
    }

    return {
        init: function () {
            $("#roleModal").modal();
            $("#roleModal").modal("show");
            $("#roleModal").removeAttr("tabindex");
            SavoMartScroll.createInstances();
            validateForm();
            SavoMartFormValidation.handleFormSubmit();

            handleSelectAll();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartRoleAdd.init();
});