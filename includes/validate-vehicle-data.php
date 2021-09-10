<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'connection.php';
include_once 'vehicle-functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submitDetails'])) {

        $vehicleName     = $_POST["vehicleName"];
        $vehicleType     = $_POST["vehicleType"];
        $vehicleModel    = $_POST["vehicleModel"];
        $ownerName       = $_POST["ownerName"];
        $datePurchased   = $_POST["datePurchased"];
        $purchasePrice   = $_POST["purchasePrice"];

        if (empty($vehicleName) || empty($vehicleModel) || empty($ownerName) || empty($datePurchased) || empty($purchasePrice)) {
            $errors['empty'] = "Please enter all fields!";
        }

        if (!empty($vehicleName) && !isVehicleNameValid($vehicleName)) {
            $errors['name'] = "Please enter a valid vehicle name!";
        }

        if (vehicleNameExists($connectDB,$vehicleName)) {
            $errors['name'] = "Vehicle name already exists";
        }

        if (!empty($ownerName) && !isOwnerNameValid($ownerName)) {
            $errors['owner'] = "Please enter a valid owner name";
        }

        if (ownerExists($connectDB,$ownerName)) {
            $errors['owner'] = "Owner already exists";
        }

        if (!empty($vehicleModel) && !isModelNameValid($vehicleModel)) {
            $errors['model'] = "Please enter a valid model name";
        }

        if (!empty($errors)) {
            $response['success'] = false;
            $response['errors'] = $errors;
        }

        else {
            if (addVehicleDetails($connectDB,$vehicleName,$vehicleType,$vehicleModel,$ownerName,$datePurchased,$purchasePrice) === true) {
                $response['success'] = true;
                $response['inserted'] = "Vehicle details added successfully!";
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