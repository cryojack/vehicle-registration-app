<?php
/*
This file includes all the functions necessary to validate vehicle data.
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
 *  Functions for 'index.php'
 */

// Check if vehicle name is valid (uppercase, lowercase characters with numbers)
function isVehicleNameValid ($name) {
    $regex = "/^[A-Za-z][A-Za-z0-9]*(?:[A-Za-z0-9]+)*$/";
    if (preg_match($regex,$name) && strlen($name) <= 50) {
        return true;
    } else {
        return false;
    }
}

// Check if vehicle name already exists in database
function vehicleNameExists ($conn,$name) {
    $result;
    $sql = "SELECT * FROM VEHICLE_LIST WHERE VEHICLE_NAME = :name;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":name",$name);
        if ($stmt->execute()) {
            $rows = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rows > 0) {
                $result = true;
            } else {
                $result = false;
            }
        }
    }
    unset($conn);
    return $result;
}

// Check if owner name is valid (same as vehicle name)
function isOwnerNameValid ($owner) {
    $regex = "/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/";
    if (preg_match($regex,$owner)) {
        return true;
    } else {
        return false;
    }
}

// Check if the owner name exists in the database
function ownerExists ($conn,$owner) {
    $result;
    $sql = "SELECT * FROM VEHICLE_LIST WHERE VEHICLE_OWNER = :owner;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":owner",$owner);
        if ($stmt->execute()) {
            $rows = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rows > 0) {
                $result = true;
            } else {
                $result = false;
            }
        }
    }
    unset($conn);
    return $result;
}

// Get the vehicle type (Car, Bike, Other) from the drop down 
// for insertion in database
function getVehicleType ($type) {
    $model;
    switch ($type) {
        case 'car':
            $model = 'CAR';
            break;
        
        case 'bike':
            $model = 'BIKE';
            break;

        case 'other':
            $model = 'OTHER';
            break;

        default:
            $model = 'OTHER';
            break;
    }
    return $model;
}

// Check if model name is valid (uppercase,lowercase characters with/without hyphen (-))
function isModelNameValid ($model) {
    $regex = "/^[A-Za-z0-9][A-Za-z0-9]*(?:[_-]*[A-Za-z0-9]+)*$/";
    if (preg_match($regex,$model) && strlen($model) <= 100) {
        return true;
    } else {
        return false;
    }
}

// Generate unique id for vehicles and services in jobs determined by
// $type variable (value of 1 = vehicles, value of 2 = services)
function generateVUID ($conn,$type) {
    $vid;
    if ($type === 1) {
        do {
            $vid = mt_rand(1000000000000000,9999999999999999);
            $sql = "SELECT VEHICLE_ID FROM VEHICLE_LIST WHERE VEHICLE_ID = :vid;";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bindParam(":vid", $vid);
                $stmt->execute();
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } while ($rows != 0);
    }

    if ($type === 2) {
        do {
            $vid = bin2hex(random_bytes(8));
            $sql = "SELECT SERVICE_ID FROM VEHICLE_SERVICE_LIST_TBL WHERE SERVICE_ID = :vid;";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bindParam(":vid", $vid);
                $stmt->execute();
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } while ($rows != 0);
    }
    unset($conn);
    return $vid;
}

// Add the vehicle to the database
function addVehicleDetails ($conn,$name,$type,$model,$owner,$date,$price) {
    $response;
    $vid = generateVUID($conn,1);
    $v_type = getVehicleType($type);
    $sql = "INSERT INTO VEHICLE_LIST (VEHICLE_ID, VEHICLE_NAME, VEHICLE_TYPE, VEHICLE_MODEL_NAME, VEHICLE_DATE_PURCHASED, VEHICLE_PRICE, VEHICLE_OWNER) VALUES (:vid, :name, :type, :model, :date, :price, :owner);";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":vid", $vid);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":type", $v_type);
        $stmt->bindParam(":model", $model);
        $stmt->bindParam(":date", $date);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":owner", $owner);

        if ($stmt->execute()) {
            $response = true;
        } else {
            $response = false;
        }
    }
    unset($conn);
    return $response;
}

/* 
 *  Functions for 'add-job-details.php' and 'search-vehicle.php' 
 */

// Show all available vehicles in the 'add-job-details.php' dropdown
function getAllVehicles ($conn) {
    $response;
    $sql = "SELECT VEHICLE_NAME AS vname FROM VEHICLE_LIST ORDER BY VEHICLE_DATE_ADDED ASC;";
    if ($stmt = $conn->prepare($sql)) {
        if ($stmt->execute()) {
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $response[] = $row;
            }
        }
    }
    unset($conn);
    return $response;
}

// Search for vehicles by text
function searchVehicles ($conn,$text) {
    $response;
    $sql = "SELECT VEHICLE_ID AS vid, VEHICLE_NAME AS vname FROM VEHICLE_LIST WHERE VEHICLE_NAME LIKE CONCAT('%', :text, '%');";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":text",$text);
        if ($stmt->execute()) {
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $response[] = $row;
            }
        }
    }
    unset($conn);
    return $response;
}

