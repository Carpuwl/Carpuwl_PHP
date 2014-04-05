<?php

//json response array
$respone = array();

if (isset($_POST['user_pk'])
    && isset($_POST['rating'])) {
    
    $user_pk = $_POST['user_pk'];
    $newRating = $_POST['rating'];

    require_once dirname(__FILE__) . '/db_connect.php';
    
    $db = new DB_CONNECT();
    
    $result = mysql_query("SELECT rating, num_ratings FROM user WHERE user_pk = $user_pk;");
    if (!empty($result)) {
        
        if (mysql_num_rows($result) > 0) {
            $result = mysql_fetch_array($result);

            $currRating = $result['rating'];
            $curr_num_ratings = $result['num_ratings'];
            $new_num_ratings = $curr_num_ratings + 1;
            
            if ($curr_num_ratings == 0) {
                $result = mysql_query("UPDATE user SET rating = $newRating, num_ratings = $new_num_ratings WHERE user_pk = $user_pk;");
            } else {
                $rating = $currRating * ($curr_num_ratings/$new_num_ratings) + $newRating * (1/$new_num_ratings);
                $result = mysql_query("UPDATE user SET rating = $rating, num_ratings = $new_num_ratings WHERE user_pk = $user_pk;");
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


?>