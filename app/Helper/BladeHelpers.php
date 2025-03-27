<?php 
namespace App\Helper\BladeHelpers;
    
    
    if(!function_exists('generateLightColor'))
    { 
        function generateLightColor() {
            $r = rand(150, 255);
            $g = rand(150, 255);
            $b = rand(150, 255);
            return sprintf("#%02X%02X%02X", $r, $g, $b);
        }
    }

