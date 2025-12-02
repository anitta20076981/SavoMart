"use strict";

// Class definition
var SavoMartScrolltop = function (element, options) {
    ////////////////////////////
    // ** Private variables  ** //
    ////////////////////////////
    var the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default options
    var defaultOptions = {
        offset: 300,
        speed: 600
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    var _construct = function () {
        if (SavoMartUtil.data(element).has('scrolltop')) {
            the = SavoMartUtil.data(element).get('scrolltop');
        } else {
            _init();
        }
    }

    var _init = function () {
        // Variables
        the.options = SavoMartUtil.deepExtend({}, defaultOptions, options);
        the.uid = SavoMartUtil.getUniqueId('scrolltop');
        the.element = element;

        // Set initialized
        the.element.setAttribute('data-kt-scrolltop', 'true');

        // Event Handlers
        _handlers();

        // Bind Instance
        SavoMartUtil.data(the.element).set('scrolltop', the);
    }

    var _handlers = function () {
        var timer;

        window.addEventListener('scroll', function () {
            SavoMartUtil.throttle(timer, function () {
                _scroll();
            }, 200);
        });

        SavoMartUtil.addEvent(the.element, 'click', function (e) {
            e.preventDefault();

            _go();
        });
    }

    var _scroll = function () {
        var offset = parseInt(_getOption('offset'));

        var pos = SavoMartUtil.getScrollTop(); // current vertical position

        if (pos > offset) {
            if (document.body.hasAttribute('data-kt-scrolltop') === false) {
                document.body.setAttribute('data-kt-scrolltop', 'on');
            }
        } else {
            if (document.body.hasAttribute('data-kt-scrolltop') === true) {
                document.body.removeAttribute('data-kt-scrolltop');
            }
        }
    }

    var _go = function () {
        var speed = parseInt(_getOption('speed'));

        window.scrollTo({ top: 0, behavior: 'smooth' });
        //SavoMartUtil.scrollTop(0, speed);
    }

    var _getOption = function (name) {
        if (the.element.hasAttribute('data-kt-scrolltop-' + name) === true) {
            var attr = the.element.getAttribute('data-kt-scrolltop-' + name);
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
        SavoMartUtil.data(the.element).remove('scrolltop');
    }

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.go = function () {
        return _go();
    }

    the.getElement = function () {
        return the.element;
    }

    the.destroy = function () {
        return _destroy();
    }
};

// Static methods
SavoMartScrolltop.getInstance = function (element) {
    if (element && SavoMartUtil.data(element).has('scrolltop')) {
        return SavoMartUtil.data(element).get('scrolltop');
    } else {
        return null;
    }
}

// Create instances
SavoMartScrolltop.createInstances = function (selector = '[data-kt-scrolltop="true"]') {
    // Initialize Menus
    var elements = document.body.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            new SavoMartScrolltop(elements[i]);
        }
    }
}

// Global initialization
SavoMartScrolltop.init = function () {
    SavoMartScrolltop.createInstances();
};

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = SavoMartScrolltop;
}