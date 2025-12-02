"use strict";

var SavoMartSwapperHandlersInitialized = false;

// Class definition
var SavoMartSwapper = function (element, options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    var the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default Options
    var defaultOptions = {
        mode: 'append'
    };

    ////////////////////////////
    // ** Private Methods  ** //
    ////////////////////////////

    var _construct = function () {
        if (SavoMartUtil.data(element).has('swapper') === true) {
            the = SavoMartUtil.data(element).get('swapper');
        } else {
            _init();
        }
    }

    var _init = function () {
        the.element = element;
        the.options = SavoMartUtil.deepExtend({}, defaultOptions, options);

        // Set initialized
        the.element.setAttribute('data-kt-swapper', 'true');

        // Initial update
        _update();

        // Bind Instance
        SavoMartUtil.data(the.element).set('swapper', the);
    }

    var _update = function (e) {
        var parentSelector = _getOption('parent');

        var mode = _getOption('mode');
        var parentElement = parentSelector ? document.querySelector(parentSelector) : null;


        if (parentElement && element.parentNode !== parentElement) {
            if (mode === 'prepend') {
                parentElement.prepend(element);
            } else if (mode === 'append') {
                parentElement.append(element);
            }
        }
    }

    var _getOption = function (name) {
        if (the.element.hasAttribute('data-kt-swapper-' + name) === true) {
            var attr = the.element.getAttribute('data-kt-swapper-' + name);
            var value = SavoMartUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === 'true') {
                value = true;
            } else if (value !== null && String(value) === 'false') {
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
    }

    var _destroy = function () {
        SavoMartUtil.data(the.element).remove('swapper');
    }

    // Construct Class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Methods
    the.update = function () {
        _update();
    }

    the.destroy = function () {
        return _destroy();
    }

    // Event API
    the.on = function (name, handler) {
        return SavoMartEventHandler.on(the.element, name, handler);
    }

    the.one = function (name, handler) {
        return SavoMartEventHandler.one(the.element, name, handler);
    }

    the.off = function (name, handlerId) {
        return SavoMartEventHandler.off(the.element, name, handlerId);
    }

    the.trigger = function (name, event) {
        return SavoMartEventHandler.trigger(the.element, name, event, the, event);
    }
};

// Static methods
SavoMartSwapper.getInstance = function (element) {
    if (element !== null && SavoMartUtil.data(element).has('swapper')) {
        return SavoMartUtil.data(element).get('swapper');
    } else {
        return null;
    }
}

// Create instances
SavoMartSwapper.createInstances = function (selector = '[data-kt-swapper="true"]') {
    // Initialize Menus
    var elements = document.querySelectorAll(selector);
    var swapper;

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            swapper = new SavoMartSwapper(elements[i]);
        }
    }
}

// Window resize handler
SavoMartSwapper.handleResize = function () {
    window.addEventListener('resize', function () {
        var timer;

        SavoMartUtil.throttle(timer, function () {
            // Locate and update Offcanvas instances on window resize
            var elements = document.querySelectorAll('[data-kt-swapper="true"]');

            if (elements && elements.length > 0) {
                for (var i = 0, len = elements.length; i < len; i++) {
                    var swapper = SavoMartSwapper.getInstance(elements[i]);
                    if (swapper) {
                        swapper.update();
                    }
                }
            }
        }, 200);
    });
};

// Global initialization
SavoMartSwapper.init = function () {
    SavoMartSwapper.createInstances();

    if (SavoMartSwapperHandlersInitialized === false) {
        SavoMartSwapper.handleResize();
        SavoMartSwapperHandlersInitialized = true;
    }
};

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = SavoMartSwapper;
}