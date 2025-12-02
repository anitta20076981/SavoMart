"use strict";

var SavoMartDrawerHandlersInitialized = false;

// Class definition
var SavoMartDrawer = function (element, options) {
    //////////////////////////////
    // ** Private variables  ** //
    //////////////////////////////
    var the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default options
    var defaultOptions = {
        overlay: true,
        direction: "end",
        baseClass: "drawer",
        overlayClass: "drawer-overlay",
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    var _construct = function () {
        if (SavoMartUtil.data(element).has("drawer")) {
            the = SavoMartUtil.data(element).get("drawer");
        } else {
            _init();
        }
    };

    var _init = function () {
        // Variables
        the.options = SavoMartUtil.deepExtend({}, defaultOptions, options);
        the.uid = SavoMartUtil.getUniqueId("drawer");
        the.element = element;
        the.overlayElement = null;
        the.name = the.element.getAttribute("data-kt-drawer-name");
        the.shown = false;
        the.lastWidth;
        the.toggleElement = null;

        // Set initialized
        the.element.setAttribute("data-kt-drawer", "true");

        // Event Handlers
        _handlers();

        // Update Instance
        _update();

        // Bind Instance
        SavoMartUtil.data(the.element).set("drawer", the);
    };

    var _handlers = function () {
        var togglers = _getOption("toggle");
        var closers = _getOption("close");

        if (togglers !== null && togglers.length > 0) {
            SavoMartUtil.on(document.body, togglers, "click", function (e) {
                e.preventDefault();

                the.toggleElement = this;
                _toggle();
            });
        }

        if (closers !== null && closers.length > 0) {
            SavoMartUtil.on(document.body, closers, "click", function (e) {
                e.preventDefault();

                the.closeElement = this;
                _hide();
            });
        }
    };

    var _toggle = function () {
        if (
            SavoMartEventHandler.trigger(the.element, "kt.drawer.toggle", the) ===
            false
        ) {
            return;
        }

        if (the.shown === true) {
            _hide();
        } else {
            _show();
        }

        SavoMartEventHandler.trigger(the.element, "kt.drawer.toggled", the);
    };

    var _hide = function () {
        if (
            SavoMartEventHandler.trigger(the.element, "kt.drawer.hide", the) ===
            false
        ) {
            return;
        }

        the.shown = false;

        _deleteOverlay();

        document.body.removeAttribute("data-kt-drawer-" + the.name, "on");
        document.body.removeAttribute("data-kt-drawer");

        SavoMartUtil.removeClass(the.element, the.options.baseClass + "-on");

        if (the.toggleElement !== null) {
            SavoMartUtil.removeClass(the.toggleElement, "active");
        }

        SavoMartEventHandler.trigger(the.element, "kt.drawer.after.hidden", the) ===
            false;
    };

    var _show = function () {
        if (
            SavoMartEventHandler.trigger(the.element, "kt.drawer.show", the) ===
            false
        ) {
            return;
        }

        the.shown = true;

        _createOverlay();
        document.body.setAttribute("data-kt-drawer-" + the.name, "on");
        document.body.setAttribute("data-kt-drawer", "on");

        SavoMartUtil.addClass(the.element, the.options.baseClass + "-on");

        if (the.toggleElement !== null) {
            SavoMartUtil.addClass(the.toggleElement, "active");
        }

        SavoMartEventHandler.trigger(the.element, "kt.drawer.shown", the);
    };

    var _update = function () {
        var width = _getWidth();
        var direction = _getOption("direction");

        var top = _getOption("top");
        var bottom = _getOption("bottom");
        var start = _getOption("start");
        var end = _getOption("end");

        // Reset state
        if (
            SavoMartUtil.hasClass(the.element, the.options.baseClass + "-on") ===
            true &&
            String(
                document.body.getAttribute("data-kt-drawer-" + the.name + "-")
            ) === "on"
        ) {
            the.shown = true;
        } else {
            the.shown = false;
        }

        // Activate/deactivate
        if (_getOption("activate") === true) {
            SavoMartUtil.addClass(the.element, the.options.baseClass);
            SavoMartUtil.addClass(
                the.element,
                the.options.baseClass + "-" + direction
            );

            SavoMartUtil.css(the.element, "width", width, true);
            the.lastWidth = width;

            if (top) {
                SavoMartUtil.css(the.element, "top", top);
            }

            if (bottom) {
                SavoMartUtil.css(the.element, "bottom", bottom);
            }

            if (start) {
                if (SavoMartUtil.isRTL()) {
                    SavoMartUtil.css(the.element, "right", start);
                } else {
                    SavoMartUtil.css(the.element, "left", start);
                }
            }

            if (end) {
                if (SavoMartUtil.isRTL()) {
                    SavoMartUtil.css(the.element, "left", end);
                } else {
                    SavoMartUtil.css(the.element, "right", end);
                }
            }
        } else {
            SavoMartUtil.removeClass(the.element, the.options.baseClass);
            SavoMartUtil.removeClass(
                the.element,
                the.options.baseClass + "-" + direction
            );

            SavoMartUtil.css(the.element, "width", "");

            if (top) {
                SavoMartUtil.css(the.element, "top", "");
            }

            if (bottom) {
                SavoMartUtil.css(the.element, "bottom", "");
            }

            if (start) {
                if (SavoMartUtil.isRTL()) {
                    SavoMartUtil.css(the.element, "right", "");
                } else {
                    SavoMartUtil.css(the.element, "left", "");
                }
            }

            if (end) {
                if (SavoMartUtil.isRTL()) {
                    SavoMartUtil.css(the.element, "left", "");
                } else {
                    SavoMartUtil.css(the.element, "right", "");
                }
            }

            _hide();
        }
    };

    var _createOverlay = function () {
        if (_getOption("overlay") === true) {
            the.overlayElement = document.createElement("DIV");

            SavoMartUtil.css(
                the.overlayElement,
                "z-index",
                SavoMartUtil.css(the.element, "z-index") - 1
            ); // update

            document.body.append(the.overlayElement);

            SavoMartUtil.addClass(the.overlayElement, _getOption("overlay-class"));

            SavoMartUtil.addEvent(the.overlayElement, "click", function (e) {
                e.preventDefault();
                _hide();
            });
        }
    };

    var _deleteOverlay = function () {
        if (the.overlayElement !== null) {
            SavoMartUtil.remove(the.overlayElement);
        }
    };

    var _getOption = function (name) {
        if (the.element.hasAttribute("data-kt-drawer-" + name) === true) {
            var attr = the.element.getAttribute("data-kt-drawer-" + name);
            var value = SavoMartUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === "true") {
                value = true;
            } else if (value !== null && String(value) === "false") {
                value = false;
            }

            return value;
        } else {
            var optionName = SavoMartUtil.snakeToCamel(name);

            if (the.options[optionName]) {
                return SavoMartUtil.getResponsiveValue(the.options[optionName]);
            } else {
                return null;
            }
        }
    };

    var _getWidth = function () {
        var width = _getOption("width");

        if (width === "auto") {
            width = SavoMartUtil.css(the.element, "width");
        }

        return width;
    };

    var _destroy = function () {
        SavoMartUtil.data(the.element).remove("drawer");
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.toggle = function () {
        return _toggle();
    };

    the.show = function () {
        return _show();
    };

    the.hide = function () {
        return _hide();
    };

    the.isShown = function () {
        return the.shown;
    };

    the.update = function () {
        _update();
    };

    the.goElement = function () {
        return the.element;
    };

    the.destroy = function () {
        return _destroy();
    };

    // Event API
    the.on = function (name, handler) {
        return SavoMartEventHandler.on(the.element, name, handler);
    };

    the.one = function (name, handler) {
        return SavoMartEventHandler.one(the.element, name, handler);
    };

    the.off = function (name, handlerId) {
        return SavoMartEventHandler.off(the.element, name, handlerId);
    };

    the.trigger = function (name, event) {
        return SavoMartEventHandler.trigger(the.element, name, event, the, event);
    };
};

// Static methods
SavoMartDrawer.getInstance = function (element) {
    if (element !== null && SavoMartUtil.data(element).has("drawer")) {
        return SavoMartUtil.data(element).get("drawer");
    } else {
        return null;
    }
};

// Hide all drawers and skip one if provided
SavoMartDrawer.hideAll = function (
    skip = null,
    selector = '[data-kt-drawer="true"]'
) {
    var items = document.querySelectorAll(selector);

    if (items && items.length > 0) {
        for (var i = 0, len = items.length; i < len; i++) {
            var item = items[i];
            var drawer = SavoMartDrawer.getInstance(item);

            if (!drawer) {
                continue;
            }

            if (skip) {
                if (item !== skip) {
                    drawer.hide();
                }
            } else {
                drawer.hide();
            }
        }
    }
};

// Update all drawers
SavoMartDrawer.updateAll = function (selector = '[data-kt-drawer="true"]') {
    var items = document.querySelectorAll(selector);

    if (items && items.length > 0) {
        for (var i = 0, len = items.length; i < len; i++) {
            var drawer = SavoMartDrawer.getInstance(items[i]);

            if (drawer) {
                drawer.update();
            }
        }
    }
};

// Create instances
SavoMartDrawer.createInstances = function (selector = '[data-kt-drawer="true"]') {
    // Initialize Menus
    var elements = document.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            new SavoMartDrawer(elements[i]);
        }
    }
};

