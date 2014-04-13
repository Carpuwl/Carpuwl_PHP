<?php

$params = array();

if (isset($_GET['start_point'])) {
    array_push($params, "e.start_point = '{$_GET['start_point']}'");
} else if (isset($_GET['end_point'])) {
    array_push($params, "e.end_point = '{$_GET['end_point']}'");
} else if (isset($_GET['price'])) {
    array_push($params, "e.price <= {$_GET['price']}");
} else if (isset($_GET['seats_rem'])) {
    array_push($params, "e.seats_rem >= {$_GET['seats_rem']}");
} else if (isset($_GET['depart_date'])) {
    array_push($params, "e.depart_date >= {$_GET['depart_date']}");
} else if (isset($_GET['eta'])) {
    array_push($params, "e.eta <= {$_GET['eta']}");
}

$sql = "";
if (!empty($params)) {
    $sql = " WHERE {$params[0]}";
    for ($i = 1; $i < count($params); $i++) {
        $sql .= " AND {$params[$i]}";
    }
}

require_once dirname(__FILE__) . '/db_connect.php';
require_once dirname(__FILE__) . '/JSONResponseHandler.php';

$respone = array(); //json response array
$db = new DB_CONNECT(); //Establish database connection
$json = new JSONResponseHandler(); //JSON response to the app

//Query to grab 25 events
$result = mysql_query("SELECT * 
                        FROM user u  
                        INNER JOIN events e 
                        ON u.fb_fk = e.fb_fk" 
                        . $sql . 
                        " ORDER BY 1 DESC LIMIT 25;"); 
if (!empty($result)) {
    $num_rows = mysql_num_rows($result);

    if ($num_rows > 0) {
        $feed = array();
        while ($row = mysql_fetch_array($result)) {
            $user = array (
                'name' => $row['name'],
                'rating' => $row['rating'],
                'num_ratings' => $row['num_ratings'],
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
                'fb_fk' => $row['fb_fk'],
                'description' => $row['description']
            );

            array_push($feed, $user + $event);
        }

        $response['feed'] = $feed;
        $json->json_response_success(
            "Event successfully retrieved!",
            $response);

    } else {        
        $json->json_response_error("Error getting event!");
    }

} else {
     $json->json_response_error("Error getting event - Empty query received!");
}


?>