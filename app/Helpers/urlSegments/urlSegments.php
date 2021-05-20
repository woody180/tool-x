<?php

function urlSegments($index = null) {
    
    $url = explode(URLROOT, CURRENT_URL)[1];
    
    if ($index)
        return explode('/', $url)[$index] ?? null;
    else
       return $url; 
}