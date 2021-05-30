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


function query(string $key = null) {
    // Query string
    preg_match_all('/[\?](.*)[\/]?+/', CURRENT_URL, $queryString);
    $queryStr = null;

    if ( isset($queryString[0]) && isset($queryString[0][0]) ) {
        parse_str($queryString[1][0], $queryArr);
        $queryStr = $queryString[0][0];
    } else {
        $queryArr = null;
    }

    if ($key) {
        return $queryArr[$key] ?? null;
    } else {
        return $queryArr;
    }

}