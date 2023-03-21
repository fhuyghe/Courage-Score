<?php

namespace App;

use function Roots\bundle;

// Get Legislator name suggestions
function get_name_suggestion() {
    $args = array(
        'posts_per_page' => 5, 
        's' => esc_attr($_REQUEST['text']), 
        'post_type' => 'people'
    );
    $the_query = new \WP_Query( $args );
    $suggestions = array();
    foreach($the_query->posts as $post){
        $suggestions[] = [
            'post' => $post,
            'url' => get_permalink($post->ID),
            'thumbnail' => get_the_post_thumbnail_url($post->ID, 'thumbnail'),
            'score' => template('partials.grade-display', ['post' => $post]),
        ];
    }
    wp_send_json_success($suggestions);
}

add_action('wp_ajax_get_name_suggestion', __NAMESPACE__ .'\\get_name_suggestion' );
add_action('wp_ajax_nopriv_get_name_suggestion', __NAMESPACE__ .'\\get_name_suggestion' );

// Get Votes per year
function get_votes_per_year() {
    $year = $_REQUEST['year'];
    $votes = \SinglePeople::get_vote($year);

    wp_send_json_success($votes);
}

add_action('wp_ajax_get_votes_per_year', __NAMESPACE__ .'\\get_votes_per_year' );
add_action('wp_ajax_nopriv_get_votes_per_year', __NAMESPACE__ .'\\get_votes_per_year' );


// Get Bills per year
function get_bills_per_year() {
    $year = $_REQUEST['year'];
    $bills = \PageBills::bills($year);

    $new_rows = [];
    foreach($bills as $bill){
        $new_rows[] = [ get_the_title($bill->ID), get_field('title', $bill->ID)];
    } 

    wp_send_json_success($new_rows);
}

add_action('wp_ajax_get_bills_per_year', __NAMESPACE__ .'\\get_bills_per_year' );
add_action('wp_ajax_nopriv_get_bills_per_year', __NAMESPACE__ .'\\get_bills_per_year' );


//Get Rep Scores
function get_score($post) {

    $score = 'na';
    $scores = get_field('progressive_voting_by_member', $post->ID);
    $current_year = date('Y');
    $vote_info = [];
    
    if($scores): 
        foreach($scores as $scoreRow):
            if($scoreRow['na'] == 1){$score = 'na';} else {$score = $scoreRow['score'];}
            $vote_info[] = array( 'year' => $scoreRow['years'] , 'score' => $score);
        endforeach;
    endif;
    
    $score = isset($vote_info[0]) ? $vote_info[0]['score'] : 'na'; 

    return $score;
}

function get_scores() {
    $postID = $_GET['postID'];
    $scores = get_field('progressive_voting_by_member', $postID);

    wp_send_json_success($scores);
}
add_action('wp_ajax_get_scores', __NAMESPACE__ .'\\get_scores' );
add_action('wp_ajax_nopriv_get_scores', __NAMESPACE__ .'\\get_scores' );

function get_auto_score($votes) {

    // Courage Score
    $points = 0;
    $voteNumber = 0;
    foreach ($votes as $vote) {
    if($vote['vote'] !== 'n_e'){
        //If the rep could vote
        $voteNumber++;
        if(get_field('oppose', $vote['bill_number']->ID)){
        //If the bill is bad
        if($vote['vote'] == 'n' || $vote['vote'] == 'a'){
            $points++;
        }
        } else {
        //If the bill is good
        if($vote['vote'] == 'y'){
            $points++;
        }
        }
    }
    }

    $score = $voteNumber > 0 ? round($points * 100 / $voteNumber) : 'na';

    return $score;
}

function get_letter($score){
    if ( $score == 'na' || empty($score) && $score != "0" ){
        $letter = 'NA';
    } elseif ($score < 60){
        $letter = 'F';
    } elseif($score < 70 && $score > 59){
        $letter = 'D';
    } elseif($score < 80 && $score > 69){
        $letter = 'C';
    } elseif($score < 90 && $score > 79){
        $letter = 'B';
    } elseif( $score > 89){
        $letter = 'A';
        if($score == 100){
            $letter = 'A+';
        };
    }

    return $letter;
}

function get_color($score){
    if ( $score == 'na' || empty($score) && $score != "0" ){
        $color = 'grey';
    } elseif ($score < 60){
        $color = 'red';
    } elseif($score < 70 && $score > 59){
        $color = 'orange';
    } elseif($score < 80 && $score > 69){
        $color = 'yellow';
    } elseif($score < 90 && $score > 79){
        $color = 'blue';
    } elseif( $score > 89){
        $color = 'green';
    }

    return $color;
}

function get_industry($tag){
    $industry = '';

    switch ($tag) {
        case 'oilagas':
            $industry = 'Oil & Gas';
            break;
        case 'cops':
            $industry = 'Cops';
            break;
        case 'realestate':
            $industry = 'Real Estate';
            break;
        case 'healthinsurance':
            $industry = 'Health Insurance';
            break;
        }

    return $industry;
}

//Get Rep Scores
function get_score_yoast() {

    $score = 'na';
    $scores = get_field('progressive_voting_by_member'); 
    $current_year = date('Y');
    
    if($scores): 
        foreach($scores as $scoreRow):
            if($scoreRow['na'] == 1){$score = 'na';} else {$score = $scoreRow['score'];}
            $vote_info[] = array( 'year' => $scoreRow['years'] , 'score' => $score);
        endforeach;
    endif;
    
    $score = $vote_info[0]['score']; 

    return $score;
}


// define the action for register yoast_variable replacments
function register_custom_yoast_variables() {
    wpseo_register_var_replacement( '%%score%%', __NAMESPACE__ . '\get_score_yoast', 'advanced' );
}

// Add action
add_action('wpseo_register_extra_replacements', __NAMESPACE__ . '\register_custom_yoast_variables');


// Language List
function languages_list() {
    $list = '';
    if (function_exists('icl_get_languages')) {
        $languages = icl_get_languages('skip_missing=0&orderby=code');
        if(1 < count($languages)){
            $list .=  '<ul class="language-switcher">';
            foreach($languages as $l){
                if($l['active']) {
                    $list .=  '<li class="active">';
                } else {
                    $list .=  '<li>';
                }
                    $list .=  '<a href="'.$l['url'].'">';
                    $list .=  icl_disp_language($l['language_code']);
                    $list .=  '</a>';
                    $list .=  '</li>';
            }
            $list .= '</ul>';
        }
    }
    return $list;
}