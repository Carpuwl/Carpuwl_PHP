<?php
//import db config file
require_once dirname(__FILE__) . '/db_config.php';

class DB_CONNECT {

    public static function connect() {
        $mysqli =  new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
        if ($mysqli->connect_error) {
            die('Connect Error (' . $mysqli->connect_errno . ') '
                . $mysqli->connect_error);
        }

        return $mysqli;
    }

}