// Get the vehicle details when clicked
function getVehicleDetails($conn,$vid) {
    $response;
    $sql = "SELECT 
    VEHICLE_NAME AS vname,
    VEHICLE_TYPE AS vtype,
    VEHICLE_MODEL_NAME AS vmodel,
    VEHICLE_DATE_PURCHASED AS vdate,
    VEHICLE_PRICE AS vprice,
    VEHICLE_OWNER AS vowner,
    VEHICLE_DATE_ADDED AS vdateadd
    FROM VEHICLE_LIST WHERE VEHICLE_ID = :vid;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":vid", $vid);
        if ($stmt->execute()) {
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $response[] = $row;
            }
        }
    }
    unset($conn);
    return $response;
}

// Get the vehicle jobs when clicked
function getVehicleJobs($conn,$vid) {
    $response;
    $columns;
    $sql = "SELECT COUNT(*) FROM VEHICLE_SERVICES_TBL WHERE VEHICLE_ID = :vid;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":vid", $vid);
        if ($stmt->execute()) {
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                $sql = "SELECT SERVICE_JOB_NO AS jobno FROM VEHICLE_SERVICES_TBL WHERE VEHICLE_ID = :vid ORDER BY SERVICE_JOB_DATE DESC;";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bindParam(":vid", $vid);
                    if ($stmt->execute()) {
                        $response['success'] = true;
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($rows as $row) {
                            $columns[] = $row;
                        }
                        $response['columns'] = $columns;
                    }
                }
            } else {
                $response['success'] = false;
                $response['message'] = "There are no service jobs for this vehicle!";
            }
        }
    }
    unset($conn);
    return $response;
}

// Get the vehicle ID for insertion into the VEHICLE_SERVICES_TBL database
function getVID ($conn,$name) {
    $vid;
    $sql = "SELECT VEHICLE_ID FROM VEHICLE_LIST WHERE VEHICLE_NAME = :name;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":name", $name);
        $stmt->execute();
        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
        $vid = $rows['VEHICLE_ID'];
    }
    unset($conn);
    return $vid;
}

// Get the job number for a specific service
function getJobNumber($conn,$srvid) {
    $jobnum;
    $sql = "SELECT SERVICE_JOB_NO FROM VEHICLE_SERVICE_LIST_TBL WHERE SERVICE_ID = :srvid;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":srvid", $srvid);
        $stmt->execute();
        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
        $jobnum = $rows['SERVICE_JOB_NO'];
    }
    unset($conn);
    return $jobnum;
}

// Check if the job number is valid (uppercase characters and numbers)
function serviceJobNoValid($number) {
    $regex = "/^[A-Z0-9][A-Z0-9]*(?:[A-Z0-9]+)*$/";
    if (preg_match($regex,$number) && strlen($number) <= 20) {
        return true;
    } else {
        return false;
    }
}

// Checks if the job number exists in the database
function serviceJobNoExists ($conn,$number) {
    $result;
    $sql = "SELECT * FROM VEHICLE_SERVICES_TBL WHERE SERVICE_JOB_NO = :number;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":number",$number);
        if ($stmt->execute()) {
            $rows = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rows > 0) {
                $result = true;
            } else {
                $result = false;
            }
        }
    }
    unset($conn);
    return $result;
}

// Add the job details into the database
function addServiceJobDetails ($conn,$name,$number,$date) {
    $response;
    $price = 0;
    $vid = getVID($conn,$name);
    $sql = "INSERT INTO VEHICLE_SERVICES_TBL (SERVICE_JOB_NO, VEHICLE_ID, SERVICE_JOB_PRICE, SERVICE_JOB_DATE) VALUES (:number, :vid, :price, :date);";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":number", $number);
        $stmt->bindParam(":vid", $vid);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":date", $date);

        if ($stmt->execute()) {
            $response = true;
        } else {
            $response = false;
        }
    }
    unset($conn);
    return $response;
}

// Get the job details when clicked
function getJobDetails($conn,$jobnum) {
    $response;
    $sql = "SELECT
    VEHICLE_LIST.VEHICLE_NAME AS vname,
    VEHICLE_SERVICES_TBL.SERVICE_JOB_NO AS jobno,
    VEHICLE_SERVICES_TBL.SERVICE_JOB_PRICE AS jobprice,
    VEHICLE_SERVICES_TBL.SERVICE_JOB_DATE AS jobdate 
    from VEHICLE_LIST INNER JOIN VEHICLE_SERVICES_TBL 
    ON VEHICLE_SERVICES_TBL.VEHICLE_ID = VEHICLE_LIST.VEHICLE_ID 
    WHERE VEHICLE_SERVICES_TBL.SERVICE_JOB_NO = :jobnum;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":jobnum", $jobnum);
        if ($stmt->execute()) {
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $response[] = $row;
            }
        }
    }
    unset($conn);
    return $response;
}

