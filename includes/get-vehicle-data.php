<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'connection.php';
include_once 'vehicle-functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['vehicleDetails'])) {
        $rdata = array();
        $vdata = getAllVehicles($connectDB);
        for ($i=0; $i < count($vdata); $i++) { 
            $rdata[] = $vdata[$i];
        }
        echo json_encode($rdata);
    }

    if (isset($_POST['searchVehicle'])) {
        $text = $_POST['searchVehicle'];
        if (!empty($text)) {
            $rdata = array();
            $vdata = searchVehicles($connectDB,$text);
            for ($i=0; $i < count($vdata); $i++) { 
                $rdata[] = $vdata[$i];
            }
            echo json_encode($rdata);
        }
    }

    if (isset($_POST['vehicleName'])) {
        $vid = $_POST['vehicleName'];
        $rdata = array();
        $vdata = getVehicleDetails($connectDB,$vid);
        for ($i=0; $i < count($vdata); $i++) { 
            $rdata[] = $vdata[$i];
        }
        echo json_encode($rdata);
    }

    if (isset($_POST['jobNumber'])) {
        $jobnum = $_POST['jobNumber'];
        $vdata = getVehicleJobs($connectDB,$jobnum);
        echo json_encode($vdata);
    }

    if (isset($_POST['jobDetails'])) {
        $jobnum = $_POST['jobDetails'];
        $rdata = array();
        $vdata = getJobDetails($connectDB,$jobnum);
        for ($i=0; $i < count($vdata); $i++) { 
            $rdata[] = $vdata[$i];
        }
        echo json_encode($rdata);
    }

    if (isset($_POST['jobServices'])) {
        $service = $_POST['jobServices'];
        $vdata = getJobServices($connectDB,$service);
        echo json_encode($vdata);
    }

    if (isset($_POST['serviceJobNo'])) {
        $vehicleName = $_POST['serviceJobNo'];
        $rdata = array();
        $vdata = getAllJobs($connectDB,$vehicleName);
        for ($i=0; $i < count($vdata); $i++) {
            $rdata[] = $vdata[$i];
        }
        echo json_encode($rdata);
    }

    if (isset($_POST['deleteService'])) {
        $srvid = $_POST['deleteService'];
        $rdata = array();
        $vdata = deleteService($connectDB,$srvid);
        for ($i=0; $i < count($vdata); $i++) {
            $rdata[] = $vdata[$i];
        }
        echo json_encode($rdata);
    }
}

?>