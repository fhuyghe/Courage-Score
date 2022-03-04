<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class FrontPage extends Controller
{

    public function data(){
        $data = [];

        $data['hall_of_shame'] = get_field('hall_of_shame');
        $data['all_stars'] = get_field('all_stars');
        $data['top'] = get_field('top');
        $data['custom_scorecard_banner'] = get_field('custom_scorecard_banner');

        return $data;
    }
    
}
