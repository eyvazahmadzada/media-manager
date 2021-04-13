<?php
    // Headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: DELETE");
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
        $item->id = $data->id;

        // Delete item query
        if($item->deleteItem()) {
            echo json_encode(array('message' => 'Item Deleted'));
        } else {
            echo json_encode(array('error' => 'An Error Occured'));
        }
    }