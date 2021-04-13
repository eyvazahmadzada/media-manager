<?php
    // Headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Content-Type: application/json");
    header('Access-Control-Allow-Headers: Content-Type');

    // Start session
    session_start();  

    //Get posted data
    $data = json_decode(file_get_contents("php://input"));
    
    // Check if data is submitted
    if($data) {
        // Set session data
        $_SESSION["host"] = $data->host;
        $_SESSION["username"] = $data->username;
        $_SESSION["password"] = $data->password;
    } else {
        echo json_encode(array('error' => 'Could not connect to MySQL'));
    }