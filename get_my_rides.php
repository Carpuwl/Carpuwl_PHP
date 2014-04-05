<?php

//json response array
$respone = array();

    $user_fk = 1;

    require_once dirname(__FILE__) . '/db_connect.php';

    $db = new DB_CONNECT();
    
    $result = mysql_query("SELECT * 
                            FROM user_event_status u_e_status
                            RIGHT JOIN events e ON u_e_status.event_fk = e.event_pk
                            WHERE u_e_status.user_fk = $user_fk AND is_driver = 0;");
    if (!empty($result)) {
        $num_rows = mysql_num_rows($result);

        if ($num_rows > 0) {
            $feed = array();
            while ($row = mysql_fetch_array($result)) {
                $user = array (
                    'name' => $row['name'],
                    'rating' => $row['rating'],
                    'phone' => $row['phone']
                );

                $event = array (
                    'event_pk' => $row['event_pk'],
                    'start_point' => $row['start_point'],
                    'end_point' => $row['end_point'],
                    'price' => $row['price'],
                    'seats_rem' => $row['seats_rem'],
                    'depart_date' => $row['depart_date'],
                    'eta' => $row['eta'],
                    'user_fk' => $row['user_fk'],
                    'description' => $row['description']
                );

                array_push($feed, $user + $event);
            }

            $response['feed'] = $feed;
            $response['success'] = 1;
            $response['message'] = "My rides successfully retrieved";
            echo json_encode($response);

        } else {
            $response['success'] = 0;
            $response['message'] = "Error getting my rides";
            echo json_encode($response);
        }

    } else {
        $response['success'] = 0;
        $response['message'] = "Error getting my rides!";
        echo json_encode($response);
    }

?>