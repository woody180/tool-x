<?php namespace App\Engine\Database;

use RedBeanPHP\Facade as R;

class Connection {
    public function __construct() {
        
        if (DATABASE) {
            
            $res = null;
            if (DATABASE_TYPE === 'MySQL') {
                R::setup( 'mysql:host='.DB_HOST.';dbname='.DB_NAME.'',
                    DB_USERNAME, DB_PASSWORD ); //for both mysql or mariaDB
            }
            
            if (DATABASE_TYPE === 'PostgreSQL') {
                R::setup( 'pgsql:host='.DB_HOST.';dbname='.DB_NAME.'',
                    DB_USERNAME, DB_PASSWORD );
            }
           
            if (DATABASE_TYPE === 'SQLite') {
                R::setup( 'sqlite:' . SQLITE_PATH );
            }
           
            if (DATABASE_TYPE === 'CUBRID') {
                R::setup('cubrid:host='.DB_HOST.';port='.DB_PORT.';
                    dbname='.DB_NAME.'',
                    DB_USERNAME, DB_PASSWORD);
            }
           
            
            if (!R::testConnection()) die('Database connection failed');
        }
    }

    public function __destruct() {
        R::close();
    }
}
