<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class PageHallOfShame extends Controller
{
    public function data() {
        $data = [];

        $data['hallOfShame'] = get_field('people_list');

	    return $data;
    }
}
