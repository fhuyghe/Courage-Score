<?php

namespace App\Controllers;

use Sober\Controller\Controller;
use WP_Query;

class App extends Controller
{
    public function siteName()
    {
        return get_bloginfo('name');
    }

    public static function title()
    {
        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }
            return __('Latest Posts', 'sage');
        }
        if (is_archive()) {
            return get_the_archive_title();
        }
        if (is_search()) {
            return sprintf(__('Search Results for %s', 'sage'), get_search_query());
        }
        if (is_404()) {
            return __('Not Found', 'sage');
        }
        return get_the_title();
    }

    public static function getLegislator($body, $district){
        $args = array(
            'post_type'     => 'people',
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
}
