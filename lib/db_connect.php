<?php

class DB_CONNECT {

    public static function connect() {
        //import db config file
        require_once dirname(__FILE__) . '/db_config.php';
        //connect ot mysql databse
        $db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die (mysql_error());
        //return connection
        return $db;
    }

}
