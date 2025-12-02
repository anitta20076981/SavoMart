"use strict";

// Class definition
var SavoMartImageInput = function (element, options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    var the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default Options
    var defaultOptions = {

    };

    ////////////////////////////
    // ** Private Methods  ** //
    ////////////////////////////

    var _construct = function () {
        if (SavoMartUtil.data(element).has('image-input') === true) {
            the = SavoMartUtil.data(element).get('image-input');
        } else {
            _init();
        }
    }

    var _init = function () {
        // Variables
        the.options = SavoMartUtil.deepExtend({}, defaultOptions, options);
        the.uid = SavoMartUtil.getUniqueId('image-input');

        // Elements
        the.element = element;
        the.formElement = element.closest(".form");
        the.inputElement = SavoMartUtil.find(element, 'input[type="file"]');
        the.wrapperElement = SavoMartUtil.find(element, '.image-input-wrapper');
        the.cancelElement = SavoMartUtil.find(element, '[data-kt-image-input-action="cancel"]');
        the.removeElement = SavoMartUtil.find(element, '[data-kt-image-input-action="remove"]');
        the.resetElement = SavoMartUtil.find(the.formElement, '[type="reset"]');
        the.hiddenElement = SavoMartUtil.find(element, 'input[type="hidden"]');
        the.src = SavoMartUtil.css(the.wrapperElement, 'backgroundImage');

        // Set initialized
        the.element.setAttribute('data-kt-image-input', 'true');

        // Event Handlers
        _handlers();

        // Bind Instance
        SavoMartUtil.data(the.element).set('image-input', the);
    }

    // Init Event Handlers
    var _handlers = function () {
        SavoMartUtil.addEvent(the.inputElement, 'change', _change);
        SavoMartUtil.addEvent(the.cancelElement, 'click', _cancel);
        SavoMartUtil.addEvent(the.removeElement, 'click', _remove);
        SavoMartUtil.addEvent(the.resetElement, 'click', _reset);
    }

    // Event Handlers
    var _change = function (e) {
        e.preventDefault();

        if (the.inputElement !== null && the.inputElement.files && the.inputElement.files[0]) {
            // Fire change event
            if (SavoMartEventHandler.trigger(the.element, 'kt.imageinput.change', the) === false) {
                return;
            }

            var reader = new FileReader();

            reader.onload = function (e) {
                SavoMartUtil.css(the.wrapperElement, 'background-image', 'url(' + e.target.result + ')');
            }

            reader.readAsDataURL(the.inputElement.files[0]);

            the.element.classList.add('image-input-changed');
            the.element.classList.remove('image-input-empty');

            // Fire removed event
            SavoMartEventHandler.trigger(the.element, 'kt.imageinput.changed', the);
        }
    }

    var _cancel = function (e) {
        e.preventDefault();

        // Fire cancel event
        if (SavoMartEventHandler.trigger(the.element, 'kt.imageinput.cancel', the) === false) {
            return;
        }

        the.element.classList.remove('image-input-changed');
        the.element.classList.remove('image-input-empty');

        if (the.src === 'none') {
            SavoMartUtil.css(the.wrapperElement, 'background-image', '');
            the.element.classList.add('image-input-empty');
        } else {
            SavoMartUtil.css(the.wrapperElement, 'background-image', the.src);
        }

        the.inputElement.value = "";

        if (the.hiddenElement !== null) {
            the.hiddenElement.value = "0";
        }

        // Fire canceled event
        SavoMartEventHandler.trigger(the.element, 'kt.imageinput.canceled', the);
    }

    var _remove = function (e) {
        e.preventDefault();

        // Fire remove event
        if (SavoMartEventHandler.trigger(the.element, 'kt.imageinput.remove', the) === false) {
            return;
        }

        the.element.classList.remove('image-input-changed');
        the.element.classList.add('image-input-empty');

        SavoMartUtil.css(the.wrapperElement, 'background-image', "none");
        the.inputElement.value = "";

        if (the.hiddenElement !== null) {
            the.hiddenElement.value = "1";
        }

        // Fire removed event
        SavoMartEventHandler.trigger(the.element, 'kt.imageinput.removed', the);
    }

    var _reset = function (e) {
        if (the.element.classList.contains('image-input-changed')) {
            // Fire remove event
            if (SavoMartEventHandler.trigger(the.element, 'kt.imageinput.reset', the) === false) {
                return;
            }

            var oldImage = the.wrapperElement.getAttribute('data-image');
            if (oldImage) {
                SavoMartUtil.css(the.wrapperElement, 'background-image', 'url(' + oldImage + ')');
            } else {
                the.element.classList.add('image-input-empty');
                the.element.classList.remove('image-input-changed');
                SavoMartUtil.css(the.wrapperElement, 'background-image', "none");

                if (the.hiddenElement !== null) {
                    the.hiddenElement.value = "1";
                }
            }

            the.inputElement.value = "";

            // Fire removed event
            SavoMartEventHandler.trigger(the.element, 'kt.imageinput.reseted', the);
        }
    }

    var _destroy = function () {
        SavoMartUtil.data(the.element).remove('image-input');
    }

    // Construct Class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.getInputElement = function () {
        return the.inputElement;
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

// Static methods
SavoMartImageInput.getInstance = function (element) {
    if (element !== null && SavoMartUtil.data(element).has('image-input')) {
        return SavoMartUtil.data(element).get('image-input');
    } else {
        return null;
    }
}

// Create instances
SavoMartImageInput.createInstances = function (selector = '[data-kt-image-input]') {
    // Initialize Menus
    var elements = document.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            new SavoMartImageInput(elements[i]);
        }
    }
}

// Global initialization
SavoMartImageInput.init = function () {
    SavoMartImageInput.createInstances();
};

// Webpack Support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = SavoMartImageInput;
}