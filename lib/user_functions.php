<?php

class USER extends JSONResponseHandler {

    public function __construct() {}

    public function get ($fb_fk) {
   
        //json response array
        $respone = array();
                
        $result = mysql_query(
            "SELECT * 
            FROM user 
            WHERE fb_fk = $fb_fk;");

        if (!empty($result)) {
            
            if (mysql_num_rows($result) > 0) {
                $row = mysql_fetch_array($result);

                $response['user'] = array (
                    'name' => $row['name'],
                    'rating' => $row['rating'],
                    'num_ratings' => $row['num_ratings'],
                    'phone' => $row['phone']
                );

                $this->json_response_success("User successfully retrieved!", $response);
                
            } else {
                
                $this->json_response_error("Erro getting user!");
            }

        } else {
            
            $this->json_response_error("Error getting user - empty query returned");
        }
    }

    public function create ($fb_fk, $name, $phone) {

        $result = mysql_fetch_array(mysql_query("SELECT EXISTS(SELECT * FROM user WHERE fb_fk = $fb_fk);"));
        
        if ($result[0]) {
            $this->json_response_success("User successfully signed in!");

        } else {
            $result = mysql_query(
                "INSERT INTO user (fb_fk, name, phone) 
                VALUES ($fb_fk, '$name', '$phone');");    

            if ($result) {
                $this->json_response_success("User successfully created!");
            } else {
                $this->json_response_error("Error occurred creating a user, or users already exists");
            }
        }
    } 

}

?>