<?php
require_once dirname(__FILE__) . '/lib/db_connect.php';
require_once dirname(__FILE__) . '/lib/JSONResponseHandler.php';

//json response array
$respone = array();

if (isset($_POST['fb_fk'])
    && isset($_POST['rating'])) {
    
    $user_pk = $_POST['fb_fk'];
    $newRating = $_POST['rating'];
    
    $db = DB_CONNECT::connect();
    
    $stmt = $db->prepare("SELECT rating, num_ratings FROM user WHERE fb_fk = ?;");
    $stmt->bind_param('d', $fb_fk);
    if ($stmt->execute()) {

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $currRating = $row['rating'];
            $curr_num_ratings = $row['num_ratings'];
            $new_num_ratings = $curr_num_ratings + 1;
            
            if ($curr_num_ratings == 0) {
                $result = mysql_query("UPDATE user SET rating = $newRating, num_ratings = $new_num_ratings WHERE fb_fk = $fb_fk;");
            } else {
                $rating = $currRating * ($curr_num_ratings/$new_num_ratings) + $newRating * (1/$new_num_ratings);
                $result = mysql_query("UPDATE user SET rating = $rating, num_ratings = $new_num_ratings WHERE fb_fk = $fb_fk;");
            }

            if ($result) {
                $response['success'] = 1;
                $response['message'] = "Rating successfully updated";
                echo json_encode($response);
            } else {
                $response['success'] = 0;
                $response['message'] = "Error updating rating";
                echo json_encode($response);
            }

        } else {
            $response['success'] = 0;
            $response['message'] = "Error updating rating";
            echo json_encode($response);
        }

    } else {
        $response['success'] = 0;
        $response['message'] = "Error updating rating";
        echo json_encode($response);
    }

} else {
    $response['success'] = 0;
    $response['message'] = "required field(s) missing";

    echo json_encode($response);
}