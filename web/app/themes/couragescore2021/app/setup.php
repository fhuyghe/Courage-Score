<?php

namespace App;

use Roots\Sage\Container;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('sage/main.css', asset_path('styles/main.css'), false, null);
    wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);
    wp_localize_script( 'sage/main.js', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil-clean-up');
    add_theme_support('soil-jquery-cdn');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');

    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage')
    ]);

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Use main stylesheet for visual editor
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
    add_editor_style(asset_path('styles/main.css'));
}, 20);

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ];
    register_sidebar([
        'name'          => __('Primary', 'sage'),
        'id'            => 'sidebar-primary'
    ] + $config);
    register_sidebar([
        'name'          => __('Footer', 'sage'),
        'id'            => 'sidebar-footer'
    ] + $config);
});

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function ($post) {
    sage('blade')->share('post', $post);
});

/**
 * Setup Sage options
 */
add_action('after_setup_theme', function () {
    /**
     * Add JsonManifest to Sage container
     */
    sage()->singleton('sage.assets', function () {
        return new JsonManifest(config('assets.manifest'), config('assets.uri'));
    });

    /**
     * Add Blade to Sage container
     */
    sage()->singleton('sage.blade', function (Container $app) {
        $cachePath = config('view.compiled');
        if (!file_exists($cachePath)) {
            wp_mkdir_p($cachePath);
        }
        (new BladeProvider($app))->register();
        return new Blade($app['view']);
    });

    /**
     * Create @asset() Blade directive
     */
    sage('blade')->compiler()->directive('asset', function ($asset) {
        return "<?= " . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
    });
});

// Register ACF options pages
if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page(array(
        'page_title'    => 'Theme General Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
    
    acf_add_options_sub_page(array(
        'page_title'    => 'Theme Header Settings',
        'menu_title'    => 'Header',
        'parent_slug'   => 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Forms Preffiled Text',
        'menu_title'    => 'Forms Preffiled Text',
        'parent_slug'   => 'theme-general-settings',
    ));
    
    acf_add_options_sub_page(array(
        'page_title'    => 'Theme Footer Settings',
        'menu_title'    => 'Footer',
        'parent_slug'   => 'theme-general-settings',
    ));
    
}

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 50, 50, true ); // Normal post thumbnails
    add_image_size( 'people-thumbnail', 213, 213, true );
    add_image_size( 'people-thumbnail_314x314', 320, 320, true );
    add_image_size( 'thumb_628x343_true', 628, 343, true );
}

// CPT "People" registration
add_action('init',  __NAMESPACE__ . '\\People_custom_init');
function People_custom_init() 
{
  $labels = array(
    'name' => _x('People', 'post type general name'),
    'singular_name' => _x('People', 'post type singular name'),
    'add_new' => _x('Add New', 'Person'),
    'add_new_item' => __('Add New Person'),
    'edit_item' => __('Edit Person'),
    'new_item' => __('New Person'),
    'view_item' => __('View Person'),
    'search_items' => __('Search People'),
    'not_found' =>  __('No People found'),
    'not_found_in_trash' => __('No People found in Trash'), 
    'parent_item_colon' => '',
    'menu_name' => 'People'
  );
  
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => array( 'slug' => 'legislator' ),
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title','editor','thumbnail','custom-fields'),
    'taxonomies' => array('title', 'district')
  ); 
  register_post_type('people',$args);
}

// CPT "Votes" registration
add_action('init',  __NAMESPACE__ . '\\Votes_custom_init');
function Votes_custom_init() 
{
  $labels = array(
    'name' => _x('Bills', 'post type general name'),
    'singular_name' => _x('Bills', 'post type singular name'),
    'add_new' => _x('Add New', 'Bill'),
    'add_new_item' => __('Add New Bill'),
    'edit_item' => __('Edit Bill'),
    'new_item' => __('New Bill'),
    'view_item' => __('View Bill'),
    'search_items' => __('Search Bill'),
    'not_found' =>  __('No Bill found'),
    'not_found_in_trash' => __('No Bill found in Trash'), 
    'parent_item_colon' => '',
    'menu_name' => 'Bills'
  );
  
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title','editor','thumbnail'),
    'taxonomies' => array('vote-category', 'vote-topic')
  ); 
  register_post_type('vote',$args);
}


add_action( 'init',  __NAMESPACE__ . '\\create_vote_topics_taxonomies' );
function create_vote_topics_taxonomies() 
{
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name' => _x( 'Topics', 'taxonomy general name' ),
    'singular_name' => _x( 'Topic', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Topics' ),
    'all_items' => __( 'All Topics' ),
    'parent_item' => __( 'Parent Topic' ),
    'parent_item_colon' => __( 'Parent Topic:' ),
    'edit_item' => __( 'Edit Topic' ), 
    'update_item' => __( 'Update Topic' ),
    'add_new_item' => __( 'Add New Topic' ),
    'new_item_name' => __( 'New Topic Name' ),
    'menu_name' => __( 'Topics' ),
  );    

  register_taxonomy('vote-topic','vote', array(
    'public'=>true,
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    ));
}

