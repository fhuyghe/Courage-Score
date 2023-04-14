<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WP_Query;

class PageBills extends Composer
{

    public static function bills($year) {
        $start_date = $year . '0000';
        $finish_year = $year+1;
        $finish_date = $finish_year . '0000';

        $args = array(
            'post_type' => 'vote',
            'orderby' => 'title',
		    'order' => 'ASC',
            'showposts' => -1,
            'meta_query' => array(
				array(
					'key'     => 'committee_voted_date', 
					'value'   => array( $start_date, $finish_date ),
					'type'    => 'numeric',
					'compare' => 'BETWEEN',
				),
				array(
					'key'     => 'floor_voted_date',
					'value'   => array( $start_date, $finish_date ),
					'type'    => 'numeric',
					'compare' => 'BETWEEN',
				),
				array(
					'key'     => 'voting_history_$_vote_date',
					'value'   => array( $start_date, $finish_date ),
					'type'    => 'numeric',
					'compare' => 'BETWEEN',			
				),
				'relation' => 'OR',
			),
	    );
	    $the_query = new WP_Query( $args );
	    return $the_query->posts;
    }
}
