<?php
require_once dirname(__FILE__) . '/JSONResponseHandler.php';
require_once dirname(__FILE__) . '/db_connect.php';

/**
 * Created by PhpStorm.
 * User: Christopher
 * Date: 23/04/14
 * Time: 12:23 AM
 */

class Rating extends JSONResponseHandler {

    public $db;

    public function __construct() {
        $this->db = DB_CONNECT::connect();
    }

    public function get ($fb_fk) {

        $stmt = $this->db->prepare("SELECT rating, num_ratings FROM user WHERE fb_fk = ?;");
        $stmt->bind_param('d', $fb_fk);

        if ($stmt->execute()) {

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $response = array (
                    'rating' => $row['rating'],
                    'num_ratings' => $row['num_ratings'],
                );

                $this->json_response_success("Rating successfully retrieved!", $response);

            } else {
                $this->json_response_error("Error getting rating - User does not exist!");
            }

        } else {
            $this->json_response_error("A database error occured, could not get rating!");
        }
        $stmt->close();
        $this->db->close();
    }

    public function update() {

    }

} 