<?php

/**
 * Theme setup.
 */

namespace App;
use WP_Query;

use function Roots\bundle;

define('GOOGLEMAPS_API', getenv('GOOGLEMAPS_API'));
define('OPENSTATES_API', getenv('OPENSTATES_API'));
define('BILLTRACK_API', getenv('BILLTRACK_API'));

/**
 * Register the theme assets.
 *
 * @return void
 */
add_action('wp_enqueue_scripts', function () {

    $is_dev_request = getenv('WP_ENV') == 'development';
    $rest_url = $is_dev_request ? 'http://localhost:3000/wp/wp-admin/admin-ajax.php' : admin_url('admin-ajax.php');

    $ajax_params = array(
        'ajax_url' => $rest_url,
        'ajax_nonce' => wp_create_nonce('my_nonce'),
    );

    bundle('app')->enqueue()->localize('ajax_object', $ajax_params);;
}, 100);

add_action('admin_enqueue_scripts', function () {
    $ajax_params = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajax_nonce' => wp_create_nonce('my_nonce'),
    );

    wp_enqueue_script('sage/admin.js', asset('scripts/admin.js'), ['jquery'], null, true);
    wp_localize_script('sage/admin.js', 'ajax_object', $ajax_params);
});


/**
 * Register the theme assets with the block editor.
 *
 * @return void
 */
add_action('enqueue_block_editor_assets', function () {
    bundle('editor')->enqueue();
}, 100);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from the Soil plugin if activated.
     *
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil', [
        'clean-up',
        'nav-walker',
        'nice-search',
        'relative-urls',
    ]);

    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});

//Filter for deep ACF queries
function my_posts_where( $where ) {
	
	$where = str_replace("meta_key = 'voting_history_$", "meta_key LIKE 'voting_history_%", $where);

	return $where;
}

add_filter('posts_where', __NAMESPACE__ . '\\my_posts_where');

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

    acf_add_options_sub_page(array(
        'page_title'    => 'Contributions Settings',
        'menu_title'    => 'Contributions',
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
    echo view('partials.admin-load-bills')->render();
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
    echo view('partials.admin-load-votes')->render();
}
add_action('admin_menu', __NAMESPACE__ . '\\load_votes_submenu');

/************************* */
//Load Scores Submenu page
/************************* */
function load_scores_submenu() {
    add_submenu_page(
        'tools.php',
        'Load Scores',
        'Load Scores',
        'manage_options',
        'load-scores',
        __NAMESPACE__ . '\\load_scores_menu_callback',
        null
    );
}

function load_scores_menu_callback() {
    echo view('partials.admin-load-scores')->render();
}
add_action('admin_menu', __NAMESPACE__ . '\\load_scores_submenu');


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
    'rewrite' => array( 'slug' => 'representative' ),
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
    'menu-icon' => 'dashicons-media-document',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'bill' ),
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => null, 
    'supports' => array('title','editor','thumbnail'),
    'taxonomies' => array('vote-category', 'vote-topic')
  ); 
  register_post_type('vote',$args);
}

// CPT "Partners" registration
add_action('init',  __NAMESPACE__ . '\\Partners_custom_init');
function Partners_custom_init() 
{
  $labels = array(
    'name' => _x('Partner', 'post type general name'),
    'singular_name' => _x('Partner', 'post type singular name'),
    'menu_name' => 'Partners'
  );
  
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'menu-icon' => 'dashicons-groups',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'partner' ),
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title','thumbnail'),
    'taxonomies' => array('partner-category')
  ); 
  register_post_type('partner',$args);
}

add_action( 'init',  __NAMESPACE__ . '\\create_partner_taxonomies' );
function create_partner_taxonomies() 
{
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name' => _x( 'Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Categories' ),
    'all_items' => __( 'All Categories' ),
    'parent_item' => __( 'Parent Category' ),
    'parent_item_colon' => __( 'Parent Category:' ),
    'edit_item' => __( 'Edit Category' ), 
    'update_item' => __( 'Update Category' ),
    'add_new_item' => __( 'Add New Category' ),
    'new_item_name' => __( 'New Category Name' ),
    'menu_name' => __( 'Categories' ),
  );    

  register_taxonomy('partner-category','partner', array(
    'public'=>true,
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    ));
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

