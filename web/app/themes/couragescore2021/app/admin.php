<?php

namespace App;

/**
 * Theme customizer
 */
add_action('customize_register', function (\WP_Customize_Manager $wp_customize) {
    // Add postMessage support
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->selective_refresh->add_partial('blogname', [
        'selector' => '.brand',
        'render_callback' => function () {
            bloginfo('name');
        }
    ]);
});

/**
 * Customizer JS
 */
add_action('customize_preview_init', function () {
    wp_enqueue_script('sage/customizer.js', asset_path('scripts/customizer.js'), ['customize-preview'], null, true);
});

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

