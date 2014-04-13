<?php

class DB_CONNECT {

    function __construct() {
        $this->connect();
    }

    function __destruct() {
        $this->close();
    }

    public function connect() {
        //import db config file
        require_once dirname(__FILE__) . '/db_config.php';
        //connect ot mysql databse
        $db = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD) or die (mysql_error());
        //selecting databse
        mysql_select_db (DB_DATABASE, $db) or die (mysql_error());
        //return connection
        return $db;
    }

    public function close() {
        mysql_close();
    }
}