function get_district($street, $city, $zip){
    // example address 1228 O St , Sacramento, CA, 95814
    $address = ''.str_replace(' ', '%20', $street);
    $address .= '+'.str_replace(' ', '%20', $city);
    $address .= '+'.$zip;
    $url = "https://www.googleapis.com/civicinfo/v2/representatives?address=$address+CA&key=GOOGLEMAPS_API";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response);
    // print_r('++++++++++++++++++++');
    // print_r($response_a);
    $offices = $response_a->offices;
    if($offices){
        foreach ($offices as $office){
            $pos = strrpos($office->divisionId, '/');
            $divisionIds = $pos === false ? $office : substr($office->divisionId, $pos + 1);
            list( $district_type, $district_number ) = explode(":", $divisionIds);
            $divisions[$district_type] = $district_number;
        }
    }
    return $divisions;
}

function get_district_data( $district_type , $district_id, $maps_count){
    $openstates_data_var = 'openstates_data_'.$district_type.'_'.$district_id.'_json';
    $cached_data = get_transient( $openstates_data_var );
    if( true == $cached_data ){
         return  $cached_data;
    }else{
        $url = "http://openstates.org/api/v1/districts/boundary/ocd-division/country:us/state:ca/$district_type:$district_id/?apikey=OPENSTATES_API";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);
        $openstates_data_var_month = 'openstates_data_'.$district_type.'_'.$district_id.'_json_month';
        if (!is_object($response_a)){
            $response_a = get_transient( $openstates_data_var_month );
        } else {
            delete_transient( $openstates_data_var );
            delete_transient( $openstates_data_var_month );
            set_transient( $openstates_data_var, $response_a, 7 * DAY_IN_SECONDS );
            set_transient( $openstates_data_var_month, $response_a, 30 * DAY_IN_SECONDS );
        }
        return  $response_a;
    } 
    
}

function get_district_map( $district_type , $district_id, $maps_count){
    global $post;
    $response_a = get_district_data($district_type , $district_id, $maps_count);
    $center_lat = $response_a->region->center_lat;
    $center_lng = $response_a->region->center_lon;
    $html = '<div  class="map"><div id="map-'.$maps_count.'" style="width:100%; height:100%"></div></div>';
    $html .='<script>
                function map'.$maps_count.'() {
                    var map'.$maps_count.' = new google.maps.Map(document.getElementById("map-'.$maps_count.'"), {
                        zoom: 7,
                        center: {lat: '.$center_lat.', lng: '.$center_lng.'},
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        disableDefaultUI: true,
                        zoomControl: true,
                        fullscreenControlOptions: true,
                    });
                ';
                foreach ($response_a->shape as $key=>$district_locations){
    $html .=    'var districtCoords_'.$key.' = [';
                    foreach($district_locations[0] as $lng_lat){
                        
                        $html .= '{lat:'.$lng_lat[1].', lng:'.$lng_lat[0].'},';
                    }
    $html .=    '];

                  // Construct the polygon.
                  var districtPolygon_'.$key.' = new google.maps.Polygon({
                    paths: districtCoords_'.$key.',
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#FF0000",
                    fillOpacity: 0.35
                  });';
     $html .=    ' districtPolygon_'.$key.'.setMap(map'.$maps_count.');';
                } 
    $html .=    '
                var currCenter = map'.$maps_count.'.getCenter();
                setTimeout(function(){
                google.maps.event.trigger(map'.$maps_count.', "resize");
                map'.$maps_count.'.setCenter(currCenter);
                }, 1000); 
             google.maps.event.trigger(map'.$maps_count.', "resize");}</script>';
    return $html;
    //endif;
}

