<?php

//connect to the database
require_once dirname(__FILE__) . '/lib/db_config.php';
require_once dirname(__FILE__) . '/lib/db_connect.php';
require_once dirname(__FILE__) . '/lib/user_functions.php';
require_once dirname(__FILE__) . '/lib/event_functions.php';

$date = new DateTime();
print_r($date->getTimestamp() * 100);
die();

$db = DB_CONNECT::connect();

$fb_fk = 100002178190500;

$event = new Event($db);
//$event->create("Narnia", "Naboo", 99.99, 3, 1234567890, 1234567890, $fb_fk, "Test");
$event->get(23);



$user = new User($db);
$user->get($fb_fk);
$user->create($fb_fk, "Christopher Ciufo", "1234567890");


