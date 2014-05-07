<?php

//connect to the database
require_once dirname(__FILE__) . '/lib/db_config.php';
require_once dirname(__FILE__) . '/lib/db_connect.php';
require_once dirname(__FILE__) . '/lib/user_functions.php';
require_once dirname(__FILE__) . '/lib/event_functions.php';

$dsn = 'mysql:dbname=test;host=localhost';

try {
    $db = new PDO($dsn, 'root', '');
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$stmt = $db->prepare('SELECT * FROM user WHERE name = ?;');
$params = array('Christopher Ciufo');
/*$stmt->bindParam('s', $name);*/
$stmt->execute($params);
$result = $stmt->fetchObject();
print_r($result);
$stmt->closeCursor();
unset($db);


/*$db = DB_CONNECT::connect();
$stmt = $db->prepare("SELECT name FROM user;");
$stmt->execute();
$stmt->bind_result($name);
$stmt->fetch();
print_r($name);*/
