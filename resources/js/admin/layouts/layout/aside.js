"use strict";

// Class definition
var SavoMartLayoutAside = function () {
    // Private variables
    var toggle;
    var aside;
    var asideMenu;

    // Private functions
    var handleToggle = function () {
        var toggleObj = SavoMartToggle.getInstance(toggle);

        // Add a class to prevent aside hover effect after toggle click
        toggleObj.on('kt.toggle.change', function () {
            aside.classList.add('animating');

            setTimeout(function () {
                aside.classList.remove('animating');
            }, 300);
        })
    }

    var handleMenuScroll = function () {
        var menuActiveItem = asideMenu.querySelector(".menu-link.active");

        if (!menuActiveItem) {
            return;
        }

        if (SavoMartUtil.isVisibleInContainer(menuActiveItem, asideMenu) === true) {
            return;
        }

        asideMenu.scroll({
            top: SavoMartUtil.getRelativeTopPosition(menuActiveItem, asideMenu),
            behavior: 'smooth'
        });
    }

    // Public methods
    return {
        init: function () {
            // Elements
            aside = document.querySelector('#kt_aside');
            toggle = document.querySelector('#kt_aside_toggle');
            asideMenu = document.querySelector('#kt_aside_menu_wrapper');

            if (asideMenu) {
                handleMenuScroll();
            }

            if (!aside || !toggle) {
                return;
            }

            handleToggle();
        }
    };
}();

// On document ready
SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartLayoutAside.init();
});