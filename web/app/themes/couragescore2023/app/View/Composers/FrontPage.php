<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class FrontPage extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'front-page',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'hallOfShame' => get_field('hall_of_shame'),
            'allStars' => get_field('all_stars'),
            'top' => get_field('top'),
            'scorecardBanner' => get_field('custom_scorecard_banner')
        ];
    }
    
}
