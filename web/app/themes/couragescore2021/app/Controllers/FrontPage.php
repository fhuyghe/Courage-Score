<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class FrontPage extends Controller
{
    public static function exampleFunction(){   
        $name = array(
            'a' => 1,
            'b' => 2,
            'c' => 3
        );   
        
        return $name;      
    }   
}
