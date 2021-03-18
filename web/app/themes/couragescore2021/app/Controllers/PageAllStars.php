<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class PageAllStars extends Controller
{
    public function data() {
        $data = [];

        $data['allStars'] = get_field('people_list');

	    return $data;
    }
}