// Create instances
SavoMartDrawer.initDrawers = function (selector = '[data-kt-load-drawer="true"]') {
    // Initialize Menus
    var elements = document.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        elements.forEach(function (el) {
            el.setAttribute("data-kt-initialized", "1");
            el.addEventListener("click", function () {
                var preValdationStatus = "Valid";
                if (SavoMartJson.validators["drawerPreValidation"]) {
                    SavoMartJson.validators["drawerPreValidation"]
                        .validate()
                        .then(function (status) {
                            preValdationStatus = status;
                            if (status != "Valid") {
                                $(
                                    "a.nav-link[href='#" +
                                    $(
                                        ".fv-plugins-bootstrap5-row-invalid:first"
                                    )
                                        .parents(".tab-pane")
                                        .attr("id") +
                                    "']"
                                ).addClass("tab-pane-error");
                            } else {
                                SavoMartDrawer.showDrawer(el);
                            }
                        });
                } else {
                    SavoMartDrawer.showDrawer(el);
                }
            });
        });
    }
};

SavoMartDrawer.showDrawer = function (el) {
    var drawerParametrs = {};
    if (el.hasAttribute("data-drawer-parameters")) {
        var parameters = el.getAttribute("data-drawer-parameters");
        if (parameters !== "") {
            parameters = JSON.parse(parameters);
            $.each(parameters, function (i, parameter) {
                if (parameter.hasOwnProperty("selector")) {
                    drawerParametrs[i] = $(parameter.selector).val();
                } else if (parameter.hasOwnProperty("value")) {
                    drawerParametrs[i] = parameter.value;
                }
            });
        }
    }
    $.ajax({
        method: "GET",
        url: $(el).data("url"),
        data: drawerParametrs,
        success: function (data) {
            $("#drawer-area").html(data.html);
            SavoMartDrawer.createInstances();
            SavoMartForm.autoCompleteDisable();
            $.each(data.scripts, function (key, script) {
                $.getScript(script);
            });
            var drawerElement = document.querySelector(
                '#drawer-area [data-kt-drawer="true"]'
            );
            SavoMartDrawer.getInstance(drawerElement).show();
            SavoMartDrawer.handleDismiss();
        },
    });
};

