<?php

//connect to the database
require_once dirname(__FILE__) . '/lib/db_config.php';
require_once dirname(__FILE__) . '/lib/db_connect.php';
require_once dirname(__FILE__) . '/lib/user_functions.php';
require_once dirname(__FILE__) . '/lib/event_functions.php';

$db = DB_CONNECT::connect();
$stmt = $db->query("SELECT LAST_INSERT_ID();");
$event_pk = $stmt->fetch(PDO::FETCH_BOTH);
print_r($event_pk);

$stmt->closeCursor();
unset($db);
