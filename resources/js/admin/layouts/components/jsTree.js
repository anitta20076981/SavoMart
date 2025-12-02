"use strict";

// Class definition
var SavoMartJsTree = function () { };

SavoMartJsTree.createJstree = function () {
    // Check if jQuery included
    if (typeof jQuery == 'undefined') {
        return;
    }

    // Check if jstree included
    if (typeof $.fn.jstree === 'undefined') {
        return;
    }

    var jsTreeElements = [].slice.call(document.querySelectorAll('[kt-jstree-container="true"]'));
    jsTreeElements.map(function (element) {
        $(element).bind("changed.jstree", function (e, data) {
            if (data.node) {
                if (data.node && data.node.state.selected) {
                    $("input.tree-checkbox." + data.node.id).prop('checked', true);
                } else {
                    $("input.tree-checkbox." + data.node.id).prop('checked', false);
                }
            }
        }).bind('loaded.jstree', function () {
            var selectedCategories = $("#categoryTree").data("selected");
            for (var k in selectedCategories) {
                $('#categoryTree').jstree("select_node", '#category_' + selectedCategories[k], true);
            }
        }).jstree(SavoMartJson.options.jsTree);
    });
    $('.searchCategory').keyup(function () {
        $('#categoryTree').jstree(true).search($('.searchCategory').val());
    });
}

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartJsTree.createJstree();
});

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = SavoMartJsTree;
}
