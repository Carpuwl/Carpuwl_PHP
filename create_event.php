<?php

//json response array
$respone = array();

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

    require_once dirname(__FILE__) . '/db_connect.php';

    $db = new DB_CONNECT();

    mysql_query("SET AUTOCOMMIT=0");
    mysql_query("START TRANSACTION");

    $a1 = mysql_query("INSERT INTO events (start_point, end_point, price, seats_rem, depart_date, eta, fb_fk, description) 
        VALUES ('$start_point', 
                '$end_point', 
                $price, 
                $seats_rem, 
                $depart_date,
                $eta,
                $fb_fk, 
                '$description');");
    $event_fk = mysql_insert_id();
    $a2 = mysql_query("INSERT INTO user_event_status (fb_fk, event_fk, is_driver, status)
        VALUES ($fb_fk, $event_fk, 1, 'PENDING');");

    $result = $a1 and $a2;
    if ($result) {
        mysql_query("COMMIT");
    } else {
        mysql_query("ROLLBACK");
    }
    
    if ($result) {
        $response['event_fk'] = $event_fk;
        $response['success'] = 1;
        $response['message'] = "Event successfully created";
        echo json_encode($response);
    } else {
        $response['success'] = 0;
        $response['message'] = "Error occurred creating event";
        echo json_encode($response);
    }

} else {
    $response['success'] = 0;
    $response['message'] = "required field(s) missing";

    echo json_encode($response);
}


?>