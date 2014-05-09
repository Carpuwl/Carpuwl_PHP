<?php
require_once dirname(__FILE__) . '/lib/db_connect.php';
require_once dirname(__FILE__) . '/lib/JSONResponseHandler.php';

$sql_conditions = array();
$params = array();
if ($_SERVER['REQUEST_METHOD'] === "GET") {

    if (isset($_GET['start_point'])) {
        array_push($sql_conditions, "e.start_point = ?");
        array_push($params, $_GET['start_point']);
    }
    if (isset($_GET['end_point'])) {
        array_push($sql_conditions, "e.end_point = ?");
        array_push($params, $_GET['end_point']);
    }
    if (isset($_GET['price'])) {
        array_push($sql_conditions, "e.price <= ?");
        array_push($params, $_GET['price']);
    }
    if (isset($_GET['seats_rem'])) {
        array_push($sql_conditions, "e.seats_rem >= ?");
        array_push($params, $_GET['seats_rem']);
    }
    if (isset($_GET['depart_date'])) {
        array_push($sql_conditions, "e.depart_date >= ?");
        array_push($params, $_GET['depart_date']);
    }
    if (isset($_GET['eta'])) {
        array_push($sql_conditions, "e.eta <= ?");
        array_push($params, $_GET['eta']);
    }

    $sql = "";
    if (!empty($sql_conditions)) {
        foreach ($sql_conditions as $condition) {
            $sql .= " AND {$condition}";
        }
    }

    $json = new JSONResponseHandler(); //JSON response to the app
    $db = DB_CONNECT::connect();

    $time_now = new DateTime();
    $time_now = $time_now->getTimestamp() * 1000;

    $stmt = $db->prepare("
        SELECT *
        FROM user u
        INNER JOIN events e
        ON u.fb_fk = e.fb_fk
        WHERE e.depart_date >= $time_now
        {$sql}
        ORDER BY e.depart_date ASC LIMIT 30;");

    if ($stmt) {

        if ($stmt->execute($params)) {

            if ($stmt->rowCount() > 0) {
                $feed = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
                $json->json_response_success("Event successfully retrieved!", $response);

            } else {
                $json->json_response_error("Error getting event! - No results matching filter");
            }

        } else {
            $json->json_response_error("Error getting event - A database error occurred");
        }

    } else {
        $json->json_response_error("Error: PDO stmt could not be created!");
    }

    $stmt->closeCursor();
    unset($db);

} else {
    $json->json_response_error("Request Failed: REQUEST_METHOD not recognized");
}
