<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class PageAbout extends Controller
{
    public function data() {
        $data = [];

        $data['logos'] = get_field('logos');

	    return $data;
    }
}
