"use strict";

var SavoMartMenuHandlersInitialized = false;

// Class definition
var SavoMartMenu = function (element, options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    var the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default Options
    var defaultOptions = {
        dropdown: {
            hoverTimeout: 200,
            zindex: 107
        },

        accordion: {
            slideSpeed: 250,
            expand: false
        }
    };

    ////////////////////////////
    // ** Private Methods  ** //
    ////////////////////////////

    var _construct = function () {
        if (SavoMartUtil.data(element).has('menu') === true) {
            the = SavoMartUtil.data(element).get('menu');
        } else {
            _init();
        }
    }

    var _init = function () {
        the.options = SavoMartUtil.deepExtend({}, defaultOptions, options);
        the.uid = SavoMartUtil.getUniqueId('menu');
        the.element = element;
        the.triggerElement;
        the.disabled = false;

        // Set initialized
        the.element.setAttribute('data-kt-menu', 'true');

        _setTriggerElement();
        _update();

        SavoMartUtil.data(the.element).set('menu', the);
    }

    var _destroy = function () { // todo

    }

    // Event Handlers
    // Toggle handler
    var _click = function (element, e) {
        e.preventDefault();

        if (the.disabled === true) {
            return;
        }

        var item = _getItemElement(element);

        if (_getOptionFromElementAttribute(item, 'trigger') !== 'click') {
            return;
        }

        if (_getOptionFromElementAttribute(item, 'toggle') === false) {
            _show(item);
        } else {
            _toggle(item);
        }
    }

    // Link handler
    var _link = function (element, e) {
        if (the.disabled === true) {
            return;
        }

        if (SavoMartEventHandler.trigger(the.element, 'kt.menu.link.click', element) === false) {
            return;
        }

        // Dismiss all shown dropdowns
        SavoMartMenu.hideDropdowns();

        SavoMartEventHandler.trigger(the.element, 'kt.menu.link.clicked', element);
    }

    // Dismiss handler
    var _dismiss = function (element, e) {
        var item = _getItemElement(element);
        var items = _getItemChildElements(item);

        if (item !== null && _getItemSubType(item) === 'dropdown') {
            _hide(item); // hide items dropdown
            // Hide all child elements as well

            if (items.length > 0) {
                for (var i = 0, len = items.length; i < len; i++) {
                    if (items[i] !== null && _getItemSubType(items[i]) === 'dropdown') {
                        _hide(tems[i]);
                    }
                }
            }
        }
    }

    // Mouseover handle
    var _mouseover = function (element, e) {
        var item = _getItemElement(element);

        if (the.disabled === true) {
            return;
        }

        if (item === null) {
            return;
        }

        if (_getOptionFromElementAttribute(item, 'trigger') !== 'hover') {
            return;
        }

        if (SavoMartUtil.data(item).get('hover') === '1') {
            clearTimeout(SavoMartUtil.data(item).get('timeout'));
            SavoMartUtil.data(item).remove('hover');
            SavoMartUtil.data(item).remove('timeout');
        }

        _show(item);
    }

    // Mouseout handle
    var _mouseout = function (element, e) {
        var item = _getItemElement(element);

        if (the.disabled === true) {
            return;
        }

        if (item === null) {
            return;
        }

        if (_getOptionFromElementAttribute(item, 'trigger') !== 'hover') {
            return;
        }

        var timeout = setTimeout(function () {
            if (SavoMartUtil.data(item).get('hover') === '1') {
                _hide(item);
            }
        }, the.options.dropdown.hoverTimeout);

        SavoMartUtil.data(item).set('hover', '1');
        SavoMartUtil.data(item).set('timeout', timeout);
    }

    // Toggle item sub
    var _toggle = function (item) {
        if (!item) {
            item = the.triggerElement;
        }

        if (_isItemSubShown(item) === true) {
            _hide(item);
        } else {
            _show(item);
        }
    }

    // Show item sub
    var _show = function (item) {
        if (!item) {
            item = the.triggerElement;
        }

        if (_isItemSubShown(item) === true) {
            return;
        }

        if (_getItemSubType(item) === 'dropdown') {
            _showDropdown(item); // // show current dropdown
        } else if (_getItemSubType(item) === 'accordion') {
            _showAccordion(item);
        }

        // Remember last submenu type
        SavoMartUtil.data(item).set('type', _getItemSubType(item)); // updated
    }

    // Hide item sub
    var _hide = function (item) {
        if (!item) {
            item = the.triggerElement;
        }

        if (_isItemSubShown(item) === false) {
            return;
        }

        if (_getItemSubType(item) === 'dropdown') {
            _hideDropdown(item);
        } else if (_getItemSubType(item) === 'accordion') {
            _hideAccordion(item);
        }
    }

    // Reset item state classes if item sub type changed
    var _reset = function (item) {
        if (_hasItemSub(item) === false) {
            return;
        }

        var sub = _getItemSubElement(item);

        // Reset sub state if sub type is changed during the window resize
        if (SavoMartUtil.data(item).has('type') && SavoMartUtil.data(item).get('type') !== _getItemSubType(item)) { // updated
            SavoMartUtil.removeClass(item, 'hover');
            SavoMartUtil.removeClass(item, 'show');
            SavoMartUtil.removeClass(sub, 'show');
        } // updated
    }

    // Update all item state classes if item sub type changed
    var _update = function () {
        var items = the.element.querySelectorAll('.menu-item[data-kt-menu-trigger]');

        if (items && items.length > 0) {
            for (var i = 0, len = items.length; i < len; i++) {
                _reset(items[i]);
            }
        }
    }

    // Set external trigger element
    var _setTriggerElement = function () {
        var target = document.querySelector('[data-kt-menu-target="# ' + the.element.getAttribute('id') + '"]');

        if (target !== null) {
            the.triggerElement = target;
        } else if (the.element.closest('[data-kt-menu-trigger]')) {
            the.triggerElement = the.element.closest('[data-kt-menu-trigger]');
        } else if (the.element.parentNode && SavoMartUtil.child(the.element.parentNode, '[data-kt-menu-trigger]')) {
            the.triggerElement = SavoMartUtil.child(the.element.parentNode, '[data-kt-menu-trigger]');
        }

        if (the.triggerElement) {
            SavoMartUtil.data(the.triggerElement).set('menu', the);
        }
    }

    // Test if menu has external trigger element
    var _isTriggerElement = function (item) {
        return (the.triggerElement === item) ? true : false;
    }

    // Test if item's sub is shown
    var _isItemSubShown = function (item) {
        var sub = _getItemSubElement(item);

        if (sub !== null) {
            if (_getItemSubType(item) === 'dropdown') {
                if (SavoMartUtil.hasClass(sub, 'show') === true && sub.hasAttribute('data-popper-placement') === true) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return SavoMartUtil.hasClass(item, 'show');
            }
        } else {
            return false;
        }
    }

    // Test if item dropdown is permanent
    var _isItemDropdownPermanent = function (item) {
        return _getOptionFromElementAttribute(item, 'permanent') === true ? true : false;
    }

    // Test if item's parent is shown
    var _isItemParentShown = function (item) {
        return SavoMartUtil.parents(item, '.menu-item.show').length > 0;
    }

    // Test of it is item sub element
    var _isItemSubElement = function (item) {
        return SavoMartUtil.hasClass(item, 'menu-sub');
    }

    // Test if item has sub
    var _hasItemSub = function (item) {
        return (SavoMartUtil.hasClass(item, 'menu-item') && item.hasAttribute('data-kt-menu-trigger'));
    }

    // Get link element
    var _getItemLinkElement = function (item) {
        return SavoMartUtil.child(item, '.menu-link');
    }

    // Get toggle element
    var _getItemToggleElement = function (item) {
        if (the.triggerElement) {
            return the.triggerElement;
        } else {
            return _getItemLinkElement(item);
        }
    }

    // Get item sub element
    var _getItemSubElement = function (item) {
        if (_isTriggerElement(item) === true) {
            return the.element;
        }
        if (item.classList.contains('menu-sub') === true) {
            return item;
        } else if (SavoMartUtil.data(item).has('sub')) {
            return SavoMartUtil.data(item).get('sub');
        } else {
            return SavoMartUtil.child(item, '.menu-sub');
        }
    }

    // Get item sub type
    var _getItemSubType = function (element) {
        var sub = _getItemSubElement(element);

        if (sub && parseInt(SavoMartUtil.css(sub, 'z-index')) > 0) {
            return "dropdown";
        } else {
            return "accordion";
        }
    }

    // Get item element
    var _getItemElement = function (element) {
        var item, sub;

        // Element is the external trigger element
        if (_isTriggerElement(element)) {
            return element;
        }

        // Element has item toggler attribute
        if (element.hasAttribute('data-kt-menu-trigger')) {
            return element;
        }

        // Element has item DOM reference in it's data storage
        if (SavoMartUtil.data(element).has('item')) {
            return SavoMartUtil.data(element).get('item');
        }

        // Item is parent of element
        if ((item = element.closest('.menu-item[data-kt-menu-trigger]'))) {
            return item;
        }

        // Element's parent has item DOM reference in it's data storage
        if ((sub = element.closest('.menu-sub'))) {
            if (SavoMartUtil.data(sub).has('item') === true) {
                return SavoMartUtil.data(sub).get('item')
            }
        }
    }

    // Get item parent element
    var _getItemParentElement = function (item) {
        var sub = item.closest('.menu-sub');
        var parentItem;

        if (SavoMartUtil.data(sub).has('item')) {
            return SavoMartUtil.data(sub).get('item');
        }

        if (sub && (parentItem = sub.closest('.menu-item[data-kt-menu-trigger]'))) {
            return parentItem;
        }

        return null;
    }

    // Get item parent elements
    var _getItemParentElements = function (item) {
        var parents = [];
        var parent;
        var i = 0;

        do {
            parent = _getItemParentElement(item);

            if (parent) {
                parents.push(parent);
                item = parent;
            }

            i++;
        } while (parent !== null && i < 20);

        if (the.triggerElement) {
            parents.unshift(the.triggerElement);
        }

        return parents;
    }

    // Get item child element
    var _getItemChildElement = function (item) {
        var selector = item;
        var element;

        if (SavoMartUtil.data(item).get('sub')) {
            selector = SavoMartUtil.data(item).get('sub');
        }

        if (selector !== null) {
            //element = selector.querySelector('.show.menu-item[data-kt-menu-trigger]');
            element = selector.querySelector('.menu-item[data-kt-menu-trigger]');

            if (element) {
                return element;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    // Get item child elements
    var _getItemChildElements = function (item) {
        var children = [];
        var child;
        var i = 0;

        do {
            child = _getItemChildElement(item);

            if (child) {
                children.push(child);
                item = child;
            }

            i++;
        } while (child !== null && i < 20);

        return children;
    }

    // Show item dropdown
    var _showDropdown = function (item) {
        // Handle dropdown show event
        if (SavoMartEventHandler.trigger(the.element, 'kt.menu.dropdown.show', item) === false) {
            return;
        }

        // Hide all currently shown dropdowns except current one
        SavoMartMenu.hideDropdowns(item);

        var toggle = _isTriggerElement(item) ? item : _getItemLinkElement(item);
        var sub = _getItemSubElement(item);

        var width = _getOptionFromElementAttribute(item, 'width');
        var height = _getOptionFromElementAttribute(item, 'height');

        var zindex = the.options.dropdown.zindex; // update
        var parentZindex = SavoMartUtil.getHighestZindex(item); // update

        // Apply a new z-index if dropdown's toggle element or it's parent has greater z-index // update
        if (parentZindex !== null && parentZindex >= zindex) {
            zindex = parentZindex + 1;
        }

        if (zindex > 0) {
            SavoMartUtil.css(sub, 'z-index', zindex);
        }

        if (width !== null) {
            SavoMartUtil.css(sub, 'width', width);
        }

        if (height !== null) {
            SavoMartUtil.css(sub, 'height', height);
        }

        SavoMartUtil.css(sub, 'display', '');
        SavoMartUtil.css(sub, 'overflow', '');

        // Init popper(new)
        _initDropdownPopper(item, sub);

        SavoMartUtil.addClass(item, 'show');
        SavoMartUtil.addClass(item, 'menu-dropdown');
        SavoMartUtil.addClass(sub, 'show');

        // Append the sub the the root of the menu
        if (_getOptionFromElementAttribute(item, 'overflow') === true) {
            document.body.appendChild(sub);
            SavoMartUtil.data(item).set('sub', sub);
            SavoMartUtil.data(sub).set('item', item);
            SavoMartUtil.data(sub).set('menu', the);
        } else {
            SavoMartUtil.data(sub).set('item', item);
        }

        // Handle dropdown shown event
        SavoMartEventHandler.trigger(the.element, 'kt.menu.dropdown.shown', item);
    }

    // Hide item dropdown
    var _hideDropdown = function (item) {
        // Handle dropdown hide event
        if (SavoMartEventHandler.trigger(the.element, 'kt.menu.dropdown.hide', item) === false) {
            return;
        }

        var sub = _getItemSubElement(item);

        SavoMartUtil.css(sub, 'z-index', '');
        SavoMartUtil.css(sub, 'width', '');
        SavoMartUtil.css(sub, 'height', '');

        SavoMartUtil.removeClass(item, 'show');
        SavoMartUtil.removeClass(item, 'menu-dropdown');
        SavoMartUtil.removeClass(sub, 'show');

        // Append the sub back to it's parent
        if (_getOptionFromElementAttribute(item, 'overflow') === true) {
            if (item.classList.contains('menu-item')) {
                item.appendChild(sub);
            } else {
                SavoMartUtil.insertAfter(the.element, item);
            }

            SavoMartUtil.data(item).remove('sub');
            SavoMartUtil.data(sub).remove('item');
            SavoMartUtil.data(sub).remove('menu');
        }

        // Destroy popper(new)
        _destroyDropdownPopper(item);

        // Handle dropdown hidden event
        SavoMartEventHandler.trigger(the.element, 'kt.menu.dropdown.hidden', item);
    }

    // Init dropdown popper(new)
    var _initDropdownPopper = function (item, sub) {
        // Setup popper instance
        var reference;
        var attach = _getOptionFromElementAttribute(item, 'attach');

        if (attach) {
            if (attach === 'parent') {
                reference = item.parentNode;
            } else {
                reference = document.querySelector(attach);
            }
        } else {
            reference = item;
        }

        var popper = Popper.createPopper(reference, sub, _getDropdownPopperConfig(item));
        SavoMartUtil.data(item).set('popper', popper);
    }

    // Destroy dropdown popper(new)
    var _destroyDropdownPopper = function (item) {
        if (SavoMartUtil.data(item).has('popper') === true) {
            SavoMartUtil.data(item).get('popper').destroy();
            SavoMartUtil.data(item).remove('popper');
        }
    }

    // Prepare popper config for dropdown(see: https://popper.js.org/docs/v2/)
    var _getDropdownPopperConfig = function (item) {
        // Placement
        var placement = _getOptionFromElementAttribute(item, 'placement');
        if (!placement) {
            placement = 'right';
        }

        // Offset
        var offsetValue = _getOptionFromElementAttribute(item, 'offset');
        var offset = offsetValue ? offsetValue.split(",") : [];

        if (offset.length === 2) {
            offset[0] = parseInt(offset[0]);
            offset[1] = parseInt(offset[1]);
        }

        // Strategy
        var strategy = _getOptionFromElementAttribute(item, 'overflow') === true ? 'absolute' : 'fixed';

        var altAxis = _getOptionFromElementAttribute(item, 'flip') !== false ? true : false;

        var popperConfig = {
            placement: placement,
            strategy: strategy,
            modifiers: [{
                name: 'offset',
                options: {
                    offset: offset
                }
            }, {
                name: 'preventOverflow',
                options: {
                    altAxis: altAxis
                }
            }, {
                name: 'flip',
                options: {
                    flipVariations: false
                }
            }]
        };

        return popperConfig;
    }

    // Show item accordion
    var _showAccordion = function (item) {
        if (SavoMartEventHandler.trigger(the.element, 'kt.menu.accordion.show', item) === false) {
            return;
        }

        var sub = _getItemSubElement(item);
        var expand = the.options.accordion.expand;

        if (_getOptionFromElementAttribute(item, 'expand') === true) {
            expand = true;
        } else if (_getOptionFromElementAttribute(item, 'expand') === false) {
            expand = false;
        } else if (_getOptionFromElementAttribute(the.element, 'expand') === true) {
            expand = true;
        }

        if (expand === false) {
            _hideAccordions(item);
        }

        if (SavoMartUtil.data(item).has('popper') === true) {
            _hideDropdown(item);
        }

        SavoMartUtil.addClass(item, 'hover');

        SavoMartUtil.addClass(item, 'showing');

        SavoMartUtil.slideDown(sub, the.options.accordion.slideSpeed, function () {
            SavoMartUtil.removeClass(item, 'showing');
            SavoMartUtil.addClass(item, 'show');
            SavoMartUtil.addClass(sub, 'show');

            SavoMartEventHandler.trigger(the.element, 'kt.menu.accordion.shown', item);
        });
    }

    // Hide item accordion
    var _hideAccordion = function (item) {
        if (SavoMartEventHandler.trigger(the.element, 'kt.menu.accordion.hide', item) === false) {
            return;
        }

        var sub = _getItemSubElement(item);

        SavoMartUtil.addClass(item, 'hiding');

        SavoMartUtil.slideUp(sub, the.options.accordion.slideSpeed, function () {
            SavoMartUtil.removeClass(item, 'hiding');
            SavoMartUtil.removeClass(item, 'show');
            SavoMartUtil.removeClass(sub, 'show');

            SavoMartUtil.removeClass(item, 'hover'); // update

            SavoMartEventHandler.trigger(the.element, 'kt.menu.accordion.hidden', item);
        });
    }

    var _setActiveLink = function (link) {
        var item = _getItemElement(link);
        var parentItems = _getItemParentElements(item);
        var parentTabPane = link.closest('.tab-pane');

        var activeLinks = [].slice.call(the.element.querySelectorAll('.menu-link.active'));
        var activeParentItems = [].slice.call(the.element.querySelectorAll('.menu-item.here, .menu-item.show'));

        if (_getItemSubType(item) === "accordion") {
            _showAccordion(item);
        } else {
            item.classList.add("here");
        }

        if (parentItems && parentItems.length > 0) {
            for (var i = 0, len = parentItems.length; i < len; i++) {
                var parentItem = parentItems[i];

                if (_getItemSubType(parentItem) === "accordion") {
                    _showAccordion(parentItem);
                } else {
                    parentItem.classList.add("here");
                }
            }
        }

        activeLinks.map(function (activeLink) {
            activeLink.classList.remove("active");
        });

        activeParentItems.map(function (activeParentItem) {
            if (activeParentItem.contains(item) === false) {
                activeParentItem.classList.remove("here");
                activeParentItem.classList.remove("show");
            }
        });

        // Handle tab
        if (parentTabPane && bootstrap.Tab) {
            var tabEl = the.element.querySelector('[data-bs-target="#' + parentTabPane.getAttribute("id") + '"]');
            var tab = new bootstrap.Tab(tabEl);

            if (tab) {
                tab.show();
            }
        }

        link.classList.add("active");
    }

    var _getLinkByAttribute = function (value, name = "href") {
        var link = the.element.querySelector('a[' + name + '="' + value + '"]');

        if (link) {
            return link;
        } else {
            null;
        }
    }

    // Hide all shown accordions of item
    var _hideAccordions = function (item) {
        var itemsToHide = SavoMartUtil.findAll(the.element, '.show[data-kt-menu-trigger]');
        var itemToHide;

        if (itemsToHide && itemsToHide.length > 0) {
            for (var i = 0, len = itemsToHide.length; i < len; i++) {
                itemToHide = itemsToHide[i];

                if (_getItemSubType(itemToHide) === 'accordion' && itemToHide !== item && item.contains(itemToHide) === false && itemToHide.contains(item) === false) {
                    _hideAccordion(itemToHide);
                }
            }
        }
    }

    // Get item option(through html attributes)
    var _getOptionFromElementAttribute = function (item, name) {
        var attr;
        var value = null;

        if (item && item.hasAttribute('data-kt-menu-' + name)) {
            attr = item.getAttribute('data-kt-menu-' + name);
            value = SavoMartUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === 'true') {
                value = true;
            } else if (value !== null && String(value) === 'false') {
                value = false;
            }
        }

        return value;
    }

    var _destroy = function () {
        SavoMartUtil.data(the.element).remove('menu');
    }

    // Construct Class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Event Handlers
    the.click = function (element, e) {
        return _click(element, e);
    }

    the.link = function (element, e) {
        return _link(element, e);
    }

    the.dismiss = function (element, e) {
        return _dismiss(element, e);
    }

    the.mouseover = function (element, e) {
        return _mouseover(element, e);
    }

    the.mouseout = function (element, e) {
        return _mouseout(element, e);
    }

    // General Methods
    the.getItemTriggerType = function (item) {
        return _getOptionFromElementAttribute(item, 'trigger');
    }

    the.getItemSubType = function (element) {
        return _getItemSubType(element);
    }

    the.show = function (item) {
        return _show(item);
    }

    the.hide = function (item) {
        return _hide(item);
    }

    the.toggle = function (item) {
        return _toggle(item);
    }

    the.reset = function (item) {
        return _reset(item);
    }

    the.update = function () {
        return _update();
    }

    the.getElement = function () {
        return the.element;
    }

    the.setActiveLink = function (link) {
        return _setActiveLink(link);
    }

    the.getLinkByAttribute = function (value, name = "href") {
        return _getLinkByAttribute(value, name);
    }

    the.getItemLinkElement = function (item) {
        return _getItemLinkElement(item);
    }

    the.getItemToggleElement = function (item) {
        return _getItemToggleElement(item);
    }

    the.getItemSubElement = function (item) {
        return _getItemSubElement(item);
    }

    the.getItemParentElements = function (item) {
        return _getItemParentElements(item);
    }

    the.isItemSubShown = function (item) {
        return _isItemSubShown(item);
    }

    the.isItemParentShown = function (item) {
        return _isItemParentShown(item);
    }

    the.getTriggerElement = function () {
        return the.triggerElement;
    }

    the.isItemDropdownPermanent = function (item) {
        return _isItemDropdownPermanent(item);
    }

    the.destroy = function () {
        return _destroy();
    }

    the.disable = function () {
        the.disabled = true;
    }

    the.enable = function () {
        the.disabled = false;
    }

    // Accordion Mode Methods
    the.hideAccordions = function (item) {
        return _hideAccordions(item);
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
};

// Get SavoMartMenu instance by element
SavoMartMenu.getInstance = function (element) {
    var menu;
    var item;

    if (!element) {
        return null;
    }

    // Element has menu DOM reference in it's DATA storage
    if (SavoMartUtil.data(element).has('menu')) {
        return SavoMartUtil.data(element).get('menu');
    }

    // Element has .menu parent
    if (menu = element.closest('.menu')) {
        if (SavoMartUtil.data(menu).has('menu')) {
            return SavoMartUtil.data(menu).get('menu');
        }
    }

    // Element has a parent with DOM reference to .menu in it's DATA storage
    if (SavoMartUtil.hasClass(element, 'menu-link')) {
        var sub = element.closest('.menu-sub');

        if (SavoMartUtil.data(sub).has('menu')) {
            return SavoMartUtil.data(sub).get('menu');
        }
    }

    return null;
}

// Hide all dropdowns and skip one if provided
SavoMartMenu.hideDropdowns = function (skip) {
    var items = document.querySelectorAll('.show.menu-dropdown[data-kt-menu-trigger]');

    if (items && items.length > 0) {
        for (var i = 0, len = items.length; i < len; i++) {
            var item = items[i];
            var menu = SavoMartMenu.getInstance(item);

            if (menu && menu.getItemSubType(item) === 'dropdown') {
                if (skip) {
                    if (menu.getItemSubElement(item).contains(skip) === false && item.contains(skip) === false && item !== skip) {
                        menu.hide(item);
                    }
                } else {
                    menu.hide(item);
                }
            }
        }
    }
}

// Update all dropdowns popover instances
SavoMartMenu.updateDropdowns = function () {
    var items = document.querySelectorAll('.show.menu-dropdown[data-kt-menu-trigger]');

    if (items && items.length > 0) {
        for (var i = 0, len = items.length; i < len; i++) {
            var item = items[i];

            if (SavoMartUtil.data(item).has('popper')) {
                SavoMartUtil.data(item).get('popper').forceUpdate();
            }
        }
    }
}

// Global handlers
SavoMartMenu.initHandlers = function () {
    // Dropdown handler
    document.addEventListener("click", function (e) {
        var items = document.querySelectorAll('.show.menu-dropdown[data-kt-menu-trigger]:not([data-kt-menu-static="true"])');
        var menu;
        var item;
        var sub;
        var menuObj;

        if (items && items.length > 0) {
            for (var i = 0, len = items.length; i < len; i++) {
                item = items[i];
                menuObj = SavoMartMenu.getInstance(item);

                if (menuObj && menuObj.getItemSubType(item) === 'dropdown') {
                    menu = menuObj.getElement();
                    sub = menuObj.getItemSubElement(item);

                    if (item === e.target || item.contains(e.target)) {
                        continue;
                    }

                    if (sub === e.target || sub.contains(e.target)) {
                        continue;
                    }

                    menuObj.hide(item);
                }
            }
        }
    });

    // Sub toggle handler(updated)
    SavoMartUtil.on(document.body, '.menu-item[data-kt-menu-trigger] > .menu-link, [data-kt-menu-trigger]:not(.menu-item):not([data-kt-menu-trigger="auto"])', 'click', function (e) {
        var menu = SavoMartMenu.getInstance(this);

        if (menu !== null) {
            return menu.click(this, e);
        }
    });

    // Link handler
    SavoMartUtil.on(document.body, '.menu-item:not([data-kt-menu-trigger]) > .menu-link', 'click', function (e) {
        var menu = SavoMartMenu.getInstance(this);

        if (menu !== null) {
            return menu.link(this, e);
        }
    });

    // Dismiss handler
    SavoMartUtil.on(document.body, '[data-kt-menu-dismiss="true"]', 'click', function (e) {
        var menu = SavoMartMenu.getInstance(this);

        if (menu !== null) {
            return menu.dismiss(this, e);
        }
    });

    // Mouseover handler
    SavoMartUtil.on(document.body, '[data-kt-menu-trigger], .menu-sub', 'mouseover', function (e) {
        var menu = SavoMartMenu.getInstance(this);

        if (menu !== null && menu.getItemSubType(this) === 'dropdown') {
            return menu.mouseover(this, e);
        }
    });

    // Mouseout handler
    SavoMartUtil.on(document.body, '[data-kt-menu-trigger], .menu-sub', 'mouseout', function (e) {
        var menu = SavoMartMenu.getInstance(this);

        if (menu !== null && menu.getItemSubType(this) === 'dropdown') {
            return menu.mouseout(this, e);
        }
    });

    // Resize handler
    window.addEventListener('resize', function () {
        var menu;
        var timer;

        SavoMartUtil.throttle(timer, function () {
            // Locate and update Offcanvas instances on window resize
            var elements = document.querySelectorAll('[data-kt-menu="true"]');

            if (elements && elements.length > 0) {
                for (var i = 0, len = elements.length; i < len; i++) {
                    menu = SavoMartMenu.getInstance(elements[i]);
                    if (menu) {
                        menu.update();
                    }
                }
            }
        }, 200);
    });
}

// Render menus by url
SavoMartMenu.updateByLinkAttribute = function (value, name = "href") {
    // Locate and update Offcanvas instances on window resize
    var elements = document.querySelectorAll('[data-kt-menu="true"]');

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            var menu = SavoMartMenu.getInstance(elements[i]);

            if (menu) {
                var link = menu.getLinkByAttribute(value, name);
                if (link) {
                    menu.setActiveLink(link);
                }
            }
        }
    }
}

// Global instances
SavoMartMenu.createInstances = function (selector = '[data-kt-menu="true"]') {
    // Initialize menus
    var elements = document.querySelectorAll(selector);
    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            new SavoMartMenu(elements[i]);
        }
    }
}

// Global initialization
SavoMartMenu.init = function () {
    SavoMartMenu.createInstances();

    if (SavoMartMenuHandlersInitialized === false) {
        SavoMartMenu.initHandlers();

        SavoMartMenuHandlersInitialized = true;
    }
};

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = SavoMartMenu;
}