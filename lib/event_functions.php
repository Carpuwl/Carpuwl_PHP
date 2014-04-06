<?php
require_once dirname(__FILE__) . "/JSONResponseHandler.php";

/**
* Class that can be used to get and create an individual event
*/
class EVENT extends JSONResponseHandler
{    
    public function __construct() {}

    public function get ($event_fk) {

        $result = mysql_query("SELECT * FROM events e
                            LEFT JOIN user u 
                            ON e.fb_fk = u.fb_fk
                            WHERE event_pk = $event_fk;");
    
        if (!empty($result)) {

            $response = array();
            
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

                $response['user'] = $user;
                $response['event'] = $event;
                $this->json_response_success("Event successfully retrieved!", $response);

            } else {
                $this->json_response_error("Error getting event!");                
            }

        } else {
            $this->json_response_error("Error getting event - Empty query given!")
        }
    }

    public function create(
        $start_point,
        $end_point,
        $price,
        $seats_rem,
        $depart_date,
        $eta,
        $fb_fk,
        $description) {

        mysql_query("SET AUTOCOMMIT=0");
        mysql_query("START TRANSACTION");

        $a1 = mysql_query("INSERT INTO events (start_point, end_point, price, seats_rem, depart_date, eta, fb_fk, description) 
            VALUES ('$start_point', 
                    '$end_point', 
                    $price, 
                    $seats_rem, 
                    $depart_date,
                    $eta,
                    $fb_fk, 
                    '$description');");
        $event_fk = mysql_insert_id();
        $a2 = mysql_query("INSERT INTO user_event_status (fb_fk, event_fk, is_driver, status)
            VALUES ($fb_fk, 
                $event_fk, 
                1, 
                'PENDING');");

        $result = $a1 and $a2;
        if ($result) {
            mysql_query("COMMIT");
        } else {
            mysql_query("ROLLBACK");
        }
        
        $response = array();

        if ($result) {
            $response['event_fk'] = $event_fk;
            $this->json_response_success("Event successfully created!", $response);
        } else {
            $this->json_response_error("Error occurred creating event");
        }
    }
}

?>