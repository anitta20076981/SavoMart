"use strict";

// Class definition
var SavoMartFeedback = function (options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    var the = this;

    // Default options
    var defaultOptions = {
        'width': 100,
        'placement': 'top-center',
        'content': '',
        'type': 'popup'
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    var _construct = function () {
        _init();
    }

    var _init = function () {
        // Variables
        the.options = SavoMartUtil.deepExtend({}, defaultOptions, options);
        the.uid = SavoMartUtil.getUniqueId('feedback');
        the.element;
        the.shown = false;

        // Event Handlers
        _handlers();

        // Bind Instance
        SavoMartUtil.data(the.element).set('feedback', the);
    }

    var _handlers = function () {
        SavoMartUtil.addEvent(the.element, 'click', function (e) {
            e.preventDefault();

            _go();
        });
    }

    var _show = function () {
        if (SavoMartEventHandler.trigger(the.element, 'kt.feedback.show', the) === false) {
            return;
        }

        if (the.options.type === 'popup') {
            _showPopup();
        }

        SavoMartEventHandler.trigger(the.element, 'kt.feedback.shown', the);

        return the;
    }

    var _hide = function () {
        if (SavoMartEventHandler.trigger(the.element, 'kt.feedback.hide', the) === false) {
            return;
        }

        if (the.options.type === 'popup') {
            _hidePopup();
        }

        the.shown = false;

        SavoMartEventHandler.trigger(the.element, 'kt.feedback.hidden', the);

        return the;
    }

    var _showPopup = function () {
        the.element = document.createElement("DIV");

        SavoMartUtil.addClass(the.element, 'feedback feedback-popup');
        SavoMartUtil.setHTML(the.element, the.options.content);

        if (the.options.placement == 'top-center') {
            _setPopupTopCenterPosition();
        }

        document.body.appendChild(the.element);

        SavoMartUtil.addClass(the.element, 'feedback-shown');

        the.shown = true;
    }

    var _setPopupTopCenterPosition = function () {
        var width = SavoMartUtil.getResponsiveValue(the.options.width);
        var height = SavoMartUtil.css(the.element, 'height');

        SavoMartUtil.addClass(the.element, 'feedback-top-center');

        SavoMartUtil.css(the.element, 'width', width);
        SavoMartUtil.css(the.element, 'left', '50%');
        SavoMartUtil.css(the.element, 'top', '-' + height);
    }

    var _hidePopup = function () {
        the.element.remove();
    }

    var _destroy = function () {
        SavoMartUtil.data(the.element).remove('feedback');
    }

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.show = function () {
        return _show();
    }

    the.hide = function () {
        return _hide();
    }

    the.isShown = function () {
        return the.shown;
    }

    the.getElement = function () {
        return the.element;
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

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = SavoMartFeedback;
}