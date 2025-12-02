"use strict";
require('../layouts/app');
require('../../../plugins/draggable/draggable.js');


var SavoMartAttributeSetEdit = function () {

    var handleWindowOnload = function (e) {
        $(window).on("load", function () {
            const statusIndicator = document.getElementById('attribute_status_indicator');
            const statusClasses = ['bg-success', 'bg-danger'];
            statusIndicator.classList.remove(...statusClasses);
        });
    }

    var handleDragable = function (e) {
        var containers = document.querySelectorAll('.draggable-zone');
        var attributeOriginLevel;
        var attributeValue;
        var attribute;

        if (containers.length === 0) {
            return false;
        }

        var sortable = new Sortable.default(containers, {
            draggable: '.draggable',
            handle: '.draggable.draggable-handle',
            mirror: {
                appendTo: 'body',
                constrainDimensions: true
            }
        });

        sortable.on("sortable:stop", (e) => {
            attribute = e.dragEvent.originalSource;
            attributeOriginLevel = attribute.getAttribute("data-kt-drag-attribute-zone");
            attributeValue = attribute.getAttribute("data-kt-drag-attribute-value");
            const attributeDestinationLevel = e.newContainer.getAttribute("data-kt-drag-zone");
            const attributId = 'attribute-input-' + attributeValue;
            if (attributeDestinationLevel == 'assigned' && attributeOriginLevel == "un-assigned") {
                var input = document.createElement("input");
                input.setAttribute("type", "hidden");
                input.setAttribute("class", "attribute-ids");
                input.setAttribute("id", attributId);
                input.setAttribute("name", "assigned_attributes[]");
                input.setAttribute("value", attributeValue);
                attribute.appendChild(input);
                attribute.setAttribute("data-kt-drag-attribute-zone", "assigned");
            }

            if (attributeDestinationLevel == 'un-assigned' && attributeOriginLevel == "assigned") {
                attribute.setAttribute("data-kt-drag-attribute-zone", "un-assigned");
                document.getElementById(attributId).remove();
            }
        });
    }

    var handleAddAttribueFormValidation = function (e) {
        SavoMartJson.options.FormValidation.fields = {
            'name': {
                validators: {
                    notEmpty: {
                        message: 'Name is required'
                    }
                }
            },
            'status': {
                validators: {
                    notEmpty: {
                        message: 'Status is required'
                    }
                }
            },
        };
        SavoMartJson.validators['attributeSetForm'] = FormValidation.formValidation(document.getElementById('attributeSetForm'), SavoMartJson.options.FormValidation);
    }

    var handleAttributeStatus = function (e) {
        const target = document.getElementById('attribute_status_indicator');
        const select = document.getElementById('attribute_status_select');
        const statusClasses = ['bg-success', 'bg-danger'];
        $(select).on('change', function (e) {
            const value = e.target.value;

            switch (value) {
                case "active":
                    {
                        target.classList.remove(...statusClasses);
                        target.classList.add('bg-success');
                        break;
                    }
                case "inactive":
                    {
                        target.classList.remove(...statusClasses);
                        target.classList.add('bg-danger');
                        break;
                    }
                default:
                    break;
            }
        });
    }

    return {
        init: function () {
            handleAddAttribueFormValidation();
            handleAttributeStatus();
            handleWindowOnload();
            handleDragable();
        }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartAttributeSetEdit.init();
});