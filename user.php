<?php
require_once dirname(__FILE__) . '/db_connect.php';
require_once dirname(__FILE__) . '/lib/user_functions.php';
require_once dirname(__FILE__) . '/JSONResponseHandler.php';

//connect to the database
$db = new DB_CONNECT(); 
$json = new JSONResponseHandler();
$user = new USER();
//json response array
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['fb_fk']) //fb link of the user 
        && isset($_POST['phone']) //phone number of the user
        && isset($_POST['name'])) { //Name of the user
        
        $fb_fk = doubleval($_POST['fb_fk']);
        $name = $_POST['name'];
        $phone = $_POST['phone'];       

        $user->create($fb_fk, $name, $phone);
    
    } else {
        $json->json_response_error("required field(s) missing");
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['fb_fk'])) { //The user who's data is being extracted
    
        $fb_fk = doubleval($_GET['fb_fk']);

        $user->get($fb_fk);

    } else {
        $json->json_response_error("required field(s) missing");
    }

} else {
    $json->json_response_success("Request Failed: REQUEST_METHOD not recognized");
}

?>