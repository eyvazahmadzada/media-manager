<?php
    // Headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Content-Type: application/json");
    header('Access-Control-Allow-Headers: Content-Type');

    include_once '../config/Database.php';
    include_once '../models/Item.php';

    // Instantiate DB
    $database = new Database();
    $db = $database->connect();

    // Instantitate item object
    $item = new Item($db);

    //Get posted data
    $data = json_decode(file_get_contents("php://input"));
    
    // Check if data is submitted
    if($data) {
        // Assign values
        $item->name = $data->name;
        $item->x = $data->x;
        $item->y = $data->y;
        $item->width = $data->width;
        $item->height = $data->height;

        // Create item query
        if($item->createItem()) {
            echo json_encode(array('message' => 'Item Created'));
        } else {
            echo json_encode(array('message' => 'An Error Occured'));
        }
    }