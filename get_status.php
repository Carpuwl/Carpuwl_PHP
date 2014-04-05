<?php

//json response array
$respone = array();

if (isset($_GET['user_pk']) //The user_pk of the current user of the app
    && isset($_GET['event_pk'])) { //The event_pk that is currently being viewed 
    
    $user_pk = $_GET['user_pk'];
    $event_pk = $_GET['even_pk'];

    require_once dirname(__FILE__) . '/db_connect.php';
    

    $db = new DB_CONNECT();
    
    $result = mysql_query("SELECT status FROM user_event_status 
        WHERE user_fk = $user_pk 
        AND event_fk = $event_pk;");
    if (!empty($result)) {
        
        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);

            $response['success'] = 1;
            $response['status'] = $row['status'];
            $response['message'] = "Status successfully retrieved";
            echo json_encode($response);

        } else {
            $response['success'] = 0;
            $response['message'] = "Error getting status";
            echo json_encode($response);
        }

    } else {
        $response['success'] = 1;
        $response['status'] = "NOT REQUESTED";
        $response['message'] = "Status successfully retrieved";
        echo json_encode($response);
    }

} else {
    $response['success'] = 0;
    $response['message'] = "required field(s) missing";

    echo json_encode($response);
}


?>