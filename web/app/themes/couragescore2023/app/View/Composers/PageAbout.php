<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class PageAbout extends Composer
{

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'page-about',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'logos' => get_field('logos')
        ];
    }
}
