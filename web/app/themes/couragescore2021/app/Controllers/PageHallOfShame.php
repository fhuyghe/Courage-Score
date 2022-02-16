<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class PageHallOfShame extends Controller
{
    public function data() {
        $data = [];

        $data['hallOfShame'] = get_field('people_list');
        $data['dishonorable_mentions'] = get_field('dishonorable_mentions');

	    return $data;
    }
}
