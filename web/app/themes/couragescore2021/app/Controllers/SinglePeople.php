<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class SinglePeople extends Controller
{
    public function data() {
        $data = [];

        $data['senate_or_assembly'] = get_field('senate_or_assembly');
        $data['district'] = get_field('district');
        $data['party'] = get_field('party');
        $data['scores'] = get_field('progressive_voting_by_member');

	    return $data;
    }

    static function votes($year){ 
        $year;
        $votes = get_field('voting');
        $votesByYear = array_filter($votes, function($vote) use ($year){
            if($vote['bill_number']):
            return $vote['floorcommittee'] == 'floor_votes' 
                && ( 
                    date("Y", strtotime($vote['bill_number']->floor_voted_date)) == $year
                    || 
                    date("Y", strtotime($vote['vote_date'])) == $year
                );
            endif;
            });

            return $votesByYear;
    }
}
