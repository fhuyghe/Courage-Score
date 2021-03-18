export default {
    init() {
        
    //Maps
    var mapboxgl = require('mapbox-gl/dist/mapbox-gl.js');
    // const apiKey = 'AAPKa7b97dc92c594bbba698c60df8b8e5c3VIU75f9pinf3oI9NwLTbN-UpWfvTSqHR6Nln5_F3vnhQpkdjJ5kYqeGSQNQiCmgY';
    // const basemapEnum = 'ArcGIS:Streets';

    mapboxgl.accessToken = 'pk.eyJ1IjoiZnJpZW5kc29mZnJpZW5kcyIsImEiOiJjajlldnkwbDIyODJmMnlsZ2Z2MjJrZGplIn0.uSr8TFD1-mXrGRfjt1_h5Q';
    var map = new mapboxgl.Map({
    container: 'mapContainer',
    // style: `https://basemaps-api.arcgis.com/arcgis/rest/services/styles/${basemapEnum}?type=style&apiKey=${apiKey}`,
    style: 'mapbox://styles/friendsoffriends/ckmf19pegd66m17p8xofezhuw',
    trackResize: true,
    center: [-119.8287148, 35.1651863],
    zoom: 4,
    maxBounds: [[-130.580095, 30.3674814], [-110.9696226, 44.0922698]],
    });

    map.resize();

    map.on('load', function () {
    map.resize();

    // Get Legislator info
    const districtNumber = $('#district').html();
    const senateAssembly = $('#parliement').html() == 'assembly' ? 0 : 1;

    //Add their layer to the map
    map.addSource('districts', {
        'type': 'geojson',
        'data': 'https://map.dfg.ca.gov/arcgis/rest/services/Political/boundaries/MapServer/' + senateAssembly + '/query?where=district%3D' + districtNumber + '&f=geojson',
    });
    
    map.addLayer({
        id: 'districts-fill',
        type: 'fill',
        source: 'districts',
        paint: {
        'fill-color': 'hsl(200, 80%, 50%)',
        'fill-opacity': 0.5,
        'fill-outline-color': 'white',
        },
    });
    });
        
},
finalize() {
  // JavaScript to be fired on the home page, after the init JS
    
},
};