<?php

namespace App;

use Roots\Sage\Container;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

define('GOOGLEMAPS_API', getenv('GOOGLEMAPS_API'));
define('OPENSTATES_API', getenv('OPENSTATES_API'));
define('BILLTRACK_API', getenv('BILLTRACK_API'));

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('sage/main.css', asset_path('styles/main.css'), false, null);
    wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);
    $ajax_params = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajax_nonce' => wp_create_nonce('my_nonce'),
    );
    wp_localize_script('sage/main.js', 'ajax_object', $ajax_params);

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

}, 100);

add_action('admin_enqueue_scripts', function () {
    $ajax_params = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajax_nonce' => wp_create_nonce('my_nonce'),
    );

    wp_enqueue_script('sage/admin.js', asset_path('scripts/admin.js'), ['jquery'], null, true);
    wp_localize_script('sage/admin.js', 'ajax_object', $ajax_params);
});

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

/************************* */
//Load Bills Submenu page
/************************* */
function load_bills_submenu() {
    add_submenu_page(
        'tools.php',
        'Load Bills',
        'Load Bills',
        'manage_options',
        'load-bills',
        __NAMESPACE__ . '\\load_bills_menu_callback',
        null
    );
}

function load_bills_menu_callback() {
    echo template('partials.admin-load-bills');
}
add_action('admin_menu', __NAMESPACE__ . '\\load_bills_submenu');

/************************* */
//Load Votes Submenu page
/************************* */
function load_votes_submenu() {
    add_submenu_page(
        'tools.php',
        'Load Votes',
        'Load Votes',
        'manage_options',
        'load-votes',
        __NAMESPACE__ . '\\load_votes_menu_callback',
        null
    );
}

function load_votes_menu_callback() {
    echo template('partials.admin-load-votes');
}
add_action('admin_menu', __NAMESPACE__ . '\\load_votes_submenu');


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

// Get the district numbers based on your address.
function getDistrict(){
    // example address 1228 O St , Sacramento, CA, 95814

    $url = "https://www.googleapis.com/civicinfo/v2/representatives?address=" . urlencode($_REQUEST['address']) . "&key=" . GOOGLEMAPS_API;
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
    $offices = $response_a->offices;
    if($offices){
        foreach ($offices as $office){
            $pos = strrpos($office->divisionId, '/');
            $divisionIds = $pos === false ? $office : substr($office->divisionId, $pos + 1);
            list( $district_type, $district_number ) = explode(":", $divisionIds);
            $divisions[$district_type] = $district_number;
        }
    }
    //Assembly result
    $district_info[0] = template('partials.representative-block', ['post' => \App::getLegislator('assembly', $divisions['sldl'])]);
    //Senate result
    $district_info[1] = template('partials.representative-block', ['post' => \App::getLegislator('senate', $divisions['sldu'])]);
    return( $district_info );
}

function getDistrict_ajax(){
    $district_info = getDistrict($_REQUEST['address']);
    wp_send_json_success( $district_info );
}

add_action('wp_ajax_get_district_ajax', __NAMESPACE__ .'\\getDistrict_ajax' );
add_action('wp_ajax_nopriv_get_district_ajax', __NAMESPACE__ .'\\getDistrict_ajax' );

function get_district_data( $district_type , $district_id){
    $openstates_data_var = 'openstates_data_'.$district_type.'_'.$district_id.'_json';
    $cached_data = get_transient( $openstates_data_var );
    if( true == $cached_data ){
         return  $cached_data;
    }else{
        $url = "https://v3.openstates.org/people?jurisdiction=ca&org_classification=$district_type&district=$district_id&apikey=6ece8713-9334-4204-a913-dea301f01663";// . OPENSTATES_API;
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
        return $response_a;
        
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