<?php

//json response array
$respone = array();

    $user_fk = 2;

    require_once dirname(__FILE__) . '/db_connect.php';

    $db = new DB_CONNECT();
    
    $result = mysql_query("SELECT * 
                            FROM user_event_status u_e_status
                            RIGHT JOIN events e ON u_e_status.event_fk = e.event_pk
                            WHERE u_e_status.user_fk = $user_fk AND is_driver = 1;");
    if (!empty($result)) {
        $num_rows = mysql_num_rows($result);

        if ($num_rows > 0) {
            $feed = array();
            while ($row = mysql_fetch_array($result)) {
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

                $event_fk = $event['event_pk'];

                $result2 = mysql_query("SELECT * 
                                        FROM user_event_status u_e_status
                                        RIGHT JOIN user u ON u_e_status.user_fk = u.user_pk
                                        WHERE u_e_status.event_fk = $event_fk AND is_driver = 0;");

                $passengers = array();
                while ($row = mysql_fetch_array($result2)) {
                    $user = array (
                        'user_pk' => $row['user_pk'],
                        'name' => $row['name'],
                        'rating' => $row['rating'],
                        'phone' => $row['phone'],
                        'status' => $row['status']
                    );

                    array_push($passengers, $user);
                }

                array_push($feed, $passengers + $event);
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