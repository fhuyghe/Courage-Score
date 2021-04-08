<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class FrontPage extends Controller
{

    public function data(){
        $data = [];

        $data['hall_of_shame'] = get_field('hall_of_shame');
        $data['all_stars'] = get_field('all_stars');
        $data['honorable_mentions'] = get_field('honorable_mentions');
        $data['top'] = get_field('top');

        return $data;
    }
    
}
