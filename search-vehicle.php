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
      <h1>Search Vehicles</h1>
        <form id="searchForm" action="includes/get-vehicle-data.php" method="POST" autocomplete="off">
          <div class="input-box">
            <input type="text" id="searchVehicle" name="searchVehicle" placeholder="Search for vehicle">
            <div class="error-box" id="searchErr"></div>
          </div>
        </form>
        <div id="showResults">
          <ul>
          </ul>
        </div>
      </div>
      <div class="job-section">
      </div>
      <div class="service-section">
      </div>
      <div class="footer">
        Created by Mridul N
        <br/>
        All rights reserved
      </div>
    </div>
  </body>
</html>