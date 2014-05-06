<?php
require_once dirname(__FILE__) . '/lib/event_functions.php';
require_once dirname(__FILE__) . '/lib/db_connect.php';
require_once dirname(__FILE__) . '/lib/JSONResponseHandler.php';

$json = new JSONResponseHandler();
$db = DB_CONNECT::connect();
$event = new Event($db);

$response = array();
$_GET['event_pk'] = 25;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['start_point'],
            $_POST['end_point'],
            $_POST['price'],
            $_POST['seats_rem'],
            $_POST['depart_date'],
            $_POST['eta'],
            $_POST['fb_fk'],
            $_POST['description'])) {
       
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
    if (isset($_GET['event_pk'])) {
    
        $event_pk = $_GET['event_pk'];
        $event->get($event_pk);
    
    } else {
        $json->json_response_error("Required field(s) missing");
    }

} else {
    $json->json_response_error("Request Failed: REQUEST_METHOD not recognized");
}

$db->close();