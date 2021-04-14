<?php

namespace App\Controllers;

use Sober\Controller\Controller;
use WP_Query;

class PagePartners extends Controller
{
    public function partners() {
        $args = array(
            'post_type' => 'partner',
            'orderby' => 'title',
            'showposts' => -1,
	    );
	    $the_query = new WP_Query( $args );
	    return $the_query->posts;
    }
}