var map;

jQuery(document).ready(function($) {    
    init($);
});

function init($){

map = new OpenLayers.Map('map');

var ocm =     new OpenLayers.Layer.OSM("OpenCycleMap",
      ["http://a.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png",
       "http://b.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png",
       "http://c.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png"],
       {layers: "basic", isBaseLayer: true, visibility: true, displayInLayerSwitcher: false} 
       );
map.addLayers([ocm]);

/* Styles */

var defaultStyle = new OpenLayers.Style({
    strokeColor: "#980000",
    fillColor: "green",
    pointRadius: 5,
});

var selectStyle = new OpenLayers.Style({
    pointRadius: 10,
    fillColor: "yellow",
    label: " ${name}",
    labelAlign: "lm",
    labelXOffset: "20",
    labelOutlineColor: "white",
    labelOutlineWidth: 3,
    fontSize: 16,
});

var pointStyles = new OpenLayers.StyleMap(
    {
        "default": defaultStyle,
        "select": selectStyle
    });

var cycle_routes = new OpenLayers.Layer.Vector("Heritage Cycle Route South", {
    projection: "EPSG:4326",
    style: {
            strokeColor: "#6e0045",
            strokeWidth: 4,
            strokeLinecap: "round",
            strokeDashstyle: "dot"
            },
    strategies: [new OpenLayers.Strategy.BBOX()],
    protocol: new OpenLayers.Protocol.WFS({
        url: "/map/?map=/home/ubuntu/mapfiles/heritage-south-cycle-route.map",
        featureType: ["heritage_cycle_route_south"]}),
    });
map.addLayers([cycle_routes]);

var heritage_waypoints = new OpenLayers.Layer.Vector("Heritage Features", {
        srsName: "EPSG:4326",
        strategies: [new OpenLayers.Strategy.BBOX()],
        protocol: new OpenLayers.Protocol.WFS({
            url: "/map/?map=/home/ubuntu/mapfiles/heritage-south-cycle-route.map",
            featureType: ["heritage_cycle_route_south_waypoints"]}),
        box: false,
        styleMap: pointStyles
    });
map.addLayers([heritage_waypoints]);


/* required to fire selection events on heritage_waypoints */
var selectControl = new OpenLayers.Control.SelectFeature(heritage_waypoints);
map.addControl(selectControl);
selectControl.activate();

  
/* Override GetFeature selectClick */
OpenLayers.Control.GetFeature.prototype.selectClick = function(evt) {
    var bounds = this.pixelToBounds(evt.xy);
    var baseSRS = this.map.getProjectionObject();
    var layerSRS = new OpenLayers.Projection('EPSG:4326');
    bounds.transform(baseSRS, layerSRS);
    this.setModifiers(evt);
    this.request(bounds, {single: this.single});
}
    
var infoctl = new OpenLayers.Control.GetFeature({
        protocol: OpenLayers.Protocol.WFS({
            url: "/map/?map=/home/ubuntu/mapfiles/heritage-south-cycle-route.map",
            featureType: "heritage_cycle_route_south_waypoints",
            featurePrefix: "ms",
            maxFeatures: 10,
            formatOptions: {
                outputFormat: "text/xml"
            }
        }),
        clickTolerance: 10
});


/* feature selection event handling */
infoctl.events.register("featureselected", this, function(e){
        /* get feature attributes */
        var feature = e.feature;
        attrs = feature.attributes;
        
        /* center the map on the selected waypoint */
        var center = new OpenLayers.LonLat(attrs.longitude, attrs.latitude);
        center.transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
        map.setCenter(center,13);
        
        /* clear existing feature info and rebuild */
        $('#info').empty();
        $('#info').append('<h4>' + attrs.name + '</h4>');
        $('#info').append('<p><img src="http://54.229.185.240/comap/media/' + attrs.image_path + '"/></p>');
        $('#info').append('<p>' + attrs.description + '</p>');
        $('#info').append('<strong>Elevation:</strong> ' + attrs.elevation.split('.')[0] + ' metres<br/>');
        $('#info').append('<strong>Latitude:</strong> ' + attrs.latitude + '<br/>');
        $('#info').append('<strong>Longitude:</strong> ' + attrs.longitude);
});

/* feature unselection event handling */
infoctl.events.register("featureunselected", this, function(e){
        $('#info').empty();
        $('#info').append('<span>Click a point on the map for more information</span>');
});


/* Add GetFeature Control to map and activate */
map.addControl(infoctl);
infoctl.activate();

/* Add map controls */
map.addControl(new OpenLayers.Control.ScaleLine());
map.addControl(new OpenLayers.Control.LayerSwitcher());


//* -8.080757 52.887412 -7.996808 52.943225

map.zoomToExtent(new OpenLayers.Bounds([-8.08, 52.887, -7.996, 52.943]).transform("EPSG:4326", "EPSG:900913"));

}





