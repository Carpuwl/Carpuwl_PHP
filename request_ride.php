<?php

//json response array
$respone = array();

if (isset($_POST['user_pk'])
    && isset($_POST['event_pk'])) {
    
    $user_pk = $_POST['user_pk'];
    $event_pk = $_POST['event_pk'];
    
    require_once dirname(__FILE__) . '/db_connect.php';

    $db = new DB_CONNECT();
    
    $result = mysql_query("INSERT INTO user_event_status (user_fk, event_fk, is_driver, status) 
        VALUES ('$user_pk', $event_pk, 0, 'REQUESTED');");
    if ($result) {
        $response['success'] = 1;
        $response['message'] = "User event status successfully created";
        echo json_encode($response);
    } else {
        $response['success'] = 0;
        $response['message'] = "Error occurred creating a event";
        echo json_encode($response);
    }

} else {
    $response['success'] = 0;
    $response['message'] = "required field(s) missing";

    echo json_encode($response);
}
 

?>