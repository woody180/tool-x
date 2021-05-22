<?php

// Url segments

function urlSegments($index = null) {
    
    $url = explode(URLROOT, CURRENT_URL)[1];
    
    if ($index) return explode('/', $url)[$index] ?? null;
 

    preg_match('/[\/](.*)/', $url, $match);
    return $match[1];
}


function baseUrl(string $url = null) {

    if ($url)
        return URLROOT . '/' . $url;
    else
        return URLROOT;
}


function assetsUrl(string $url = null) {

    $publicUrl = $url ? '/' . $url : '';
    return PUBLIC_DIR . $publicUrl;
}