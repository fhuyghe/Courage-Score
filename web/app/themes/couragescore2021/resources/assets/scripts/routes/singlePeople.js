import 'datatables.net'
//import Sticky from 'sticky-js'
import '@fancyapps/fancybox/dist/jquery.fancybox'
import * as turf from '@turf/turf'
import actionkit from '../util/actionkit'

// /*global google*/

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
        const senateAssembly = $('.body').html() == 'assembly' ? 0 : 1;

    //Add their layer to the map
        let districtUrl = 'https://map.dfg.ca.gov/arcgis/rest/services/Political/boundaries/MapServer/' + senateAssembly + '/query?where=district%3D' + districtNumber + '&f=geojson';
        
        // $.ajax({
        //     url : '../../app/themes/couragescore2021/resources/assets/geo/assembly/district-1.txt',
        //     dataType: 'text',
        //     success : function (data) {
        //         let districtCoords = [eval(data)];
        //         var districtPolygon = new google.maps.Polygon({
        //             paths: districtCoords,
        //             strokeColor: '#FF0000',
        //             strokeOpacity: 0.8,
        //             strokeWeight: 2,
        //             fillColor: '#FF0000',
        //             fillOpacity: 0.35,
        //         });
        //         console.log(districtPolygon)
        //         var myMap = new google.maps.Map($('.textwidget'));
        //         districtPolygon.setMap(myMap);
                
        //         myMap.data.togeoJSON(function (res) { 
        //             console.log(res);
        //         });
        //     },
        // });

        $.getJSON(districtUrl, function (response) {
            console.log(districtUrl, response);
            drawMap(map, response);
        }).fail(function () {
            //Rely on local maps to get the info
            
        });
    });
        
        function drawMap(map, mapJSON) {
            map.addSource('districts', {
                'type': 'geojson',
                'data': mapJSON,
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

            var bbox = turf.bbox(mapJSON);
            map.fitBounds(bbox, {padding: 20});
        }
        
    // Votes Table
    
        var voteTable = $('#billsTable').DataTable({
            info: false,
            initComplete: function() {
                $('#billsTable').css('opacity', 1);
            },
            columnDefs: [
                {
                    targets: 0,
                    visible: false,
                },
                {
                    targets: 1,
                    visible: false,
                },
                {
                    targets: 2,
                    visible: false,
                },
            ],
            language: {
                search: '<i class="fal fa-search"></i>',
              },
        });

        voteTable
                .column(0)
                .search( 'floor_votes', true, false )
                .draw();

        voteTable
                .column(1)
                .search( $('#yearsFilter a.active').data('year') , true, false )
                .draw();

        //Filter by Topic
        $('#topics').on('change', function () {
            var val = $(this).val();
            voteTable
                .column(2)
                .search( val ? val : '', true, false )
                .draw();
        });

        //Filter by year
        $('#yearsFilter a').on('click', function () {
            let val = $(this).data('year');
            let postID = $('#yearsFilter').data('id');

            $('#yearsFilter a').removeClass('active');
            $(this).addClass('active');

            
            //Get new score
            let score = 0
            $('#general').css('opacity', .5);

            $.ajax({
                url : ajax_object.ajax_url,
                data : {
                  action: 'get_scores',
                  postID: postID,
                },
              })
                .done(function (res) {
                    if (val) {
                        res.data.forEach(row => {
                            if (row.years == val ) {
                                score = row.score;
                            }
                        });
                        
                    } else {
                        //Calculate the average
                        let sum = 0;
                        res.data.forEach(row => {
                            sum += parseInt(row.score);
                        });
                        score = Math.round(sum / res.data.length);
                    }

                    //Add new score                   
                    $('.score').html(score);

                    //Get new grade
                    let color = '';
                    let letter = '';
                    if ( score == 'na' || !score ){
                        color = 'grey';
                        letter = 'N/A';
                    } else if (score < 60){
                        color = 'red';
                        letter = 'F';
                    } else if(score < 70 && score > 59){
                        color = 'orange';
                        letter = 'D';
                    } else if(score < 80 && score > 69){
                        color = 'yellow';
                        letter = 'C';
                    } else if(score < 90 && score > 79){
                        color = 'green';
                        letter = 'B';
                    } else if( score > 89){
                        color = 'blue';
                        letter = 'A';

                        if(score == 100){
                            letter = 'A+';
                        }
                    }
                    let grade = $('.grade')
                    grade.attr('class', 'grade ' + color);
                    grade.html(letter);
                    $('#general').css('opacity', 1);
              });

            //Update years
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

        $('#partnersScoresTable').DataTable({
            searching: false,
            paging: false,
        });
        
},
finalize() {
  // JavaScript to be fired on the home page, after the init JS
    
    //Sticky Element
    // var sticky = new Sticky('.sticky');
    // sticky.update();

    // $(window).on('resize', function () {
    //     sticky.update();
    // })

    //Fancybox launch
    $('#contact').fancybox({
        src  : '#contact-form',
        type : 'inline',
        opts : {
            afterShow : function() {
                actionkit.forms.initPage();
                actionkit.forms.contextRoot = 'https://act.couragecampaign.org/context/';
                actionkit.forms.initForm('act')
            },
        },
    });

    //Print the page
    $('#print').on('click', function () {
        window.print();
     });
    
    //Copy the URL
    $('#link').on('click', function () {
        //Copy
        var input = document.createElement('textarea');
        input.innerHTML = window.location;
        document.body.appendChild(input);
        input.select();
        var result = document.execCommand('copy');
        document.body.removeChild(input);

        // Create an alert
        let alert = document.createElement('div');
        alert.setAttribute('role', 'alert');
        alert.classList.add('alert');
        if (result) {
            alert.appendChild(document.createTextNode('URL copied'))
            alert.classList.add('alert-success');
        } else {
            alert.appendChild(document.createTextNode('Failed to copy URL'));
            alert.classList.add('alert-danger');
        }
        document.body.appendChild(alert);

        //Destroy alert
        setTimeout(function () {
            document.body.removeChild(alert);
        }, 3000);
    }
    );
    
},
};