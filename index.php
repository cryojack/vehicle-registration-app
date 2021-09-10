<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>VEA - The Vehicle Expense App</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="scripts/style.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.js" defer></script>
    <script type="text/javascript" src="scripts/script.js" defer></script>
  </head>
  <body>
    <div class="container">
      <div class="header">Vehicle Registration App</div>
      <div class="navbar">
        <li><a href="index.php">Index</a></li>
        <li><a href="search-vehicle.php">Search Vehicle</a></li>
        <li><a href="add-job-details.php">Add Job details</a></li>
        <li><a href="add-services.php">Add Services</a></li>
        <li><a href="about.php">About</a></li>
      </div>
      <div class="main-section">
      <h1>Add Vehicle Details</h1>
        <form id="insertVehicle" action="includes/validate-vehicle-data.php" method="POST" autocomplete="off">
          <div class="input-box">
            <label for="vehicleName">Vehicle name</label>
            <input type="text" id="vehicleName" name="vehicleName" placeholder="Enter vehicle name">
            <div class="error-box" id="nameErr"></div>
          </div>
          <div class="input-box">
            <label for="vehicleType">Vehicle type</label>
            <select id="vehicleType" name="vehicleType">
              <option value="car">Car</option>
              <option value="bike" selected>Bike</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="input-box">
            <label for="vehicleModel">Model name</label>
            <input type="text" id="vehicleModel" name="vehicleModel" placeholder="Enter vehicle model">
            <div class="error-box" id="modelErr"></div>
          </div>
          <div class="input-box">
            <label for="ownerName">Owner name</label>
            <input type="text" id="ownerName" name="ownerName" placeholder="Enter owner">
            <div class="error-box" id="ownerErr"></div>
          </div>
          <div class="input-box">
            <label for="datePurchased">Date purchased</label>
            <input type="date" id="datePurchased" name="datePurchased">
            <div class="error-box" id="dateErr"></div>
          </div>
          <div class="input-box">
            <label for="purchasePrice">Purchase price</label>
            <input type="number" id="purchasePrice" name="purchasePrice" placeholder="Enter price(in Rs.)" min="0">
            <div class="error-box" id="priceErr"></div>
          </div>
          <div class="error-box" id="submitErr"></div>
          <div class="input-box">
            <button id="submitDetails" name="submitDetails">Create vehicle</button>
          </div>
        </form>
      </div>
      <div class="footer">
        Created by Mridul N
        <br/>
        All rights reserved
      </div>
    </div>
  </body>
</html>