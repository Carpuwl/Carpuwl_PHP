<?php
require_once dirname(__FILE__) . '/lib/db_connect.php';
require_once dirname(__FILE__) . '/lib/JSONResponseHandler.php';

$json = new JSONResponseHandler();

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (isset($_POST['fb_fk'],
    $_POST['event_pk'])) {

        $user_pk = $_POST['fb_fk'];
        $event_pk = $_POST['event_pk'];
        $is_driver = false;
        $status = "REQUESTED";

        $db = DB_CONNECT::connect();

        $stmt = $db->prepare("SELECT *
                              FROM user_event_status
                              WHERE fb_fk = ?
                              AND event_fk = ?;");
        $stmt->bind_param('dd', $user_pk, $event_pk);
        $stmt->execute();

        $response['is_requested'] = 0;
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $response['is_requested'] = 1;
            $json->json_response_success("Event status already created!", $response);
        } else {
            $stmt = $db->prepare("INSERT INTO user_event_status (fb_fk, event_fk, is_driver, status)
                              VALUES (?,?,?,?);");
            if ($stmt) {
                $stmt->bind_param('sdis', $user_pk, $event_pk, $is_driver, $status);
                if ($stmt->execute()) {
                    $json->json_response_success("Request successful", $response);
                } else {
                    $json->json_response_error("Request Failed");
                }
            } else {
                $json->json_response_error("Error: Mysqli stmt could not be created!");
            }
        }

        $stmt->close();
        $db->close();

    } else {
        $json->json_response_error("Required field(s) missing!");
    }
} else {
    $json->json_response_error("Request Failed: REQUEST_METHOD not recognized");
}