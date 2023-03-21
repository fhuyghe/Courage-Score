<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WP_Query;

class TemplateCustomScore extends Composer
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