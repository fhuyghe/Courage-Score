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
        $data['partners_scores'] = get_field('partners_scores');
        $data['contributions'] = get_field('corporate_contributions_to_legislator');
        $data['titles'] = get_field('single_representatives', 'option');

	    return $data;
    }

    static function votes($year){ 
        $year = intval($year);
        $votes = get_field('voting');
        $votesByYear = $votes ? array_filter($votes, function($vote) use ($year){
            if($vote['bill_number']):
            return date("Y", strtotime(get_field('floor_voted_date',$vote['bill_number']))) == $year
                    || 
                    date("Y", strtotime($vote['vote_date'])) == $year;
            endif;
            }) : [];

            return $votesByYear;
    }
}
