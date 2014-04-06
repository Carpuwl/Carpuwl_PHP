<?php

require_once dirname(__FILE__) . '/db_connect.php';
require_once dirname(__FILE__) . '/lib/event_functions.php';
require_once dirname(__FILE__) . '/lib/JSONResponseHandler.php';

//connect to database
$db = new DB_CONNECT();
$json = new JSONResponseHandler();
$event = new EVENT();

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['start_point']) 
        && isset($_POST['end_point']) 
        && isset($_POST['price']) 
        && isset($_POST['seats_rem']) 
        && isset($_POST['depart_date'])
        && isset($_POST['eta'])
        && isset($_POST['fb_fk'])
        && isset($_POST['description'])) {
       
        $start_point = $_POST['start_point'];
        $end_point = $_POST['end_point'];
        $price = $_POST['price'];
        $seats_rem = $_POST['seats_rem']; 
        $depart_date = $_POST['depart_date'];
        $eta = $_POST['eta'];
        $fb_fk = $_POST['fb_fk'];
        $description = $_POST['description']; 

        $event->create(
            $start_point,
            $end_point,
            $price,
            $seats_rem,
            $depart_date,
            $eta,
            $fb_fk,
            $description);
    
    } else {
        $json->json_response_error("Required field(s) missing");
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['event_fk'])) {
    
        $event_fk = $_GET['event_fk']; 
        $event->get($event_fk);
    
    } else {
        $json->json_response_error("Required field(s) missing");
    }

} else {
    $json->json_response_error("Request Failed: REQUEST_METHOD not recognized");
}

?>