function get_district_map_new( $district_type , $district_id, $maps_count){
    global $post;
    $center_lat = 0;
    $center_lng = 0;
    $html = '<div  class="map"><div id="map-'.$maps_count.'" style="width:100%; height:100%"></div></div>';
    $html .='<script>
                function map'.$maps_count.'() {
                    var map'.$maps_count.' = new google.maps.Map(document.getElementById("map-'.$maps_count.'"), {
                        zoom: 10,
                        center: {lat: '.$center_lat.', lng: '.$center_lng.'},
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        disableDefaultUI: true,
                        zoomControl: true,
                        fullscreenControlOptions: true,
                    });
                ';
                if($district_type== 'sldu' ){$district = 'senate';} else{ $district = 'assembly';}
                
                $drow = $district.'_district_'.$district_id;
    $html .=    'var districtCoords = [';
    $html .=  	get_local_file_contents(TEMPLATEPATH.'/geo/'.$district.'/district-'.$district_id.'.txt');
    $html .=	'];';
                  // Construct the polygon.
     $html .=   'var districtPolygon = new google.maps.Polygon({
                    paths: districtCoords,
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#FF0000",
                    fillOpacity: 0.35
                  });';
     
               
    $html .=    ' 
     			var bounds = new google.maps.LatLngBounds();
				var i;
				for (var j = 0; j < districtCoords.length; j++) {
					for (i = 0; i < districtCoords[j].length; i++) {
					  bounds.extend(districtCoords[j][i]);
					}
				}
				map'.$maps_count.'.fitBounds(bounds);
     			districtPolygon.setMap(map'.$maps_count.');';
    $html .=    '
                var currCenter = bounds.getCenter();
                
                var listener = google.maps.event.addListener(map'.$maps_count.', "idle", function() { 
				    var zoom = map'.$maps_count.'.getZoom();
				    if(zoom <3){
                        map'.$maps_count.'.setZoom(6); 
                    }

				  google.maps.event.removeListener(listener); 
				 });
                setTimeout(function(){
                google.maps.event.trigger(map'.$maps_count.', "resize");
                map'.$maps_count.'.setCenter(currCenter);
                }, 1000);
                google.maps.event.trigger(map'.$maps_count.', "resize"); }</script>';
    return $html;
}

function get_local_file_contents( $file_path ) {
    ob_start();
    include $file_path;
    $contents = ob_get_clean();

    return $contents;
}

function get_district_map_full_screen( $district_type , $district_id, $maps_count){
    global $post;
    $response_a = get_district_data($district_type , $district_id, $maps_count);
    $center_lat = $response_a->region->center_lat;
    $center_lng = $response_a->region->center_lon;
    //print_r($response_a);
    //if($response_a && $response_a[0] !='Not Found'):
    $html = '<div  class="map"><div id="map-'.$maps_count.'" style="width:100%; height:100%"></div></div>';
    $html .='<script>
                function map'.$maps_count.'() {
                    var map'.$maps_count.' = new google.maps.Map(document.getElementById("map-'.$maps_count.'"), {
                        zoom: 10,
                        center: {lat: '.$center_lat.', lng: '.$center_lng.'},
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        disableDefaultUI: true,
                        zoomControl: true,
                        fullscreenControlOptions: true,
                    });';
                foreach ($response_a->shape as $key=>$district_locations){
    $html .=    'var districtCoords_'.$key.' = [';
                    foreach($district_locations[0] as $lng_lat){
                        
                        $html .= '{lat:'.$lng_lat[1].', lng:'.$lng_lat[0].'},';
                    }
    $html .=    '];

                  // Construct the polygon.
                  var districtPolygon_'.$key.' = new google.maps.Polygon({
                    paths: districtCoords_'.$key.',
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#FF0000",
                    fillOpacity: 0.35
                  });';
     $html .=    ' districtPolygon_'.$key.'.setMap(map'.$maps_count.');';
                } 
    $html .=    ' }</script>';
    return $html;
    //endif;
}

function get_district_map_full_screen_new( $district_type , $district_id, $maps_count){
    global $post;
    $center_lat = 0;
    $center_lng = 0;
    //print_r($response_a);
    //if($response_a && $response_a[0] !='Not Found'):
    $html = '<div  class="map"><div id="map-'.$maps_count.'" style="width:100%; height:100%"></div></div>';
    $html .='<script>
                function map'.$maps_count.'() {
                    var map'.$maps_count.' = new google.maps.Map(document.getElementById("map-'.$maps_count.'"), {
                        zoom: 10,
                        center: {lat: '.$center_lat.', lng: '.$center_lng.'},
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        disableDefaultUI: true,
                        zoomControl: true,
                        fullscreenControlOptions: true,
                    });';
                 if($district_type == 'sldu' ){$district = 'senate';} else{ $district = 'assembly';}
    $html .=    'var districtCoords = [';
    $html .=    get_local_file_contents(TEMPLATEPATH.'/geo/'.$district.'/district-'.$district_id.'.txt');
    $html .=    '];';

                  // Construct the polygon.
    $html .=    'var districtPolygon = new google.maps.Polygon({
                    paths: districtCoords,
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#FF0000",
                    fillOpacity: 0.35
                  });';
      $html .=    ' 
                var bounds = new google.maps.LatLngBounds();
                var i;
                for (var j = 0; j < districtCoords.length; j++) {
                    for (i = 0; i < districtCoords[j].length; i++) {
                      bounds.extend(districtCoords[j][i]);
                    }
                }
                map'.$maps_count.'.fitBounds(bounds);
                districtPolygon.setMap(map'.$maps_count.');';
    $html .=    '
                var currCenter = bounds.getCenter();
                
                var listener = google.maps.event.addListener(map'.$maps_count.', "idle", function() { 
                    var zoom = map'.$maps_count.'.getZoom();
                    if(zoom <3){
                        map'.$maps_count.'.setZoom(6); 
                    }

                  google.maps.event.removeListener(listener); 
                 });
                setTimeout(function(){
                google.maps.event.trigger(map'.$maps_count.', "resize");
                map'.$maps_count.'.setCenter(currCenter);
                }, 1000); }</script>';
    return $html;
    //endif;
}

