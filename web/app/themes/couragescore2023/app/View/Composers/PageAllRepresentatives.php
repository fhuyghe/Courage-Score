<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WP_Query;

class PageAllRepresentatives extends Composer
{

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'page-all-representatives',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'allRepresentatives' => $this->all_representatives()
        ];
    }

    public function all_representatives() {
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