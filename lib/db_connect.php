<?php
//import db config file
require_once dirname(__FILE__) . '/db_config.php';

class DB_CONNECT {

    public static function connect() {
        try {
            return new PDO(DSN, DB_USER, DB_PASSWORD);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

}
