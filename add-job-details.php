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
      <h1>Add Job Details</h1>
        <form id="insertJob" action="includes/validate-job-data.php" method="POST" autocomplete="off">
        <div class="input-box">
            <label for="vehicleDetails">Vehicle Name</label>
            <select id="vehicleDetails" name="vehicleDetails">
              <option value="">Please select...</option>
            </select>
            <div class="error-box" id="detailsErr"></div>
          </div>
          <div class="input-box">
            <label for="serviceJobNo">Service Job Number</label>
            <input type="text" id="serviceJobNo" name="serviceJobNo" placeholder="Enter service job number">
            <div class="error-box" id="jobNumErr"></div>
          </div>
          <div class="input-box">
            <label for="serviceDate">Service date</label>
            <input type="date" id="serviceDate" name="serviceDate">
            <div class="error-box" id="jobDateErr"></div>
          </div>
          <div class="error-box" id="submitErr"></div>
          <div class="input-box">
            <button id="submitJob" name="submitJob">Add service details</button>
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