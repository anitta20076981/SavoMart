"use strict";

var SavoMartStickyHandlersInitialized = false;

// Class definition
var SavoMartSticky = function (element, options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    var the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default Options
    var defaultOptions = {
        offset: 200,
        reverse: false,
        release: null,
        animation: true,
        animationSpeed: '0.3s',
        animationClass: 'animation-slide-in-down'
    };
    ////////////////////////////
    // ** Private Methods  ** //
    ////////////////////////////

    var _construct = function () {
        if (SavoMartUtil.data(element).has('sticky') === true) {
            the = SavoMartUtil.data(element).get('sticky');
        } else {
            _init();
        }
    }

    var _init = function () {
        the.element = element;
        the.options = SavoMartUtil.deepExtend({}, defaultOptions, options);
        the.uid = SavoMartUtil.getUniqueId('sticky');
        the.name = the.element.getAttribute('data-kt-sticky-name');
        the.attributeName = 'data-kt-sticky-' + the.name;
        the.attributeName2 = 'data-kt-' + the.name;
        the.eventTriggerState = true;
        the.lastScrollTop = 0;
        the.scrollHandler;

        // Set initialized
        the.element.setAttribute('data-kt-sticky', 'true');

        // Event Handlers
        window.addEventListener('scroll', _scroll);

        // Initial Launch
        _scroll();

        // Bind Instance
        SavoMartUtil.data(the.element).set('sticky', the);
    }

    var _scroll = function (e) {
        var offset = _getOption('offset');
        var release = _getOption('release');
        var reverse = _getOption('reverse');
        var st;
        var attrName;
        var diff;

        // Exit if false
        if (offset === false) {
            return;
        }

        offset = parseInt(offset);
        release = release ? document.querySelector(release) : null;

        st = SavoMartUtil.getScrollTop();
        diff = document.documentElement.scrollHeight - window.innerHeight - SavoMartUtil.getScrollTop();

        var proceed = (!release || (release.offsetTop - release.clientHeight) > st);

        if (reverse === true) {  // Release on reverse scroll mode
            if (st > offset && proceed) {
                if (document.body.hasAttribute(the.attributeName) === false) {

                    if (_enable() === false) {
                        return;
                    }

                    document.body.setAttribute(the.attributeName, 'on');
                    document.body.setAttribute(the.attributeName2, 'on');
                    the.element.setAttribute("data-kt-sticky-enabled", "true");
                }

                if (the.eventTriggerState === true) {
                    SavoMartEventHandler.trigger(the.element, 'kt.sticky.on', the);
                    SavoMartEventHandler.trigger(the.element, 'kt.sticky.change', the);

                    the.eventTriggerState = false;
                }
            } else { // Back scroll mode
                if (document.body.hasAttribute(the.attributeName) === true) {
                    _disable();
                    document.body.removeAttribute(the.attributeName);
                    document.body.removeAttribute(the.attributeName2);
                    the.element.removeAttribute("data-kt-sticky-enabled");
                }

                if (the.eventTriggerState === false) {
                    SavoMartEventHandler.trigger(the.element, 'kt.sticky.off', the);
                    SavoMartEventHandler.trigger(the.element, 'kt.sticky.change', the);
                    the.eventTriggerState = true;
                }
            }

            the.lastScrollTop = st;
        } else { // Classic scroll mode
            if (st > offset && proceed) {
                if (document.body.hasAttribute(the.attributeName) === false) {

                    if (_enable() === false) {
                        return;
                    }

                    document.body.setAttribute(the.attributeName, 'on');
                    document.body.setAttribute(the.attributeName2, 'on');
                    the.element.setAttribute("data-kt-sticky-enabled", "true");
                }

                if (the.eventTriggerState === true) {
                    SavoMartEventHandler.trigger(the.element, 'kt.sticky.on', the);
                    SavoMartEventHandler.trigger(the.element, 'kt.sticky.change', the);
                    the.eventTriggerState = false;
                }
            } else { // back scroll mode
                if (document.body.hasAttribute(the.attributeName) === true) {
                    _disable();
                    document.body.removeAttribute(the.attributeName);
                    document.body.removeAttribute(the.attributeName2);
                    the.element.removeAttribute("data-kt-sticky-enabled");
                }

                if (the.eventTriggerState === false) {
                    SavoMartEventHandler.trigger(the.element, 'kt.sticky.off', the);
                    SavoMartEventHandler.trigger(the.element, 'kt.sticky.change', the);
                    the.eventTriggerState = true;
                }
            }
        }

        if (release) {
            if (release.offsetTop - release.clientHeight > st) {
                the.element.setAttribute('data-kt-sticky-released', 'true');
            } else {
                the.element.removeAttribute('data-kt-sticky-released');
            }
        }
    }

    var _enable = function (update) {
        var top = _getOption('top');
        top = top ? parseInt(top) : 0;

        var left = _getOption('left');
        var right = _getOption('right');
        var width = _getOption('width');
        var zindex = _getOption('zindex');
        var dependencies = _getOption('dependencies');
        var classes = _getOption('class');

        var height = _calculateHeight();
        var heightOffset = _getOption('height-offset');
        heightOffset = heightOffset ? parseInt(heightOffset) : 0;

        if (height + heightOffset + top > SavoMartUtil.getViewPort().height) {
            return false;
        }

        if (update !== true && _getOption('animation') === true) {
            SavoMartUtil.css(the.element, 'animationDuration', _getOption('animationSpeed'));
            SavoMartUtil.animateClass(the.element, 'animation ' + _getOption('animationClass'));
        }

        if (classes !== null) {
            SavoMartUtil.addClass(the.element, classes);
        }

        if (zindex !== null) {
            SavoMartUtil.css(the.element, 'z-index', zindex);
            SavoMartUtil.css(the.element, 'position', 'fixed');
        }

        if (top >= 0) {
            SavoMartUtil.css(the.element, 'top', String(top) + 'px');
        }

        if (width !== null) {
            if (width['target']) {
                var targetElement = document.querySelector(width['target']);
                if (targetElement) {
                    width = SavoMartUtil.css(targetElement, 'width');
                }
            }

            SavoMartUtil.css(the.element, 'width', width);
        }

        if (left !== null) {
            if (String(left).toLowerCase() === 'auto') {
                var offsetLeft = SavoMartUtil.offset(the.element).left;

                if (offsetLeft >= 0) {
                    SavoMartUtil.css(the.element, 'left', String(offsetLeft) + 'px');
                }
            } else {
                SavoMartUtil.css(the.element, 'left', left);
            }
        }

        if (right !== null) {
            SavoMartUtil.css(the.element, 'right', right);
        }

        // Height dependencies
        if (dependencies !== null) {
            var dependencyElements = document.querySelectorAll(dependencies);

            if (dependencyElements && dependencyElements.length > 0) {
                for (var i = 0, len = dependencyElements.length; i < len; i++) {
                    SavoMartUtil.css(dependencyElements[i], 'padding-top', String(height) + 'px');
                }
            }
        }
    }

    var _disable = function () {
        SavoMartUtil.css(the.element, 'top', '');
        SavoMartUtil.css(the.element, 'width', '');
        SavoMartUtil.css(the.element, 'left', '');
        SavoMartUtil.css(the.element, 'right', '');
        SavoMartUtil.css(the.element, 'z-index', '');
        SavoMartUtil.css(the.element, 'position', '');

        var dependencies = _getOption('dependencies');
        var classes = _getOption('class');

        if (classes !== null) {
            SavoMartUtil.removeClass(the.element, classes);
        }

        // Height dependencies
        if (dependencies !== null) {
            var dependencyElements = document.querySelectorAll(dependencies);

            if (dependencyElements && dependencyElements.length > 0) {
                for (var i = 0, len = dependencyElements.length; i < len; i++) {
                    SavoMartUtil.css(dependencyElements[i], 'padding-top', '');
                }
            }
        }
    }

    var _check = function () {

    }

    var _calculateHeight = function () {
        var height = parseFloat(SavoMartUtil.css(the.element, 'height'));

        height = height + parseFloat(SavoMartUtil.css(the.element, 'margin-top'));
        height = height + parseFloat(SavoMartUtil.css(the.element, 'margin-bottom'));

        if (SavoMartUtil.css(element, 'border-top')) {
            height = height + parseFloat(SavoMartUtil.css(the.element, 'border-top'));
        }

        if (SavoMartUtil.css(element, 'border-bottom')) {
            height = height + parseFloat(SavoMartUtil.css(the.element, 'border-bottom'));
        }

        return height;
    }

    var _getOption = function (name) {
        if (the.element.hasAttribute('data-kt-sticky-' + name) === true) {
            var attr = the.element.getAttribute('data-kt-sticky-' + name);
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
        window.removeEventListener('scroll', _scroll);
        SavoMartUtil.data(the.element).remove('sticky');
    }

    // Construct Class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Methods
    the.update = function () {
        if (document.body.hasAttribute(the.attributeName) === true) {
            _disable();
            document.body.removeAttribute(the.attributeName);
            document.body.removeAttribute(the.attributeName2);
            _enable(true);
            document.body.setAttribute(the.attributeName, 'on');
            document.body.setAttribute(the.attributeName2, 'on');
        }
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
SavoMartSticky.getInstance = function (element) {
    if (element !== null && SavoMartUtil.data(element).has('sticky')) {
        return SavoMartUtil.data(element).get('sticky');
    } else {
        return null;
    }
}

// Create instances
SavoMartSticky.createInstances = function (selector = '[data-kt-sticky="true"]') {
    // Initialize Menus
    var elements = document.body.querySelectorAll(selector);
    var sticky;

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            sticky = new SavoMartSticky(elements[i]);
        }
    }
}

// Window resize handler
SavoMartSticky.handleResize = function () {
    window.addEventListener('resize', function () {
        var timer;

        SavoMartUtil.throttle(timer, function () {
            // Locate and update Offcanvas instances on window resize
            var elements = document.body.querySelectorAll('[data-kt-sticky="true"]');

            if (elements && elements.length > 0) {
                for (var i = 0, len = elements.length; i < len; i++) {
                    var sticky = SavoMartSticky.getInstance(elements[i]);
                    if (sticky) {
                        sticky.update();
                    }
                }
            }
        }, 200);
    });
}

// Global initialization
SavoMartSticky.init = function () {
    SavoMartSticky.createInstances();

    if (SavoMartStickyHandlersInitialized === false) {
        SavoMartSticky.handleResize();
        SavoMartStickyHandlersInitialized = true;
    }
};

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = SavoMartSticky;
}
