<?php

namespace App\Controllers;

use Sober\Controller\Controller;
use WP_Query;

class TemplateCustomScore extends Controller
{
    public function representatives() {
        $args = array(
            'post_type' => 'people',
            'orderby' => 'title',
		    'order' => 'ASC',
            'showposts' => -1,
	    );
	    $the_query = new WP_Query( $args );
	    return $the_query->posts;
    }
}