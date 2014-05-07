<?php

class JSONResponseHandler 
{
    public function __construct() {}

    public function json_response_success($msg, $response = array()) {
        $response['success'] = 1;
        $response['message'] = $msg;
        echo json_encode($response);
    }

    public function json_response_error ($e, $response = array()) {
        $response['success'] = 0;
        $response['message'] = "Error: ". $e;
        echo json_encode($response);
    }   
}
