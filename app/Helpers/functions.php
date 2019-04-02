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
