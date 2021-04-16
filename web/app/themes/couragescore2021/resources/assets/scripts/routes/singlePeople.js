import 'datatables.net'
import Sticky from 'sticky-js'
import '@fancyapps/fancybox/dist/jquery.fancybox'
import * as turf from '@turf/turf'
import '@popperjs/core'

export default {
    init() {
        
    //Maps
    var mapboxgl = require('mapbox-gl/dist/mapbox-gl.js');

    mapboxgl.accessToken = 'pk.eyJ1IjoiZnJpZW5kc29mZnJpZW5kcyIsImEiOiJjajlldnkwbDIyODJmMnlsZ2Z2MjJrZGplIn0.uSr8TFD1-mXrGRfjt1_h5Q';
    var map = new mapboxgl.Map({
    container: 'mapContainer',
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
    const districtNumber = $('.district').html();
    const senateAssembly = $('.body').html() == 'assembly' ? 1 : 0;

    //Add their layer to the map
        let districtUrl = 'https://map.dfg.ca.gov/arcgis/rest/services/Political/boundaries/MapServer/' + senateAssembly + '/query?where=district%3D' + districtNumber + '&f=geojson';

        $.getJSON(districtUrl, function (response) {
            console.log(districtUrl, response);

            map.addSource('districts', {
                'type': 'geojson',
                'data': response,
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

            console.log(districtUrl, response);
            var bbox = turf.bbox(response);
            map.fitBounds(bbox, {padding: 20});
        });
    });
        
    // Votes Table
    
        var voteTable = $('#billsTable').DataTable({
            columnDefs: [
                {
                    targets: 0,
                    visible: false,
                },
                {
                    targets: 1,
                    visible: false,
                },
            ],
            //paging: false,
        });

        voteTable
                .column(0)
                .search( 'floor_votes', true, false )
                .draw();

        //Filter by Topic
        $('#topics').on('change', function () {
            var val = $(this).val();
            voteTable
                .column(1)
                .search( val ? val : '', true, false )
                .draw();
        });
        
        //Filter by Floor or Committee
        $('#floorCommittee button').on('click', function () {
            $('#floorCommittee button').removeClass('active');
            $(this).addClass('active');
            var val = $(this).data('val');
            voteTable
                .column(0)
                .search( val ? val : '', true, false )
                .draw();
        });
        
},
finalize() {
  // JavaScript to be fired on the home page, after the init JS
    
    //Sticky Element
    var sticky = new Sticky('.sticky');
    sticky.update();

    $(window).on('resize', function () {
        sticky.update();
    })

    //Popover
    $('[data-toggle="popover"]').popover({
        placement: 'left',
        trigger: 'hover',
        html: true,
    });

    //Fancybox launch
    $('#contact').fancybox({
            src  : '#contact-form',
            type : 'inline',
            opts : {
                afterShow : function() {
                    // actionkit.forms.initPage();
                    // actionkit.forms.contextRoot = 'https://act.couragecampaign.org/context/';
                    // actionkit.forms.initForm('act')
                },
            },
        });
    
},
};