function get_district_static_map( $district_type , $district_id, $maps_count){
    global $post;
    // print_r($district_type);
    // print_r($district_id);
    // print_r($maps_count);
    $response_a = get_district_data($district_type , $district_id, $maps_count);

    $center_lat = $response_a->region->center_lat;
    $center_lng = $response_a->region->center_lon;
    $html = '<img src="http://maps.googleapis.com/maps/api/staticmap?center='.$center_lat.','.$center_lng.'&size=213x214&sensor=false';
        foreach ($response_a->shape as $key=>$district_locations){
            $html .='&path=color:red|weight:1|fillcolor:red';
            $coord_count = 0;
            if( 49 > count($district_locations[0])  ){
                $divider = 1;
            } elseif( 99 > count($district_locations[0]) &&  count($district_locations[0]) > 50){
                $divider = 2;
            } elseif(200 > count($district_locations[0]) &&  count($district_locations[0]) > 100){
                $divider = 5;
            } elseif(500 >count($district_locations[0]) && count($district_locations[0]) > 201){
                $divider = 10;
            }  elseif(1000 >count($district_locations[0]) && count($district_locations[0]) > 501){
                $divider = 20;
            }  elseif(2000 >count($district_locations[0]) && count($district_locations[0]) > 1000){
                $divider = 40;
            } else {
                $divider = 50;
            }
            foreach($district_locations[0] as $lng_lat){
                if($coord_count %  $divider == 0){
                    $html .= '|'.$lng_lat[1].','.$lng_lat[0];
                }
                $coord_count++;
            }
        }
    $html .='"/>';
    return $html;
}

function wpml_custom_args() {
    if (function_exists('icl_get_languages')) {
        $languages = icl_get_languages('skip_missing=0');
 
        if(1 < count($languages)){
            $street = (isset($_GET['street'])) ? $_GET['street'] : '';
            $city = (isset($_GET['city'])) ? $_GET['city'] : '';
            $zip = (isset($_GET['zip'])) ? $_GET['zip'] : '';
            $address = 'street='.str_replace(' ', '+', $street);
            $address .= '&city='.str_replace(' ', '+', $city);
            $address .= '&zip='.$zip;
            $html = '<ul class="languages-switcher">';
            foreach($languages as $l){
                $html .= '<li><a href="'.$l['url'];
                $html .= ! empty( $address )  ? '?'.$address : '';
                $html .= '">'.$l['native_name'].'</a></li>';
            }
            $html .= '</ul>';
        }
    }
    //print_r( $languages );
    return $html;
}

function languages_list(){
    if (function_exists('icl_get_languages')) {
        $languages = icl_get_languages('skip_missing=0&orderby=code');
        if(1 < count($languages)){
            $street = (isset($_GET['street'])) ? $_GET['street'] : '';
            $city = (isset($_GET['city'])) ? $_GET['city'] : '';
            $zip = (isset($_GET['zip'])) ? $_GET['zip'] : '';
            $lang = (isset($_GET['lang'])) ? $_GET['lang'] : '';
            $address = 'street='.str_replace(' ', '+', $street);
            $address .= '&city='.str_replace(' ', '+', $city);
            $address .= '&zip='.$zip;
            // $address .= '&lang='.$lang;
            echo '<ul class="top-links cf">';
            foreach($languages as $l){
                if(!$l['active']) echo '<li data-active-lang="'.ICL_LANGUAGE_CODE.'">';                
                // if(!empty( $address )){
                //     if(!$l['active']) echo '<a href="'.$l['url'].'?'.$address.'">';
                // } else {
                    if(!$l['active']) echo '<a href="'.$l['url'].'">';
                // }
                if(!$l['active']) echo icl_disp_language($l['native_name']);
                if(!$l['active']) echo '</a>';
                if(!$l['active']) echo '</li>';
            }
            echo '</ul>';
        }
    }
}

function retrieve_cs_score_replacement() {
    global $post;
    if(get_field('progressive_voting_by_member')){
        if(count(get_field('progressive_voting_by_member'))>0){
            $voting_arr = get_field('progressive_voting_by_member');
            $voting = $voting_arr[0]['score'];
        } else {
            $voting = get_field('progressive_voting_by_member_in_assembly', $post->ID);
        }
    } else {
        $voting = 'na';
    }
   
    return $voting;
}

