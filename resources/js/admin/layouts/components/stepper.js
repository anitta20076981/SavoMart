"use strict";

// Class definition
var SavoMartStepper = function (element, options) {
    //////////////////////////////
    // ** Private variables  ** //
    //////////////////////////////
    var the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default Options
    var defaultOptions = {
        startIndex: 1,
        animation: false,
        animationSpeed: '0.3s',
        animationNextClass: 'animate__animated animate__slideInRight animate__fast',
        animationPreviousClass: 'animate__animated animate__slideInLeft animate__fast'
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    var _construct = function () {
        if (SavoMartUtil.data(element).has('stepper') === true) {
            the = SavoMartUtil.data(element).get('stepper');
        } else {
            _init();
        }
    }

    var _init = function () {
        the.options = SavoMartUtil.deepExtend({}, defaultOptions, options);
        the.uid = SavoMartUtil.getUniqueId('stepper');

        the.element = element;

        // Set initialized
        the.element.setAttribute('data-kt-stepper', 'true');

        // Elements
        the.steps = SavoMartUtil.findAll(the.element, '[data-kt-stepper-element="nav"]');
        the.btnNext = SavoMartUtil.find(the.element, '[data-kt-stepper-action="next"]');
        the.btnPrevious = SavoMartUtil.find(the.element, '[data-kt-stepper-action="previous"]');
        the.btnSubmit = SavoMartUtil.find(the.element, '[data-kt-stepper-action="submit"]');

        // Variables
        the.totalStepsNumber = the.steps.length;
        the.passedStepIndex = 0;
        the.currentStepIndex = 1;
        the.clickedStepIndex = 0;

        // Set Current Step
        if (the.options.startIndex > 1) {
            _goTo(the.options.startIndex);
        }

        // Event listeners
        the.nextListener = function (e) {
            e.preventDefault();

            SavoMartEventHandler.trigger(the.element, 'kt.stepper.next', the);
        };

        the.previousListener = function (e) {
            e.preventDefault();

            SavoMartEventHandler.trigger(the.element, 'kt.stepper.previous', the);
        };

        the.stepListener = function (e) {
            e.preventDefault();

            if (the.steps && the.steps.length > 0) {
                for (var i = 0, len = the.steps.length; i < len; i++) {
                    if (the.steps[i] === this) {
                        the.clickedStepIndex = i + 1;

                        SavoMartEventHandler.trigger(the.element, 'kt.stepper.click', the);

                        return;
                    }
                }
            }
        };

        // Event Handlers
        SavoMartUtil.addEvent(the.btnNext, 'click', the.nextListener);

        SavoMartUtil.addEvent(the.btnPrevious, 'click', the.previousListener);

        the.stepListenerId = SavoMartUtil.on(the.element, '[data-kt-stepper-action="step"]', 'click', the.stepListener);

        // Bind Instance
        SavoMartUtil.data(the.element).set('stepper', the);
    }

    var _goTo = function (index) {
        // Trigger "change" event
        SavoMartEventHandler.trigger(the.element, 'kt.stepper.change', the);

        // Skip if this step is already shown
        if (index === the.currentStepIndex || index > the.totalStepsNumber || index < 0) {
            return;
        }

        // Validate step number
        index = parseInt(index);

        // Set current step
        the.passedStepIndex = the.currentStepIndex;
        the.currentStepIndex = index;

        // Refresh elements
        _refreshUI();

        // Trigger "changed" event
        SavoMartEventHandler.trigger(the.element, 'kt.stepper.changed', the);

        return the;
    }

    var _goNext = function () {
        return _goTo(_getNextStepIndex());
    }

    var _goPrevious = function () {
        return _goTo(_getPreviousStepIndex());
    }

    var _goLast = function () {
        return _goTo(_getLastStepIndex());
    }

    var _goFirst = function () {
        return _goTo(_getFirstStepIndex());
    }

    var _refreshUI = function () {
        var state = '';

        if (_isLastStep()) {
            state = 'last';
        } else if (_isFirstStep()) {
            state = 'first';
        } else {
            state = 'between';
        }

        // Set state class
        SavoMartUtil.removeClass(the.element, 'last');
        SavoMartUtil.removeClass(the.element, 'first');
        SavoMartUtil.removeClass(the.element, 'between');

        SavoMartUtil.addClass(the.element, state);

        // Step Items
        var elements = SavoMartUtil.findAll(the.element, '[data-kt-stepper-element="nav"], [data-kt-stepper-element="content"], [data-kt-stepper-element="info"]');

        if (elements && elements.length > 0) {
            for (var i = 0, len = elements.length; i < len; i++) {
                var element = elements[i];
                var index = SavoMartUtil.index(element) + 1;

                SavoMartUtil.removeClass(element, 'current');
                SavoMartUtil.removeClass(element, 'completed');
                SavoMartUtil.removeClass(element, 'pending');

                if (index == the.currentStepIndex) {
                    SavoMartUtil.addClass(element, 'current');

                    if (the.options.animation !== false && element.getAttribute('data-kt-stepper-element') == 'content') {
                        SavoMartUtil.css(element, 'animationDuration', the.options.animationSpeed);

                        var animation = _getStepDirection(the.passedStepIndex) === 'previous' ? the.options.animationPreviousClass : the.options.animationNextClass;
                        SavoMartUtil.animateClass(element, animation);
                    }
                } else {
                    if (index < the.currentStepIndex) {
                        SavoMartUtil.addClass(element, 'completed');
                    } else {
                        SavoMartUtil.addClass(element, 'pending');
                    }
                }
            }
        }
    }

    var _isLastStep = function () {
        return the.currentStepIndex === the.totalStepsNumber;
    }

    var _isFirstStep = function () {
        return the.currentStepIndex === 1;
    }

    var _isBetweenStep = function () {
        return _isLastStep() === false && _isFirstStep() === false;
    }

    var _getNextStepIndex = function () {
        if (the.totalStepsNumber >= (the.currentStepIndex + 1)) {
            return the.currentStepIndex + 1;
        } else {
            return the.totalStepsNumber;
        }
    }

    var _getPreviousStepIndex = function () {
        if ((the.currentStepIndex - 1) > 1) {
            return the.currentStepIndex - 1;
        } else {
            return 1;
        }
    }

    var _getFirstStepIndex = function () {
        return 1;
    }

    var _getLastStepIndex = function () {
        return the.totalStepsNumber;
    }

    var _getTotalStepsNumber = function () {
        return the.totalStepsNumber;
    }

    var _getStepDirection = function (index) {
        if (index > the.currentStepIndex) {
            return 'next';
        } else {
            return 'previous';
        }
    }

    var _getStepContent = function (index) {
        var content = SavoMartUtil.findAll(the.element, '[data-kt-stepper-element="content"]');

        if (content[index - 1]) {
            return content[index - 1];
        } else {
            return false;
        }
    }

    var _destroy = function () {
        // Event Handlers
        SavoMartUtil.removeEvent(the.btnNext, 'click', the.nextListener);

        SavoMartUtil.removeEvent(the.btnPrevious, 'click', the.previousListener);

        SavoMartUtil.off(the.element, 'click', the.stepListenerId);

        SavoMartUtil.data(the.element).remove('stepper');
    }

    // Construct Class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.getElement = function (index) {
        return the.element;
    }

    the.goTo = function (index) {
        return _goTo(index);
    }

    the.goPrevious = function () {
        return _goPrevious();
    }

    the.goNext = function () {
        return _goNext();
    }

    the.goFirst = function () {
        return _goFirst();
    }

    the.goLast = function () {
        return _goLast();
    }

    the.getCurrentStepIndex = function () {
        return the.currentStepIndex;
    }

    the.getNextStepIndex = function () {
        return _getNextStepIndex();
    }

    the.getPassedStepIndex = function () {
        return the.passedStepIndex;
    }

    the.getClickedStepIndex = function () {
        return the.clickedStepIndex;
    }

    the.getPreviousStepIndex = function () {
        return _getPreviousStepIndex();
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
SavoMartStepper.getInstance = function (element) {
    if (element !== null && SavoMartUtil.data(element).has('stepper')) {
        return SavoMartUtil.data(element).get('stepper');
    } else {
        return null;
    }
}

SavoMartStepper.initStepper = function (selector = '[data-kt-stepper="true"]') {

    var stepper = document.querySelector(selector);

    if (!stepper) {
        return;
    }

    // Initialize Stepper
    SavoMartStepper.stepperObj = new SavoMartStepper(stepper);

    SavoMartStepper.stepperObj.on('kt.stepper.next', function (stepper) {
        var validator = SavoMartStepper.validations[stepper.getCurrentStepIndex()];
        if (validator) {
            validator.validate().then(function (status) {
                if (status == 'Valid') {
                    stepper.goNext();
                    SavoMartUtil.scrollTop();
                } else {
                    SavoMartUtil.scrollTop();
                }
            });
        } else {
            stepper.goNext();
            SavoMartUtil.scrollTop();
        }
    });

    // Prev event
    SavoMartStepper.stepperObj.on('kt.stepper.previous', function (stepper) {
        stepper.goPrevious();
        SavoMartUtil.scrollTop();
    });
}

SavoMartStepper.validations = [];


// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = SavoMartStepper;
}