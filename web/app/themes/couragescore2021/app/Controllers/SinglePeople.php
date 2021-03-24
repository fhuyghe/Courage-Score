<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class SinglePeople extends Controller
{
    public function data() {
        $data = [];

        $data['senate_or_assembly'] = get_field('senate_or_assembly');
        $data['district'] = get_field('district');
        $data['bills'] = get_field('voting');

	    return $data;
    }
}