function getLegislator($body, $district){
    $args = array(
        'post_type'     => 'people',
        'post_status'        => 'publish',
        'showposts' => 1,
        'meta_query'	=> array(
            'relation'		=> 'AND',
            array(
                'key'	 	=> 'district',
                'value'	  	=> $district,
                'compare' 	=> '='
            ),
            array(
                'key'	  	=> 'senate_or_assembly',
                'value'	  	=> $body,
                'compare' 	=> '='
            )
        )
    );
    $the_query = new WP_Query( $args );
    return $the_query->posts[0];
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
    
    $assemblyRep = array_key_exists('sldl', $divisions) ? getLegislator('assembly', $divisions['sldl']) : null;
    $senateRep = array_key_exists('sldu', $divisions) ? getLegislator('senate', $divisions['sldu']) : null;
    //Assembly result
    $district_info[0] = $assemblyRep 
        ? view('partials.rep-block', ['post' => $assemblyRep])->render() 
        : '<div class="norep"><i class="fal fa-exclamation-circle"></i> No representative found for this assembly district.</div>';
    //Senate result
    $district_info[1] = $senateRep 
        ? view('partials.rep-block', ['post' => $senateRep])->render() 
        : '<div class="norep"><i class="fal fa-exclamation-circle"></i> No representative found for this senate district.</div>';
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
        $url = "https://v3.openstates.org/people?jurisdiction=ca&org_classification=$district_type&district=$district_id&apikey=" . OPENSTATES_API;
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


/***************************** */
// Show the Chamber in admin menu
/***************************** */
/*
 * Add columns to exhibition post list
 */
function add_acf_columns ( $columns ) {
    return array_merge ( $columns, array ( 
      'senate_or_assembly' => __ ( 'Chamber' ),
      'party'   => __ ( 'Party' ) 
    ) );
  }
add_filter ( 'manage_people_posts_columns', __NAMESPACE__ . '\\add_acf_columns' );
/*
 * Add columns to exhibition post list
 */
function people_custom_column ( $column, $post_id ) {
    switch ( $column ) {
      case 'senate_or_assembly':
            $chamber = get_field ( 'senate_or_assembly', $post_id );
            $district = get_field ( 'district', $post_id );
            if($district){
                echo $chamber . ' district ' . $district;
            } else {
                echo 'No District';
            }
        break;
        case 'party':
            echo get_field ( 'party', $post_id );
        break;
    }
  }
add_action ( 'manage_people_posts_custom_column', __NAMESPACE__ . '\\people_custom_column', 10, 2 );



/***********/
// BILLS
/***********/

/*** Get New Bills ***/
function get_scorecards(){
    
    /*** API Request ***/ 
    $authorization = "Authorization: apikey " . BILLTRACK_API;
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
    $authorization = "Authorization: apikey " . BILLTRACK_API;
    
    $url = 'https://www.billtrack50.com/BT50Api/2.0/json/Scorecards/'. $_GET['scorecardID'] .'/Bills';
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

    $bills = $response_a->bills;
    //$bill_list_full = $bill_list_full + $bills;

    wp_send_json_success($bills);
}
add_action('wp_ajax_get_bills_by_scorecard', __NAMESPACE__ .'\\get_bills_by_scorecard' );

// GET SCORES BY SCORECARD
function get_scores_by_scorecard(){
     set_time_limit(0);
    /*** API Request ***/ 
    $authorization = "Authorization: apikey " . BILLTRACK_API;
    
    $url = 'https://www.billtrack50.com/BT50Api/2.0/json/Scorecards/'. $_GET['scorecardID'] .'/legislators';
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

    $legislators = $response_a->legislators;
    //$bill_list_full = $bill_list_full + $bills;

    wp_send_json_success($legislators);
}
add_action('wp_ajax_get_scores_by_scorecard', __NAMESPACE__ .'\\get_scores_by_scorecard' );

function get_bill_summary($bill_id){
    /*** API Request ***/ 
    $authorization = "Authorization: apiKey " . BILLTRACK_API;
    $url = 'https://www.billtrack50.com/bt50api/2.0/json/bills/' . $bill_id;
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

    $bill_summary = $response_a->bill->summary;
    return  $bill_summary;
}

/*******************/
// GET VOTES PER BILL
/*******************/


function get_bill_votes($bill_id){
    /*** API Request ***/ 
    $authorization = "Authorization: apikey " . BILLTRACK_API;
    $url = 'https://www.billtrack50.com/BT50Api/2.0/json/Bills/' . $bill_id . '/Votes';
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
    $votes_arr = $response_a->votes;
    //return $response_a;
    return $votes_arr;
}

/*******************/
// UPDATE SCORES
/*******************/

function update_score(){

    $legislator = $_POST['legislator'];
    $scorecard =  $_POST['scorecard'];

    
    
    // Find out if bill is in the DB
    $args = array(
        'post_type'         => 'people',
        'post_status'       => array('draft', 'publish'),
        'meta_query'        => array(
            array(
                'key' => 'billtrack_id',
                'value' => $legislator['legislatorID'],
                'compare' => '=',
            )
        )
            );

    $legislator_query = new WP_Query($args);

    if(!$legislator_query->have_posts()){
        wp_send_json_error('No legislator');
    }

    $wp_legislator = $legislator_query->posts[0];

    //Add score to legislator
    $row = array(
        'name'   => $scorecard,
        'score'   => floor($legislator['voteIndex']),
    );

    // Get existing alternate scores
    $rows = get_field('alternate_scores', $wp_legislator->ID);
    
    if($rows){
        foreach($rows as $key=>$val){
            if($val['name'] == $scorecard){
                // Update the value
                update_row('alternate_scores', $key + 1, $row, $wp_legislator->ID);
                wp_send_json_success('Updated');
            }
        }
    }
    
    // Create the row
    add_row('alternate_scores', $row,  $wp_legislator->ID);
    wp_send_json_success('Score added');
}

add_action('wp_ajax_update_score', __NAMESPACE__ .'\\update_score' );
add_action('wp_ajax_nopriv_update_score', __NAMESPACE__ .'\\update_score' );
    

/*******************/
// UPDATE BILLS
/*******************/

function update_bill(){

    if (!isset($_POST)) {
        wp_send_json_error('No bill');
    }

    $bill = $_POST['bill'];
    //wp_send_json_success($bill['billID']);
    //wp_send_json_success(var_export($bill, true));

    // Find out if bill is in the DB
    $args = array(
        'post_type'         => 'vote',
        'post_status'       => array('draft', 'publish'),
        'meta_query'        => array(
            array(
                'key' => 'billtrack_id',
                'value' => $bill['billID'],
                'compare' => '=',
                )
            )
        );

        $bills_query = new WP_Query($args);
        $wp_bill = $bills_query->have_posts() ? $bills_query->posts[0] : null;
        //wp_send_json_success(var_export($bills_query, true));
        
        // Create post if bill doesn't exist
        if ( !$bills_query->have_posts()){
            // Create post object
    $bill_summary = get_bill_summary($bill['billID']);
    
    $new_bill = array(
        'post_title'    => $bill['stateBillID'],
        'post_content'  => $bill_summary,
        'post_status'   => 'draft',
        'post_author'   => 1,
        'post_type' => 'vote',
    );
    $new_post_ID = wp_insert_post( $new_bill );
    update_field('title', $bill['billName'],  $new_post_ID );
    update_field('billtrack_id', $bill['billID'],  $new_post_ID );
    update_field('last_update_attempt_ts', 0,  $new_post_ID );
    update_field('last_update_success_ts', 0,  $new_post_ID );

    // Get post from database
    $wp_bill_query = new \WP_Query(array(
        'post_type'     => 'vote',
        'p'            => $new_post_ID
    ));
    $wp_bill = $wp_bill_query->post;
}

//Check that we didn't try to update it in the last 10 hours
$limitts = time() - 10*60*60; //-10 hours
$a_ts = get_field("last_update_attempt_ts", $wp_bill->ID);
$s_ts = get_field("last_update_success_ts", $wp_bill->ID);
$billtrack_id = get_field("billtrack_id", $wp_bill->ID);
$voting_history = get_field('voting_history', $wp_bill->ID);

if(empty($billtrack_id) || $a_ts > $limitts){
    wp_send_json_error('Already updated');
}

update_field("last_update_attempt_ts", time(), $wp_bill->ID);


//Get votes
$votes = get_bill_votes($billtrack_id);

//Get current vote IDs
$voting_history_arr = array();
if(have_rows('voting_history', $wp_bill->ID)): 
    while(have_rows('voting_history', $wp_bill->ID)): the_row();
        $voting_history_arr[] = get_sub_field('vote_id');
    endwhile; 
endif;

$updatedVotes = 0;

if($votes){
    foreach ($votes as $vote) {
        $vote_id = $vote->voteID;
        if(!in_array($vote_id, $voting_history_arr)){
            $updatedVotes++;
            $vote_date = date("Ymd", strtotime($vote->voteDate));
            $votes_count = $vote->yesVotes + $vote->noVotes + $vote->otherVotes;
            if( $votes_count>37 ){ $commitee_floor = 'floor'; } else {$commitee_floor = 'committee';};
           
            $row = array(
                'field_5a5f6d4a0ae1a' =>  $vote_id,
                'field_5a5f6d5c0ae1b' =>  $vote_date,
                'field_5a5f6d910ae1c' =>  $commitee_floor,
                'field_5a61f5744032d' =>  $vote->yesVotes,
                'field_5a61f58b4032e' =>  $vote->noVotes,
                'field_5a61f5934032f' =>  $vote->otherVotes
            );
       
            $i = add_row('field_5a5f6d320ae19', $row, $wp_bill->ID );
            unset($row);
            unset($vote_id);
            unset($vote_date);
            unset($commitee_floor);
        }
    }           
}
unset($voting_history_arr);
update_field("last_update_success_ts", time(), $wp_bill->ID);

wp_send_json_success($updatedVotes . 'votes updated');
}
add_action('wp_ajax_update_bill', __NAMESPACE__ .'\\update_bill' );
add_action('wp_ajax_nopriv_update_bill', __NAMESPACE__ .'\\update_bill' );


/********************** */
// LEGISLATORS
/********************** */

//Get People's Billtrack ID
function get_person_id( $post_id ){
    if ( !$post_id )
        return;

    $person_name = get_the_title( $post_id );

    $person_name = str_replace('&#8217;', "'", $person_name);
    
    /*** Person's Last Name ***/
    $pieces = explode(' ', $person_name);
    $person_last_name = array_pop($pieces);
    /*************************/
    
    /*** API Request ***/ 
    $authorization = "Authorization: apikey " . BILLTRACK_API;
    $url = 'https://www.billtrack50.com/BT50Api/2.0/json/legislators?legislatorName='. $person_last_name .'&stateCodes=CA';

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
    $found_legislators = $response_a->legislators;

    if($found_legislators){
        foreach($found_legislators as $legislator){
            if ($person_name == $legislator->name){
                update_field('billtrack_id', $legislator->legislatorID,  $post_id );
                return $legislator->legislatorID;                 
            }
        }
    }
}

function get_votes_by_scorecard_and_legislator_id($scorecardID, $person_id){
    //return[$scorecardID, $person_id];
    /*** API Request ***/ 
    $authorization = "Authorization: apikey " . BILLTRACK_API;
    
    $url = 'https://www.billtrack50.com/BT50Api/2.0/json/scorecards/'.$scorecardID. '/legislators/' . $person_id .'/votes';

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
    $votes = $response_a->billVotes;

    return  $votes;
}


// ***************************************
// **** GET REPS TO UPDATE
// ***************************************

function get_reps_to_update(){
    $all_people = get_posts(array(
        'post_type' => 'people',
        'numberposts' => -1,
        'suppress_filters' => false,
        'post_status' => 'publish',
        'order' => "ASC"
    ));

    $response = array();

    $nowts = time();
    $limitts = $nowts - 10*60*60; //-10 hours

    foreach($all_people as $person){
        $a_ts = get_field("last_update_attempt_ts", $person->ID);
        $s_ts = get_field("last_update_success_ts", $person->ID);
        $billtrack_id = get_field("billtrack_id", $person->ID);

        //If no attempt before, zero in the counters
        if($a_ts == "" || is_null($a_ts))
            update_field("last_update_attempt_ts", 0, $person->ID);
        if($s_ts == "" || is_null($s_ts))
            update_field("last_update_success_ts", 0, $person->ID);

        // If missing IF, get it
        if(empty($billtrack_id)){
            $billtrack_id = get_person_id($person->ID);
            update_field("billtrack_id", $billtrack_id, $person->ID);
        }

        // If recent attempt, ignore
        if($a_ts < $limitts)
            $response[] = array(
                'ID' => $person->ID,
                'name' => $person->post_title,
                'billtrack_id' => $billtrack_id
            );
    }

    wp_send_json_success($response);
}

add_action('wp_ajax_get_reps_to_update', __NAMESPACE__ .'\\get_reps_to_update' );
add_action('wp_ajax_nopriv_get_reps_to_update', __NAMESPACE__ .'\\get_reps_to_update' );




// ***************************************
// **** UPDATE VOTES
// ***************************************

function update_votes($scorecardID){
    $scorecardID = $_POST['scorecardID'];
    $person = $_POST['rep'];
    // too early to update
    if(!$person) 
        wp_send_json_error("No update to be made");

    
    update_field("last_update_attempt_ts", time(), $person['ID']);


    $votes = get_votes_by_scorecard_and_legislator_id($scorecardID, $person['billtrack_id']);

    if($votes){
        foreach($votes as $vote){                            

            $bills_query = get_posts(array(
                'meta_query' => array(
                    array(
                        'key' => 'billtrack_id',
                        'value' => $vote->billID,
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
                    
                    if($vote->vote == 'Y'){
                        $vote_label = 'y';
                    } elseif ($vote->vote == 'N'){
                        $vote_label = 'n';
                    } else{
                        $vote_label = 'a';
                    }
                    $vote_date = date("Ymd", strtotime($vote->voteDate));

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
                     
                    $row_exists = false;
                    $index = 1; //ACF rows index start at 1

                    if(have_rows('voting', $person['ID'])): while(have_rows('voting', $person['ID'])): the_row();
                        if( get_sub_field('bill_id') == $billtrack_bill_id && get_sub_field('vote') == $vote_label && get_sub_field('vote_date') == $vote_date && get_sub_field('floorcommittee') == $floor_committee ):
                            update_row('voting', $index, $row, $person['ID']);
                            $row_exists = true;
                            break;
                        endif;
                        $index++;
                    endwhile; endif;

                    if($row_exists == false){
                        $i = add_row( 'voting', $row, $person['ID'] );
                    }

                    unset($billtrack_bill_id);
                    unset($vote_label);
                    unset($floor_committee);
                    unset($vote_date);
                    unset($row);
                }
            }unset($bills_query);
        }
    }

    $response = [
        "votes" => count($votes)
    ];

    unset($votes);

    update_field("last_update_success_ts", time(), $person['ID']);
    wp_send_json_success($response);
   
   return true;
}
add_action('wp_ajax_update_votes', __NAMESPACE__ .'\\update_votes' );
add_action('wp_ajax_nopriv_update_votes', __NAMESPACE__ .'\\update_votes' );

//Never used yet
function get_bill_authors($bill_id){
    /*** API Request ***/ 
    $authorization = "Authorization: apikey " . BILLTRACK_API;
    $url = 'https://www.billtrack50.com/BT50Api/2.0/json/bills/'.$bill_id.'/sponsors';
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