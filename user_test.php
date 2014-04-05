<?php

require_once dirname(__FILE__) . '/get_user.php';
require_once dirname(__FILE__) . '/create_user.php';
require_once dirname(__FILE__) . '/db_connect.php';

//connect to the database
$db = new DB_CONNECT(); 

$fb_fk = 691975324;
GET_USER::get($fb_fk);
CREATE_USER::create(21, "test", "1");

?>