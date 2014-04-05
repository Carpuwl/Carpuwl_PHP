<?php

//json response array
$respone = array();

if (isset($_POST['user_pk']) //the passenger's user_pk
    && isset($_POST['event_pk']) //the event_pk of the ride the passenger is requesting
    && isset($_POST['choice'])) { //The decision of the driver

    $user_pk = $_POST['user_pk'];
    $event_pk = $_POST['event_pk'];
    $choice = $_POST['choice'];

    if ($choice == 1) {
        $status = "ACCEPTED";
    } else {
        $status = "DECLINED";
    }

    require_once dirname(__FILE__) . '/db_connect.php';
    
    $db = new DB_CONNECT();
    
    $result = mysql_query("UPDATE user_event_status SET status = '$status' WHERE 
        user_fk = $user_pk AND event_fk = $event_pk AND is_driver = 0;");

    if ($result) {

        $response['success'] = 1;
        $response['message'] = "Status successfully updated";
        echo json_encode($response);

    } else {

        $response['success'] = 0;
        $response['message'] = "Error updating status";
        echo json_encode($response);
    }

} else {
    $response['success'] = 0;
    $response['message'] = "required field(s) missing";

    echo json_encode($response);
}


?>