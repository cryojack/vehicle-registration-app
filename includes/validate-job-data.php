<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'connection.php';
include_once 'vehicle-functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submitJob'])) {

        $vehicleDetails = $_POST["vehicleDetails"];
        $serviceNumber  = $_POST["serviceJobNo"];
        $serviceDate    = $_POST["serviceDate"];

        if (empty($vehicleDetails) || empty($serviceNumber) || empty($serviceDate)) {
            $errors['empty'] = "Please enter all fields!";
        }

        if (!empty($serviceNumber) && !serviceJobNoValid($serviceNumber)) {
            $errors['number'] = "Job number is invalid!";
        }

        if (serviceJobNoExists($connectDB,$serviceNumber)) {
            $errors['number'] = "Job number already exists!";
        }

        if (!empty($errors)) {
            $response['success'] = false;
            $response['errors'] = $errors;
        }

        else {
            if (addServiceJobDetails($connectDB,$vehicleDetails,$serviceNumber,$serviceDate) === true) {
                $response['success'] = true;
                $response['inserted'] = "Service job details added successfully!";
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