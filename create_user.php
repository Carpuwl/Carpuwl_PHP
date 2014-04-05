<?php

class CREATE_USER {

    public function create ($fb_fk, $name, $phone) {

        //json response array
        $respone = array();      

        $result = mysql_fetch_array(mysql_query("SELECT EXISTS(SELECT * FROM user WHERE fb_fk = $fb_fk);"));
        
        if ($result[0]) {
            $response['success'] = 1;
            $response['message'] = "User successfully signed in";
            echo json_encode($response);

        } else {
            $result = mysql_query("INSERT INTO user (fb_fk, name, phone) VALUES ($fb_fk, '$name', '$phone');");    

            if ($result) {
                $response['success'] = 1;
                $response['message'] = "User successfully created";
                echo json_encode($response);
            } else {
                $response['success'] = 0;
                $response['message'] = "Error occurred creating a user, or users already exists";
                echo json_encode($response);
            }
        }
    }   

}

   
?>