function retrieve_cs_letter_replacement() {
    global $post;

    if(get_field('progressive_voting_by_member')){
        if(count(get_field('progressive_voting_by_member'))>0){
            $voting_arr = get_field('progressive_voting_by_member');
            $voting = $voting_arr[0]['score'];
        } else {
            $voting = get_field('progressive_voting_by_member_in_assembly', $post->ID);
        }
    }
   
    if($voting < 59){
        $letter = 'F';
    } elseif($voting < 70 && $voting > 59){
        $letter = 'D';
    } elseif($voting < 80 && $voting > 69){
        $letter = 'C';
    } elseif($voting < 90 && $voting > 79){
        $letter = 'B';
    } elseif( $voting > 89){
        $letter = 'A';
        if($voting == 100){
            $letter = 'A+';
        };
    }

    return $letter;
}

function register_meta_extra_replacements() {
    wpseo_register_var_replacement( '%%cs_letter%%', __NAMESPACE__ . '\\retrieve_cs_letter_replacement', 'advanced', 'replace code for %%cs_letter%%' );
    wpseo_register_var_replacement( '%%cs_score%%', __NAMESPACE__ . '\\retrieve_cs_score_replacement', 'advanced', 'replace code for %%cs_score%%' );
}
add_action( 'wpseo_register_extra_replacements', __NAMESPACE__ . '\\register_meta_extra_replacements' );


function post_object_bill_result( $title, $post, $field, $post_id ) {
    
        // load a custom field from this $object and show it in the $result
        $billt_title = get_field('title', $post->ID);
        $title = $title.'-'.$billt_title;
        return $title;
    }
    
    // filter for a specific field based on it's name
add_filter('acf/fields/post_object/result/name=bill_number',  __NAMESPACE__ . '\\post_object_bill_result', 10, 4);

/**
 * Generated by the WordPress Meta Box generator
 * at http://jeremyhixon.com/tool/wordpress-meta-box-generator/
 */

function people_list_get_meta( $value ) {
    global $post;

    $field = get_post_meta( $post->ID, $value, true );
    if ( ! empty( $field ) ) {
        return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
    } else {
        return false;
    }
}


/*
    Usage: people_list_get_meta( 'people_list_vote' )
*/

function acf_load_years_field_choices( $field ) {
    // reset choices
    $field['choices'] = array();


    $current_year = date('Y') ;
    // if has rows
    if( $current_year) {
        
        // while has rows
        for( $year = $current_year; $year > $current_year-10; $year--) {
            
          
            if ($year > 2014){
                // vars
                $value = $label= $year;
                
                // append to choices
                $field['choices'][ $value ] = $label;
            }
        } 
        
    }


    // return the field
    return $field;
    
}
add_filter('acf/load_field/name=years',  __NAMESPACE__ . '\\acf_load_years_field_choices');

/*************/
// Call this at each point of interest, passing a descriptive string
function prof_flag($str)
{
    global $prof_timing, $prof_names;
    $prof_timing[] = microtime(true);
    $prof_names[] = $str;
}

// Call this when you're done and want to see the results
function prof_print()
{
    global $prof_timing, $prof_names;
    $size = count($prof_timing);
    for($i=0;$i<$size - 1; $i++)
    {
        echo "<b>{$prof_names[$i]}</b><br>";
        echo sprintf("&nbsp;&nbsp;&nbsp;%f<br>", $prof_timing[$i+1]-$prof_timing[$i]);
    }
    echo "<b>{$prof_names[$size-1]}</b><br>";
}

/***********/

/*** Get New Bills ***/
function get_scorecards(){
    
    /*** API Request ***/ 
    $authorization = "Authorization: apikey BILLTRACK_API";
    $url = 'https://www.billtrack50.com/BT50Api/2.0/json/Scorecards';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);

    curl_close($ch);
    $response_a = json_decode($response);
   // print_r($response_a);
   // die();
    return  $response_a;
    
}

function get_bills_by_scorecard(){
     set_time_limit(0);
    /*** API Request ***/ 
    $authorization = "Authorization: apikey BILLTRACK_API";
    
    $scorecards = get_scorecards();
    if($scorecards && $scorecards->Scorecards){
        $bill_list_full = array();
        foreach($scorecards->Scorecards as $scorecard){
            if($scorecard->ScorecardName == '2020 Courage Score'){
                $url = 'https://www.billtrack50.com/BT50Api/2.0/json/Scorecards/'.$scorecard->ScorecardID.'/Bills';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($ch);

                curl_close($ch);
                $response_a = json_decode($response);

                $bills = $response_a->Bills;
                $bill_list_full = $bill_list_full + $bills;

                return  $bill_list_full;
            }
        }
    }    
    // print_r($bill_list_full);
    // die();
    
}

