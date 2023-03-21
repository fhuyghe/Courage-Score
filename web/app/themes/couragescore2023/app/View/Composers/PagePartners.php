<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WP_Query;

class PagePartners extends Composer
{
    public function partners() {
        $args = array(
            'post_type' => 'partner',
            'orderby' => array( 
                'title' => 'ASC',
            ),
            'meta_key' => 'scorecard_link',
            'showposts' => -1,
	    );
	    $the_query = new WP_Query( $args );
	    return $the_query->posts;
    }
}