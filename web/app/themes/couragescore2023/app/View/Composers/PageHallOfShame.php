<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class PageHallOfShame extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'page-hall-of-shame',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'hallOfShame' => get_field('people_list'),
            'dishonorableMentions' => get_field('dishonorable_mentions'),
        ];
    }
}