function get_bill_summury($bill_id){
    /*** API Request ***/ 
    $authorization = "Authorization: apikey APIKEY";
    $url = 'https://www.billtrack50.com/BT50Api/2.0/json/Bills/'.$bill_id;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);

    curl_close($ch);
    $response_a = json_decode($response);

    $bill_summury = $response_a->Bill->Summary;
    return  $bill_summury;
}

function get_bill_votes($bill_id){
    /*** API Request ***/ 
    $authorization = "Authorization: apikey BILLTRACK_API";
    $url = 'https://www.billtrack50.com/BT50Api/2.0/json/Bills/'.$bill_id.'/Votes';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);

    curl_close($ch);
    $response_a = json_decode($response);
//print_r( $response_a);
    $votes_arr = $response_a->Votes;
    //return $response_a;
    return $votes_arr;
}

function add_new_bills(){
   
    $bills = get_bills_by_scorecard();
    // print_r($bills);
    // die;
    foreach ( $bills as $bill){
        $bills_query = new WP_Query(array(
            'meta_key'          => 'billtrack_id',
            'meta_value'        => $bill->BillID,
            'post_type'         => 'vote',
            'suppress_filters'  => false,
            'numberposts'       => -1,
            'post_status'       => array('draft', 'publish'),
            ));
        if ( $bills_query->have_posts()){
            while( $bills_query->have_posts()): $bills_query->the_post();
                // $votes = get_bill_votes(get_field('billtrack_id'));
                // if(have_rows('voting_history')): while(have_rows('voting_history')): the_row();
                //     foreach($votes as $vote){
                //         if(get_sub_field('vote_id') == $vote->VoteID){
                //             update_sub_field('yes_votes', $vote->YesVotes);
                //             update_sub_field('no_votes', $vote->NoVotes);
                //             update_sub_field('other_votes', $vote->OtherVotes);
                //         }
                //     }
                // endwhile; endif;
            endwhile;
        } else { 
            // Create post object
            $bill_summury = get_bill_summury($bill->BillID);
            
            $new_bill = array(
              'post_title'    => $bill->StateBillID,
              'post_content'  => $bill_summury,
              'post_status'   => 'draft',
              'post_author'   => 1,
              'post_type' => 'vote',
            );
            $new_post_ID = wp_insert_post( $new_bill );
            update_field('title', $bill->BillName,  $new_post_ID );
            update_field('billtrack_id', $bill->BillID,  $new_post_ID );
            update_field('last_update_attempt_ts', 0,  $new_post_ID );
            update_field('last_update_success_ts', 0,  $new_post_ID );
            
        }
        // print_r($new_post_ID);
        //  print_r('+++++++++++');

        wp_reset_postdata();       
    }
    $all_bills = new WP_Query(array(
        'post_type'         => 'vote',
        'suppress_filters'  => false,
        'showposts'       => -1,
        'post_status'       => array('draft', 'publish'),
        'meta_key' => 'last_update_attempt_ts',
        'orderby' => "meta_value",
        'order' => "ASC"
    ));
    //print_r($all_bills->posts);die();
    if(!$all_bills)
        die("can't get bills list from DB");

    $first_bill = false;
    $nowts = time();
    $limitts = $nowts - 10*60*60; //-10 hours

    foreach($all_bills->posts as $wp_bill){
        //print_r($wp_bill); die();
        $a_ts = get_field("last_update_attempt_ts", $wp_bill->ID);
        $s_ts = get_field("last_update_success_ts", $wp_bill->ID);
        $billtrack_id = get_field("billtrack_id", $wp_bill->ID);
        $voting_history = get_field('voting_history', $wp_bill->ID);
        //print_r($voting_history);
        if(empty($billtrack_id))
            continue;
        if($a_ts > $limitts)
            continue;
        
        $first_bill = $wp_bill;
        //print_r($first_bill);die();
        echo "<hr/><br/>working with  <br/>title : ".get_the_title( $first_bill->ID )." <br/>billtrack_id : ".$billtrack_id."<br/> postID : ".($first_bill->ID)."<hr/>";
        break;
    }

    if(!$first_bill) // too early to update
        die("all bill entries are up to date");

    update_field("last_update_attempt_ts", time(), $first_bill->ID);

    $bill = $first_bill;
    $votes = get_bill_votes(get_field('billtrack_id', $first_bill->ID));
    //print_r($votes);die();
    $voting_history_arr = array();
    if(have_rows('voting_history', $first_bill->ID)): while(have_rows('voting_history', $first_bill->ID)): the_row();
        $voting_history_arr[] = get_sub_field('vote_id');
    endwhile; endif;
    //print_r($voting_history_arr);die();
    if($votes){
        foreach ($votes as $vote) {
            $vote_id = $vote->VoteID;
            if(!in_array($vote_id, $voting_history_arr)){
                $vote_date = date("Ymd", strtotime($vote->VoteDate));
                $votes_count = $vote->YesVotes + $vote->NoVotes + $vote->OtherVotes;
                if( $votes_count>37 ){ $commitee_floor = 'floor'; } else {$commitee_floor = 'committee';};
               
                $row = array(
                    'field_5a5f6d4a0ae1a' =>  $vote_id,
                    'field_5a5f6d5c0ae1b' =>  $vote_date,
                    'field_5a5f6d910ae1c' =>  $commitee_floor,
                    'field_5a61f5744032d' =>  $vote->YesVotes,
                    'field_5a61f58b4032e' =>  $vote->NoVotes,
                    'field_5a61f5934032f' =>  $vote->OtherVotes
                );
                // print_r($row);
                // print_r($new_post_ID);
                //print_r($row);die();
                $i = add_row('field_5a5f6d320ae19', $row, $first_bill->ID );
                unset($row);
                unset($vote_id);
                unset($vote_date);
                unset($commitee_floor);
                //return true;
            }
        }
        //return false;                
    }
    unset($voting_history_arr);
    update_field("last_update_success_ts", time(), $first_bill->ID);
   
    return true;
    //return false;
}

