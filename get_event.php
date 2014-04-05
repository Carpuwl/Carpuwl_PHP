<?php

//json response array
$respone = array();

if (isset($_GET['event_fk'])) { //The event pk that is being retrieved
    
    $event_fk = $_GET['event_fk'];

    require_once dirname(__FILE__) . '/db_connect.php';

    $db = new DB_CONNECT();
    
    $result = mysql_query("SELECT * FROM events e
                            LEFT JOIN user u 
                            ON e.fb_fk = u.fb_fk
                            WHERE event_pk = $event_fk;");
    
    if (!empty($result)) {
        
        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);

            $user = array (
                'name' => $row['name'],
                'rating' => $row['rating'],
                'num_ratings' => $row['num_ratings'],
                'phone' => $row['phone']
            );

            $event = array (
                'start_point' => $row['start_point'],
                'end_point' => $row['end_point'],
                'price' => $row['price'],
                'seats_rem' => $row['seats_rem'],
                'depart_date' => $row['depart_date'],
                'eta' => $row['eta'],
                'fb_fk' => $row['fb_fk'],
                'description' => $row['description']
            );

            $response['success'] = 1;
            $response['user'] = $user;
            $response['event'] = $event;
            $response['message'] = "Event successfully retrieved!";
            echo json_encode($response);

        } else {
            $response['success'] = 0;
            $response['message'] = "Error getting event!";
            echo json_encode($response);
        }

    } else {
        $response['success'] = 0;
        $response['message'] = "Error getting event!";
        echo json_encode($response);
    }

} else {
    $response['success'] = 0;
    $response['message'] = "required field(s) missing";

    echo json_encode($response);
}


?>