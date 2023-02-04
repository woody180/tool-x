<?php

namespace App\Engine\Libraries;

class LanguageLogic {
    
    public static function start()
    {
        $langList = \App\Engine\Libraries\Languages::list();
        $langCodeList = array_map(function($item) {
            return $item->code;
        }, $langList);

        if (isset($_SESSION['lang'])) {
            if (!is_null(urlSegments('first')) && in_array(urlSegments('first'), $langCodeList))
                \App\Engine\Libraries\Languages::switch(urlSegments('first'));

            if (!is_null(urlSegments(2)) && !in_array(urlSegments('first'), $langCodeList)) return abort();

            if (!is_null(urlSegments('first')) && !in_array(urlSegments('first'), $langCodeList)) return abort();
        }
    }
}