function get_person_id( $post_id ){
    //$post_id = 13; // David Chiu ID
    if ( $post_id ){
        $person_name = get_the_title( $post_id );

        $person_name = str_replace('&#8217;', "'", $person_name);
        
        /*** Person's Last Name ***/
        $pieces = explode(' ', $person_name);
        $person_last_name = array_pop($pieces);
        /*************************/
        
        /*** API Request ***/ 
        $authorization = "Authorization: apikey BILLTRACK_API";
        $url = 'https://www.billtrack50.com/BT50Api/2.0/json/Legislators?LegislatorName='. $person_last_name .'&StateCodes=CA';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);

        curl_close($ch);
        $response_a = json_decode($response);
        $found_legislators = $response_a->Legislators;

        if($found_legislators){
            foreach($found_legislators as $legislator){
                if ($person_name == $legislator->Name){
                    update_field('billtrack_id', $legislator->LegislatorID,  $post_id );                 
                }
            }
            return true;
        }
    } else {
        return ;
    }
   
}

function get_people(){
    $all_people = get_posts(array(
        'post_type' => 'people',
        'numberposts' => -1,
        'suppress_filters' => false,
        'post_status' => 'publish',
    ));
    if($all_people){
        foreach($all_people as $i => $person){
            $success = get_person_id($person->ID);
        }
    }
}

function get_votes_by_scorecard_and_legislator_id($person_id){
    /*** API Request ***/ 
    $authorization = "Authorization: apikey BILLTRACK_API";
    
    $scorecards = get_scorecards();
    if($scorecards && $scorecards->Scorecards){
        $votes_full = array();
        foreach($scorecards->Scorecards as $scorecard){
            if($scorecard->ScorecardName == '2020 Courage Score'){
                $url = 'https://www.billtrack50.com/BT50Api/2.0/json/Scorecards/'.$scorecard->ScorecardID.'/Legislators/'. $person_id .'/Votes';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($ch);

                curl_close($ch);
                $response_a = json_decode($response);
                $votes = $response_a->BillVotes;
                $votes_full = $votes_full + $votes;
            }
        }
    }    
    return  $votes_full;
}

