<?php

include_once "dbconfig.php";

$errors = array();
$response = array();

try {
  $connectDB = new PDO($dbsn, $dbusername, $dbpassword);
  $connectDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $connectDB->getAttribute(constant("PDO::ATTR_CONNECTION_STATUS"));
} catch (PDOException $exception) {
  $errors['connerror'] = "Could not connect to server, please try again later";
  die($exception->getMessage());
}
?>