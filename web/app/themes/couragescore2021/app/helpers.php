<?php

namespace App;

use Roots\Sage\Container;

/**
 * Get the sage container.
 *
 * @param string $abstract
 * @param array  $parameters
 * @param Container $container
 * @return Container|mixed
 */
function sage($abstract = null, $parameters = [], Container $container = null)
{
    $container = $container ?: Container::getInstance();
    if (!$abstract) {
        return $container;
    }
    return $container->bound($abstract)
        ? $container->makeWith($abstract, $parameters)
        : $container->makeWith("sage.{$abstract}", $parameters);
}

/**
 * Get / set the specified configuration value.
 *
 * If an array is passed as the key, we will assume you want to set an array of values.
 *
 * @param array|string $key
 * @param mixed $default
 * @return mixed|\Roots\Sage\Config
 * @copyright Taylor Otwell
 * @link https://github.com/laravel/framework/blob/c0970285/src/Illuminate/Foundation/helpers.php#L254-L265
 */
function config($key = null, $default = null)
{
    if (is_null($key)) {
        return sage('config');
    }
    if (is_array($key)) {
        return sage('config')->set($key);
    }
    return sage('config')->get($key, $default);
}

/**
 * @param string $file
 * @param array $data
 * @return string
 */
function template($file, $data = [])
{
    return sage('blade')->render($file, $data);
}

/**
 * Retrieve path to a compiled blade view
 * @param $file
 * @param array $data
 * @return string
 */
function template_path($file, $data = [])
{
    return sage('blade')->compiledPath($file, $data);
}

/**
 * @param $asset
 * @return string
 */
function asset_path($asset)
{
    return sage('assets')->getUri($asset);
}

/**
 * @param string|string[] $templates Possible template files
 * @return array
 */
function filter_templates($templates)
{
    $paths = apply_filters('sage/filter_templates/paths', [
        'views',
        'resources/views'
    ]);
    $paths_pattern = "#^(" . implode('|', $paths) . ")/#";

    return collect($templates)
        ->map(function ($template) use ($paths_pattern) {
            /** Remove .blade.php/.blade/.php from template names */
            $template = preg_replace('#\.(blade\.?)?(php)?$#', '', ltrim($template));

            /** Remove partial $paths from the beginning of template names */
            if (strpos($template, '/')) {
                $template = preg_replace($paths_pattern, '', $template);
            }

            return $template;
        })
        ->flatMap(function ($template) use ($paths) {
            return collect($paths)
                ->flatMap(function ($path) use ($template) {
                    return [
                        "{$path}/{$template}.blade.php",
                        "{$path}/{$template}.php",
                    ];
                })
                ->concat([
                    "{$template}.blade.php",
                    "{$template}.php",
                ]);
        })
        ->filter()
        ->unique()
        ->all();
}

/**
 * @param string|string[] $templates Relative path to possible template files
 * @return string Location of the template
 */
function locate_template($templates)
{
    return \locate_template(filter_templates($templates));
}

/**
 * Determine whether to show the sidebar
 * @return bool
 */
function display_sidebar()
{
    static $display;
    isset($display) || $display = apply_filters('sage/display_sidebar', false);
    return $display;
}


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
        $color = 'green';
    } elseif( $score > 89){
        $color = 'blue';
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


