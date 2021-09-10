<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'connection.php';
include_once 'vehicle-functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submitService'])) {

        $serviceJobNo       = $_POST["serviceJobNo"];
        $serviceDescription = strtoupper($_POST["serviceDescription"]);
        $servicePrice       = $_POST["servicePrice"];

        if (empty($serviceJobNo) || empty($serviceDescription) || empty($servicePrice)) {
            $errors['empty'] = "Please enter all fields!";
        }

        if (!empty($errors)) {
            $response['success'] = false;
            $response['errors'] = $errors;
        }

        else {
            if (addJobServices($connectDB,$serviceJobNo,$serviceDescription,$servicePrice) === true) {
                $response['success'] = true;
                $response['inserted'] = "Service added to job successfully!";
            } else {
                $response['success'] = false;
                $response['inserted'] = "Couldn't complete the request (Connection Error)!";
            }
        }
    } else {
        $errors['sqlerror'] = "Something went wrong, please try again later!";
    }
} else {
    $errors['connerror'] = "Server cannot be reached, please try again later!";
}

echo json_encode($response);
?>