// Toggle instances
SavoMartDrawer.handleShow = function () {
    // External drawer toggle handler
    SavoMartUtil.on(
        document.body,
        '[data-kt-drawer-show="true"][data-kt-drawer-target]',
        "click",
        function (e) {
            e.preventDefault();

            var element = document.querySelector(
                this.getAttribute("data-kt-drawer-target")
            );

            if (element) {
                SavoMartDrawer.getInstance(element).show();
            }
        }
    );
};

// Dismiss instances
SavoMartDrawer.handleDismiss = function () {
    // External drawer toggle handler
    SavoMartUtil.on(
        document.body,
        '[data-kt-drawer-dismiss="true"]',
        "click",
        function (e) {
            var element = this.closest('[data-kt-drawer="true"]');

            if (element) {
                var drawer = SavoMartDrawer.getInstance(element);
                if (drawer.isShown()) {
                    drawer.hide();
                }
            }
        }
    );
};

// Handle resize
SavoMartDrawer.handleResize = function () {
    // Window resize Handling
    window.addEventListener("resize", function () {
        var timer;

        SavoMartUtil.throttle(
            timer,
            function () {
                // Locate and update drawer instances on window resize
                var elements = document.querySelectorAll(
                    '[data-kt-drawer="true"]'
                );

                if (elements && elements.length > 0) {
                    for (var i = 0, len = elements.length; i < len; i++) {
                        var drawer = SavoMartDrawer.getInstance(elements[i]);
                        if (drawer) {
                            drawer.update();
                        }
                    }
                }
            },
            200
        );
    });
};

// Global initialization
SavoMartDrawer.init = function () {
    SavoMartDrawer.initDrawers();
    SavoMartDrawer.createInstances();

    if (SavoMartDrawerHandlersInitialized === false) {
        SavoMartDrawer.handleResize();
        SavoMartDrawer.handleShow();
        SavoMartDrawer.handleDismiss();

        SavoMartDrawerHandlersInitialized = true;
    }
};

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = SavoMartDrawer;
}
