//
// Global init of core components
//

// Init components
var SavoMartComponents = (function () {
    // Public methods
    return {
        init: function () {
            SavoMartApp.init();
            SavoMartDrawer.init();
            SavoMartMenu.init();
            SavoMartScroll.init();
            SavoMartSticky.init();
            SavoMartSwapper.init();
            SavoMartToggle.init();
            SavoMartScrolltop.init();
            // SavoMartDialer.init();
            SavoMartImageInput.init();
            SavoMartPasswordMeter.init();
        },
    };
})();

// On document ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", function () {
        SavoMartComponents.init();
    });
} else {
    SavoMartComponents.init();
}

// Init page loader
window.addEventListener("load", function () {
    SavoMartApp.hidePageLoading();
});

// Declare SavoMartApp for Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    window.SavoMartComponents = module.exports = SavoMartComponents;
}
