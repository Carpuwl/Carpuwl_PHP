<?php
require_once dirname(__FILE__) . '/lib/user_functions.php';
require_once dirname(__FILE__) . '/lib/db_connect.php';
require_once dirname(__FILE__) . '/lib/JSONResponseHandler.php';

$json = new JSONResponseHandler();
$db = DB_CONNECT::connect();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['fb_fk'], //fb link of the user
        $_POST['phone'], //phone number of the user
        $_POST['name'])) { //Name of the user
        
        $fb_fk = $_POST['fb_fk'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];       

        $user->create($fb_fk, $name, $phone);
    
    } else {
        $json->json_response_error("Required field(s) missing");
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['fb_fk'])) { //The user who's data is being extracted
    
        $fb_fk = $_GET['fb_fk'];

        $user->get($fb_fk);

    } else {
        $json->json_response_error("Required field(s) missing");
    }

} else {
    $json->json_response_error("Request Failed: REQUEST_METHOD not recognized");
}

$db->close();