<?php

//json response array
$respone = array();

if (isset($_POST['user_pk']) //the passenger's user_pk
    && isset($_POST['event_pk']) //the event_pk of the ride the passenger is requesting
    && isset($_POST['choice'])) { //The decision of the driver

    $driver_fk = $_POST['user_fk'];
    $passenger_pk = $_POST['user_pk'];
    $event_pk = $_POST['event_pk'];
    $choice = $_POST['choice'];

    if ($choice == 1) {
        $status = "CONFIRMED";
    } 
    else if ($choice == 2) {
        $status = "ACCEPTED";
    } 
    else {
        $status = "DECLINED";
    }

    require_once dirname(__FILE__) . '/db_connect.php';
    
    $db = new DB_CONNECT();
    
    mysql_query("SET AUTOCOMMIT=0");
    mysql_query("START TRANSACTION");

    $a1 = mysql_query("UPDATE user_event_status SET status = '$status' 
                            WHERE user_fk = $passenger_pk
                            AND event_fk = $event_pk AND is_driver = 0;");

    if ($status == "CONFIRMED") {
        $result = mysql_query("SELECT seats_rem FROM events WHERE event_pk = $event_pk;");
        $row = mysql_fetch_array($result);

        if($row['seats_rem'] > 0){
            echo "has seats";
            $new_num_seats = $row['seats_rem'] - 1;
            $a2 = mysql_query("UPDATE events SET seats_rem = $new_num_seats 
                            WHERE event_pk = $event_pk;");
        }
    }

    if ($a1) {
        mysql_query("COMMIT");
    } else {        
        mysql_query("ROLLBACK");
    }

    $result = $a1 and $a2;
    if ($result) {

        $response['success'] = 1;
        $response['message'] = "Status successfully updated";
        echo json_encode($response);

    } else {

        $response['success'] = 0;
        $response['message'] = "Error updating status";
        echo json_encode($response);
    }

} else 
    $response['success'] = 0;
    $response['message'] = "required field(s) missing";

    echo json_encode($response);
}


?>