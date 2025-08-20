<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'renter') { header("Location: ../login.html"); exit(); }
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Renter Dashboard</title>
  <link rel="stylesheet" href="../css/style.css" />
  <script src="../js/geo-fetch.js" defer></script>
</head>
<body class="glass">
  <div class="container">
    <div class="logo">RentHouse</div>
    <h2>Welcome, Renter!</h2>

    <form id="filterForm">
      <label for="searchLocation">Location:</label>
      <input type="text" id="searchLocation" placeholder="Enter Location" />
      <label for="searchCategory">Category:</label>
      <select id="searchCategory">
        <option value="">All Categories</option>
        <option value="1BHK">1BHK</option>
        <option value="2BHK">2BHK</option>
        <option value="3BHK">3BHK</option>
      </select>
      <label for="searchPrice">Max Price:</label>
      <input type="number" id="searchPrice" placeholder="Max Price" />
      <button type="submit">Search</button>
    </form>

    <div id="houseList" class="house-listing"></div>

    <button id="showHistoryBtn" type="button">View Rent Request History</button>
    <div id="bookingHistory" style="display:none;"></div>

    <section style="margin-top: 30px;">
      <h3>Update Profile</h3>
      <form id="updateProfileForm">
        <input type="text" name="first_name" placeholder="First Name" required />
        <input type="text" name="last_name" placeholder="Last Name" required />
        <input type="text" name="mobile" placeholder="Mobile Number" required />
        <input type="text" name="nid" placeholder="NID or Birth Certificate No." required />
        <input type="email" name="email" placeholder="Email" required />
        <button type="submit">Update Profile</button>
        <p id="profileMsg"></p>
      </form>
    </section>

    <p><a href="../php/logout.php">Logout</a></p>
  </div>
</body>
</html>
