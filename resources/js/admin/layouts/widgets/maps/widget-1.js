"use strict";

// Class definition
var SavoMartMapsWidget1 = (function () {
    // Private methods
    var initMap = function () {
        // Check if amchart library is included
        if (typeof am5 === 'undefined') {
            return;
        }

        var element = document.getElementById("kt_maps_widget_1_map");

        if (!element) {
            return;
        }


        var widget1Root;

        var init = function () {
            // Create root element
            // https://www.amcharts.com/docs/v5/getting-started/#Root_element
            widget1Root = am5.Root.new(element);

            // Set themes
            // https://www.amcharts.com/docs/v5/concepts/themes/
            widget1Root.setThemes([
                am5themes_Animated.new(widget1Root),
            ]);

            // Create the map chart
            // https://www.amcharts.com/docs/v5/charts/map-chart/
            var chart = widget1Root.container.children.push(
                am5map.MapChart.new(widget1Root, {
                    panX: "translateX",
                    panY: "translateY",
                    projection: am5map.geoMercator(),
                    paddingLeft: 0,
                    paddingrIGHT: 0,
                    paddingBottom: 0
                })
            );

            // Create main polygon series for countries
            // https://www.amcharts.com/docs/v5/charts/map-chart/map-polygon-series/
            var polygonSeries = chart.series.push(
                am5map.MapPolygonSeries.new(widget1Root, {
                    geoJSON: am5geodata_worldLow,
                    exclude: ["AQ"],
                })
            );

            polygonSeries.mapPolygons.template.setAll({
                tooltipText: "{name}",
                toggleKey: "active",
                interactive: true,
                fill: am5.color(SavoMartUtil.getCssVariableValue('--bs-gray-300')),
            });

            polygonSeries.mapPolygons.template.states.create("hover", {
                fill: am5.color(SavoMartUtil.getCssVariableValue('--bs-success')),
            });

            polygonSeries.mapPolygons.template.states.create("active", {
                fill: am5.color(SavoMartUtil.getCssVariableValue('--bs-success')),
            });

            // Highlighted Series
            // Create main polygon series for countries
            // https://www.amcharts.com/docs/v5/charts/map-chart/map-polygon-series/
            var polygonSeriesHighlighted = chart.series.push(
                am5map.MapPolygonSeries.new(widget1Root, {
                    //geoJSON: am5geodata_usaLow,
                    geoJSON: am5geodata_worldLow,
                    include: ['US', 'BR', 'DE', 'AU', 'JP']
                })
            );

            polygonSeriesHighlighted.mapPolygons.template.setAll({
                tooltipText: "{name}",
                toggleKey: "active",
                interactive: true,
            });

            var colors = am5.ColorSet.new(widget1Root, {});

            polygonSeriesHighlighted.mapPolygons.template.set(
                "fill",
                am5.color(SavoMartUtil.getCssVariableValue('--bs-primary')),
            );

            polygonSeriesHighlighted.mapPolygons.template.states.create("hover", {
                fill: widget1Root.interfaceColors.get("primaryButtonHover"),
            });

            polygonSeriesHighlighted.mapPolygons.template.states.create("active", {
                fill: widget1Root.interfaceColors.get("primaryButtonHover"),
            });

            // Add zoom control
            // https://www.amcharts.com/docs/v5/charts/map-chart/map-pan-zoom/#Zoom_control
            //chart.set("zoomControl", am5map.ZoomControl.new(widget1Root, {}));

            // Set clicking on "water" to zoom out
            chart.chartContainer
                .get("background")
                .events.on("click", function () {
                    chart.goHome();
                });

            // Make stuff animate on load
            chart.appear(1000, 100);
        }

        // On amchart ready
        am5.ready(function () {
            init();
        }); // end am5.ready()

        // Update chart on theme mode change
        SavoMartThemeMode.on("kt.thememode.change", function () {
            // Destroy chart
            widget1Root.dispose();

            // Reinit chart
            init();
        });
    };

    // Public methods
    return {
        init: function () {
            initMap();
        },
    };
})();

// Webpack support
if (typeof module !== "undefined") {
    module.exports = SavoMartMapsWidget1;
}

// On document ready
SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartMapsWidget1.init();
});