// Get the job services when clicked
function getJobServices($conn,$jobnum) {
    $response;
    $columns;
    $sql = "SELECT COUNT(*) FROM VEHICLE_SERVICE_LIST_TBL WHERE SERVICE_JOB_NO = :jobnum;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":jobnum", $jobnum);
        if ($stmt->execute()) {
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                $sql = "SELECT SERVICE_ID AS srvid, SERVICE_DETAIL AS srvdtl, SERVICE_PRICE AS srvprice FROM VEHICLE_SERVICE_LIST_TBL WHERE SERVICE_JOB_NO = :jobnum ORDER BY SERVICE_DATE_ADDED ASC;";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bindParam(":jobnum", $jobnum);
                    if ($stmt->execute()) {
                        $response['success'] = true;
                        $response['jobno'] = $jobnum;
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($rows as $row) {
                            $columns[] = $row;
                        }
                        $response['columns'] = $columns;
                    }
                }
            } else {
                $response['success'] = false;
                $response['message'] = "There are no services on this job!";
            }
        }
    }
    unset($conn);
    return $response;
}

// Get sum of all the service prices for a job and insert 
// into the VEHICLE_SERVICES_TBL database
function addServicePrices($conn,$jobnum) {
    $response;
    $sql = "SELECT SUM(SERVICE_PRICE) AS sum FROM VEHICLE_SERVICE_LIST_TBL WHERE SERVICE_JOB_NO = :jobnum;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":jobnum", $jobnum);
        if ($stmt->execute()) {
            $rows = $stmt->fetch(PDO::FETCH_ASSOC);
            if($rows['sum'] === NULL) {
                $total = 0;
            } else {
                $total = $rows['sum'];
            }
            $sql = "UPDATE VEHICLE_SERVICES_TBL SET SERVICE_JOB_PRICE = :total WHERE SERVICE_JOB_NO = :jobnum;";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bindParam(":total", $total);
                $stmt->bindParam(":jobnum", $jobnum);
                if ($stmt->execute()) {
                    $response = true;
                } else {
                    $response = false;
                }
            }
        }
    }
    unset($conn);
    return $response;
}

// Delete a job along with all it's services
function deleteJob($conn,$jobnum) {
    $response;
    $sql = "DELETE FROM VEHICLE_SERVICE_LIST_TBL WHERE SERVICE_JOB_NO = :jobnum;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":jobnum", $jobnum);
        if ($stmt->execute()) {
            $sql = "DELETE FROM VEHICLE_SERVICES_TBL WHERE SERVICE_JOB_NO = :jobnum;";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bindParam(":jobnum", $jobnum);
                if ($stmt->execute()) {
                    $response = getVehicleJobs($conn);
                } else {
                    $response['error'] = "Something went wrong, please try again later!";
                }
            }
        }
    }
    unset($conn);
    return $response;
}

// Delete a service in a job
function deleteService($conn,$srvid) {
    $response;
    $jobnum = getJobNumber($conn,$srvid);
    $sql = "DELETE FROM VEHICLE_SERVICE_LIST_TBL WHERE SERVICE_ID = :srvid;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":srvid", $srvid);
        if ($stmt->execute()) {
            if (addServicePrices($conn,$jobnum) === true) {
                $response[] = getJobServices($conn,$jobnum);
            } else {
                $response['error'] = "Something went wrong, try again later!";
            }
        }
    }
    unset($conn);
    return $response;
}

/*
 *  Functions to show job data and add services on 'add-services.php'
 */

// Show all jobs in the dropdown for the specific vehicle
function getAllJobs ($conn,$vname) {
    $response;
    $vid = getVID($conn,$vname);
    $sql = "SELECT SERVICE_JOB_NO AS jobnum FROM VEHICLE_SERVICES_TBL WHERE VEHICLE_ID = :vid ORDER BY SERVICE_JOB_DATE ASC;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":vid", $vid);
        if ($stmt->execute()) {
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $response[] = $row;
            }
        }
    }
    unset($conn);
    return $response;
}

// Add the service to the database
function addJobServices ($conn,$jobno,$desc,$price) {
    $response;
    $vid = generateVUID($conn,2);
    $sql = "INSERT INTO VEHICLE_SERVICE_LIST_TBL (SERVICE_ID, SERVICE_JOB_NO, SERVICE_DETAIL, SERVICE_PRICE) VALUES (:vid, :jobno, :desc, :price);";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(":vid", $vid);
        $stmt->bindParam(":jobno", $jobno);
        $stmt->bindParam(":desc", $desc);
        $stmt->bindParam(":price", $price);

        if ($stmt->execute()) {
            if (addServicePrices($conn,$jobno) === true) {
                $response = true;
            }
        } else {
            $response = false;
        }
    }
    unset($conn);
    return $response;
}
?>