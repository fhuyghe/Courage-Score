<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class PageAllStars extends Composer
{

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'page-all-stars',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'allStars' => get_field('people_list'),
            'honorableMentions' => get_field('honorable_mentions'),
        ];
    }
}
