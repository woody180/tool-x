<?php

use App\Engine\Libraries\Library;

function csrf_field() {
    if (isset($_SESSION['csrf_token']))
        return "<input type=\"hidden\" name=\"csrf_token\" value=\"".$_SESSION['csrf_token']."\" />";
    else
        return '<p style="color: red;">CSRF PROTECTION IS OFF!!!</p>';
}

function csrf_hash() {
    if (isset($_SESSION['csrf_token']))
        return $_SESSION['csrf_token'];
    else
        return 0;
}

// Forms
function getForm(string $val) {
        
    if (Library::hasFlashData('form'))
        return Library::getFlashData('form')->{$val};
    else 
        return null;
}

function setForm(array $data) {
    
    Library::setFlashData('form', $data);
}


// Method spoofing
function setMethod(string $method) {
    return '<input name="_method" type="hidden" value="'.$method.'" />';
}