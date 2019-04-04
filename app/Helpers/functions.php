<?php

if (!function_exists('getSubPath')) {
    function getSubPath($id) {
        $prefBase = intval($id / 2000);
        $pref1 = intval($prefBase / 2000);
        $pref2 = $prefBase % 2000;
        return $pref1 . "/" .  $pref2 . "/" . $id;
    }
}

if (!function_exists('getResourceUrl')) {
    function getResourceUrl($id, $type, $path) {
        if (!$path || is_null($path)) return '';
        return url('/res/' . $type . '/' . $id . '/' . $path);
    }
}


if (!function_exists('getArrayVar')) {
    function getArrayVar($array, $key, $default = '') {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}

if (!function_exists('hex2rgb')) {
    function hex2rgb($hex, $output = 0, $opacity = false) {
       $hex = str_replace("#", "", $hex);

       if(strlen($hex) == 3) {
          $r = hexdec(substr($hex,0,1).substr($hex,0,1));
          $g = hexdec(substr($hex,1,1).substr($hex,1,1));
          $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       } else {
          $r = hexdec(substr($hex,0,2));
          $g = hexdec(substr($hex,2,2));
          $b = hexdec(substr($hex,4,2));
       }

       if ($output == 1) {
        $rgb = array('r' => $r, 'g' => $g, 'b' => $b);
        if (!($opacity === false)) $rgb['a'] = (($opacity < 1) ? $opacity : $opacity/100);
       } else {
        $rgb = array($r, $g, $b); 
       }
       
       //return implode(",", $rgb); // returns the rgb values separated by commas
       return $rgb; // returns an array with the rgb values
    }
}
