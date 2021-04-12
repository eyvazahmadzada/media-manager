<?php
    // Headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");
    header("Content-Type: application/json");
    header('Access-Control-Allow-Headers: Content-Type');

    include_once '../config/Database.php';
    include_once '../models/Item.php';

    // Instantiate DB
    $database = new Database();
    $db = $database->connect();

    // Instantitate item object
    $item = new Item($db);

    // Get item query
    $result = $item->getItems();
    // Get row counts
    $num = $result->rowCount();

    // Check if there are any item
    if($num > 0) {
        // Item array
        $items_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract(($row));

            $item = array(
                'id' => $id,
                'name' => $name,
                'x' => $x,
                'y' => $y,
                'width' => $width,
                'height' => $height
            );

            // Push to $items_arr
            array_push($items_arr, $item);
        }

        // To JSON
        echo json_encode($items_arr);
    } else {
        // No item
        echo json_encode(array());
    }