function save_votings_single_person(){

    // prof_flag('start');
    // echo "<br/>NOW:".date("Y-m-d H:i:s"); 

    //zero all empty "update" fields
    // prof_flag('START: get peoples with empty "update" fields ');
    $all_people = get_posts(array(
        'post_type' => 'people',
        //'post__in' => array(13),
        'numberposts' => 1,
        'suppress_filters' => false,
        'post_status' => 'publish',
    ));
    foreach($all_people as $person){
        $a_ts = get_field("last_update_attempt_ts", $person->ID);
        $s_ts = get_field("last_update_success_ts", $person->ID);
        // echo "<br/> {$person->ID} == (".gettype($a_ts).")[$a_ts] (".gettype($s_ts).")[$s_ts]";

        if($a_ts == "" || is_null($a_ts))
            update_field("last_update_attempt_ts", 0, $person->ID);
        if($s_ts == "" || is_null($s_ts))
            update_field("last_update_success_ts", 0, $person->ID);
    }
    

    
    //get first person ordered by "last_update_attempt_ts", later filtered by "billtrack_id" not null
    $all_people = get_posts(array(
        //'p' => 999,
        'post_type' => 'people',
        //'post__in' => array(13),
        'numberposts' => -1,
        'suppress_filters' => false,
        'post_status' => 'publish',
        'meta_key' => 'last_update_attempt_ts',
        // 'meta_compare' => 'EXISTS',
        'orderby' => "meta_value",
        'order' => "ASC"
    ));


    if(!$all_people)
        die("can't get people's list from DB");

    $first_person = false;
    $nowts = time();
    $limitts = $nowts - 10*60*60; //-10 hours

    foreach($all_people as $person){
        $a_ts = get_field("last_update_attempt_ts", $person->ID);
        $s_ts = get_field("last_update_success_ts", $person->ID);
        $billtrack_id = get_field("billtrack_id", $person->ID);
        if(empty($billtrack_id))
            continue;
        if($a_ts > $limitts)
            continue;
        //print_r($billtrack_id);
        $first_person = $person;
        echo "<hr/><br/>working with  <br/>title : ".get_the_title( $first_person->ID )." <br/>billtrack_id : ".$billtrack_id."<br/> postID : ".($first_person->ID)."<hr/>";
        break;
    }

    if(!$first_person) // too early to update
        die("all people entries are up to date");

    update_field("last_update_attempt_ts", time(), $first_person->ID);

    $person = $first_person;

    $votes = get_votes_by_scorecard_and_legislator_id(get_field('billtrack_id', $person->ID));

    if($votes){
        foreach($votes as $vote){                            

            $bills_query = get_posts(array(
                'meta_query' => array(
                    array(
                        'key' => 'billtrack_id',
                        'value' => $vote->BillID,
                    )
                ),
                'post_type'         => 'vote',
                'suppress_filters'  => false,
                'showposts'       => 1,
                'post_status'       => array('draft', 'publish'),
            ));
            if($bills_query){
                foreach($bills_query as $bill_post){
                    $billtrack_bill_id = get_field('billtrack_id',$bill_post->ID);
                    
                    if($vote->Vote == 'Y'){
                        $vote_label = 'y';
                    } elseif ($vote->Vote == 'N'){
                        $vote_label = 'n';
                    } else{
                        $vote_label = 'a';
                    }
                    $vote_date = date("Ymd", strtotime($vote->VoteDate));
                    if(have_rows('voting_history', $bill_post->ID)){
                        while (have_rows('voting_history',$bill_post->ID)) {
                            the_row();
                            if (get_sub_field('vote_date') == $vote_date) {
                                if(get_sub_field('committee_floor') == 'floor'){
                                    $floor_committee = 'floor_votes';
                                } else {
                                    $floor_committee = 'committee_votes';
                                }
                            }
                        }
                    }

                    $row = array(
                        'bill_number'    => $bill_post->ID ,
                        'bill_id'        => $billtrack_bill_id,
                        'vote'           => $vote_label,
                        'vote_date'      => $vote_date,
                        'floorcommittee' => $floor_committee,
                    );
                     
                    // die();
                    $row_exists = 0;
                    if(have_rows('voting',$person->ID)): while(have_rows('voting',$person->ID)): the_row();
                        if( get_sub_field('bill_id') == $billtrack_bill_id && get_sub_field('vote') == $vote_label && get_sub_field('vote_date') == $vote_date && get_sub_field('floorcommittee') == $floor_committee ):
                            $row_exists = 1;
                        endif;
                    endwhile; endif;
                    if($row_exists == 0){
                        $i = add_row( 'voting', $row, $person->ID );
                    }
                    // print_r($i);
                    // print_r('<br/>');
                    unset($billtrack_bill_id);
                    unset($vote_label);
                    unset($floor_committee);
                    unset($vote_date);
                    unset($row);
                }
            }unset($bills_query);
        }
        print_r(' -- votes updated'); 
       
    }

    unset($votes);

    update_field("last_update_success_ts", time(), $person->ID);
   
   return true;
}

function get_bill_authors($bill_id){
    /*** API Request ***/ 
    $authorization = "Authorization: apikey BILLTRACK_API";
    $url = 'https://www.billtrack50.com/BT50Api/2.0/json/Bills/'.$bill_id.'/Sponsors';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);

    curl_close($ch);
    $response_a = json_decode($response);
    return $response_a;
}

add_filter('posts_where',  __NAMESPACE__ . '\\jb_filter_acf_meta');
function jb_filter_acf_meta( $where ) {
    $where = str_replace('meta_key = \'voting_history_$_vote_date', "meta_key LIKE 'voting_history_%_vote_date", $where);
    return $where;
}

// TEST AJAX CALLS
function callExampleFunction() {
    $name = \FrontPage::exampleFunction();
    wp_send_json_success($name);
}

add_action('wp_ajax_nopriv_call_example_function', __NAMESPACE__ .'\\callExampleFunction' );
add_action('wp_ajax_admin_call_example_function', __NAMESPACE__ .'\\callExampleFunction' );