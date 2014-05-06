<?php
require_once dirname(__FILE__) . '/JSONResponseHandler.php';

/**
 * Class User can be used to retrieve and create individual users
 */
class User extends JSONResponseHandler {

    public $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /** Used to fetch a specified user
     * @param $fb_fk
     */
    public function get ($fb_fk) {

        $stmt = $this->db->prepare("SELECT * FROM user WHERE fb_fk = ?;");
        $stmt->bind_param('d', $fb_fk);

        if ($stmt->execute()) {

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $response['user'] = array (
                    'name' => $row['name'],
                    'rating' => $row['rating'],
                    'num_ratings' => $row['num_ratings'],
                    'phone' => $row['phone']
                );

                $this->json_response_success("User successfully retrieved!", $response);

            } else {
                $this->json_response_error("Error getting user - User does not exist!");
            }

        } else {
            $this->json_response_error("A database error occured, could not get user!");
        }
        $stmt->close();
    }

    /** Used to sign in a user or create one if it does not already exist in the database
     * @param $fb_fk
     * @param $name
     * @param $phone
     */
    public function create ($fb_fk, $name, $phone) {

        //See if the user already exists in the database
        $stmt = $this->db->prepare("SELECT * FROM user WHERE fb_fk = ?;");
        $stmt->bind_param('d', $fb_fk);
        if ($stmt->execute()) {

            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                //If the user exists, sign in
                $this->json_response_success("User successfully signed in!");

            } else {
                //If the user does not exist, take the data and create a new user
                $stmt = $this->db->prepare("
                            INSERT INTO user (fb_fk, name, phone)
                            VALUES (?, ?, ?);");
                $stmt->bind_param('dss', $fb_fk, $name, $phone);

                if ($stmt->execute()) {
                    $this->json_response_success("User successfully created!");
                } else {
                    $this->json_response_error("Error occurred creating a user");
                }
            }

        } else {
            $this->json_response_error("Error creating user - A Database error occurred!");
        }

        $stmt->close();
    }

}