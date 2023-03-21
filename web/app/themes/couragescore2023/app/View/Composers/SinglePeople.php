<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class SinglePeople extends Composer
{

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'single',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'senateOrAssembly' => get_field('senate_or_assembly'),
            'district' => get_field('district'),
            'party' => get_field('party'),
            'scores' => get_field('progressive_voting_by_member'),
            'partnersScores' => get_field('partners_scores'),
            'contributions' => get_field('corporate_contributions_to_legislator'),
            'titles' => get_field('single_representatives', 'option'),
            'additionalText' => get_field('additional_text'),
        ];
    }

   
}
