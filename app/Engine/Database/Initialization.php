<?php
use \R as R;

function initModel(string $modelName) {

    if (DATABASE) {

        require_once APPROOT . '/Engine/Libraries/rb.php';
        require_once APPROOT . '/Engine/Database/Connection.php';

        if (!is_array($modelName) && file_exists(APPROOT . "/Models/Model_$modelName.php")) {
            require_once APPROOT . "/Models/Model_$modelName.php";

            new \App\Engine\Database\Connection();

            return R::dispense($modelName);
        }
    }

    return false;
}