<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'siteName' => $this->siteName(),
            'getAllStars' => $this->getAllStars(),
            'getHallOfShame' => $this->getHallOfShame()
        ];
    }

    /**
     * Returns the site name.
     *
     * @return string
     */
    public function siteName()
    {
        return get_bloginfo('name', 'display');
    }

    public function getLegislator($body, $district){
        $args = array(
            'post_type'     => 'people',
            'post_status'        => 'publish',
            'showposts' => 1,
            'meta_query'	=> array(
                'relation'		=> 'AND',
                array(
                    'key'	 	=> 'district',
                    'value'	  	=> $district,
                    'compare' 	=> '='
                ),
                array(
                    'key'	  	=> 'senate_or_assembly',
                    'value'	  	=> $body,
                    'compare' 	=> '='
                )
            )
	    );
	    $the_query = new WP_Query( $args );
	    return $the_query->posts[0];
    }

    public function getAllStars(){
        return get_field('people_list', 3214);
    }
    
    public function getHallOfShame(){
        return get_field('people_list', 175);
    }
}
