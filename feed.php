<?php
require_once dirname(__FILE__) . '/lib/db_connect.php';
require_once dirname(__FILE__) . '/lib/JSONResponseHandler.php';

$sql_conditions = array();
$types = "";
$params = array();
if (isset($_GET['start_point'])) {
    array_push($sql_conditions, "e.start_point = ?");
    array_push($params, $_GET['start_point']);
    $types .= 's';
}
if (isset($_GET['end_point'])) {
    array_push($sql_conditions, "e.end_point = ?");
    array_push($params, $_GET['end_point']);
    $types .= 's';
}
if (isset($_GET['price'])) {
    array_push($sql_conditions, "e.price <= ?");
    array_push($params, $_GET['price']);
    $types .= 'd';
}
if (isset($_GET['seats_rem'])) {
    array_push($sql_conditions, "e.seats_rem >= ?");
    array_push($params, $_GET['seats_rem']);
    $types .= 'i';
}
if (isset($_GET['depart_date'])) {
    array_push($sql_conditions, "e.depart_date >= ?");
    array_push($params, $_GET['depart_date']);
    $types .= 'd';
}
if (isset($_GET['eta'])) {
    array_push($sql_conditions, "e.eta <= ?");
    array_push($params, $_GET['eta']);
    $types .= 'd';
}

$sql = "";
if (!empty($sql_conditions)) {
    foreach ($sql_conditions as $condition) {
        $sql .= " AND {$condition}";
    }
}

$json = new JSONResponseHandler(); //JSON response to the app
$db = DB_CONNECT::connect(); //Establish database connection


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

if (!empty($params)) {
    $a_params = array();
    array_push($a_params, $types);
    foreach ($params as $param) {
        array_push($a_params, $param);
    }

    $refs = array();
    foreach($a_params as $key => $value) {
        $refs[$key] = &$a_params[$key];
    }

    call_user_func_array(array($stmt, 'bind_param'), $refs);
}

if ($stmt->execute()) {
    $stmt->store_result();

    $meta = $stmt->result_metadata(); 
    while ($field = $meta->fetch_field()) 
    {   
        $params[] = &$row[$field->name]; 
    } 

    call_user_func_array(array($stmt, 'bind_result'), $params); 

    while ($stmt->fetch()) 
    { 
        foreach($row as $key => $val) 
        { 
            $c[$key] = $val; 
        }

        $result[] = $c; 
    }

    $response['feed'] = $result;
    $json->json_response_success(
        "Event successfully retrieved!",
        $response);

} else {
     $json->json_response_error("Error getting event - A database error occurred");
}

$stmt->close();