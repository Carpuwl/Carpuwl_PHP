<?php
require_once dirname(__FILE__) . "/JSONResponseHandler.php";
require_once dirname(__FILE__) . "/db_connect.php";

/**
* Class that can be used to get and create an individual event
*/
class Event extends JSONResponseHandler
{
    public $db;

    public function __construct() {
        $this->db = DB_CONNECT::connect();
    }

    /** This method is used to retrieve the data for a specific event
     * @param $event_pk : the pk of the event bring retrieved
     */
    public function get ($event_pk) {

        $stmt = $this->db->prepare("
                    SELECT *
                    FROM events e
                    LEFT JOIN user u
                    ON e.fb_fk = u.fb_fk
                    WHERE event_pk = ?;");
        if ($stmt) {

            $params = array($event_pk);
            if ($stmt->execute($params)) {

                if ($stmt->rowCount() > 0) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    $response = array (
                        'name' => $row['name'],
                        'rating' => $row['rating'],
                        'num_ratings' => $row['num_ratings'],
                        'phone' => $row['phone'],
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

                    $this->json_response_success("Event successfully retrieved!", $response);

                } else {
                    $this->json_response_error("Error getting event! - No rows returned");
                }

            } else {
                $this->json_response_error("Error getting event - A Database error occurred while executing the stmt!");
            }
        } else {
            $this->json_response_error("PDO stmt could not be created!");
        }

        $stmt->closeCursor();
        unset($this->db);
    }

    /** This method is used to create a new event entry in the database
     * @param $start_point : Starting point of the ride
     * @param $end_point : Final destination point of the ride
     * @param $price : Listed price of the ride
     * @param $seats_rem : Number of seats remaining in the car
     * @param $depart_date : Estimated depart date and time
     * @param $eta : Estimated arrival date and time
     * @param $fb_fk : facebook fk of the driver/poster of the ride
     * @param $description : Description written by the driver/poster of the ride
     */
    public function create(
        $start_point,
        $end_point,
        $price,
        $seats_rem,
        $depart_date,
        $eta,
        $fb_fk,
        $description) {

        $stmt = $this->db->prepare("
                    INSERT INTO events (
                        start_point,
                        end_point,
                        price,
                        seats_rem,
                        depart_date,
                        eta,
                        fb_fk,
                        description)
                    VALUES (?,?,?,?,?,?,?,?);");
        if ($stmt) {
            $params = array(
                $start_point,
                $end_point,
                $price,
                $seats_rem,
                $depart_date,
                $eta,
                $fb_fk,
                $description);

            if ($stmt->execute($params)) {
                //Getting the primary key of the previous statement
                $stmt = $this->db->query("SELECT LAST_INSERT_ID();");
                if ($stmt != FALSE) {
                    $event_pk = $stmt->fetch(PDO::FETCH_BOTH)[0]; //The result is the event_pk
                    $stmt = $this->db->prepare("
                            INSERT INTO user_event_status (
                                fb_fk,
                                event_fk,
                                is_driver,
                                status)
                            VALUES (?,?,?,?);");
                    $status = "PENDING";
                    $is_driver = true;
                    $params = array($fb_fk, $event_pk, $is_driver, $status);

                    if ($stmt->execute($params)) {
                        $this->json_response_success("Event successfully created!");
                        $this->get($event_pk);
                    } else {
                        $this->json_response_error("Entry into user_events_status table could not be made - A Database error occurred!");
                    }

                } else {
                    $this->json_response_error("Event PK could not be retrieved - A Database error occurred, the query was not executed correctly!");
                }

            } else {
                $this->json_response_error("Event could not be created - A Database error occurred while executing the stmt!");
            }
        } else {
            $this->json_response_error("PDO stmt could not be created!");
        }

        $stmt->closeCursor();
        unset($this->db);
    }
}