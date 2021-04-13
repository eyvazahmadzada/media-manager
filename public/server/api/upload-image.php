<?php
    // Headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Content-Type: application/json");
    header('Access-Control-Allow-Headers: Content-Type');

    // Get uploaded file info
    $fileName = $_FILES["image"]["name"];
    $fileSize = $_FILES["image"]["size"];
    $tempPath = $_FILES["image"]["tmp_name"];

    $uploadPath = "../../img/";

    // Get info about file
    $fileExt = strtolower((pathinfo($fileName, PATHINFO_EXTENSION)));

    //Supported file extensions
    $validExtensions = ["jpeg", "jpg", "png"];

    // Check if file extension is valid
    if (in_array($fileExt, $validExtensions)) {

        // Check if file already exists
        if (!file_exists($uploadPath . $fileName)) {
            $maxSize = 5000; // 5 MB

            // Check if file size is not too large
            if ($fileSize < ($maxSize * 1000)) {
                move_uploaded_file($tempPath, $uploadPath . $fileName);
                echo json_encode(array('message' => 'Image Uploaded'));
            } else {
                echo json_encode(array('error' => 'Uploaded file too large, max size: 5 MB'));
            }
        } else {
            echo json_encode(array('error' => 'Uploaded file already exists'));
        }
    } else {
        echo json_encode(array('error' => 'Unsupported file extension'));
    }