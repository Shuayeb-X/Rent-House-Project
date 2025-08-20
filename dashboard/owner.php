<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'owner') { header("Location: ../login.html"); exit(); }
$first = htmlspecialchars($_SESSION['first_name'] ?? 'Owner');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Owner Dashboard</title>
  <link rel="stylesheet" href="../css/style.css" />
</head>
<body class="glass">
  <div class="container">
    <div class="logo">🏠 RentHouse</div>
    <h2>Welcome, <?php echo $first; ?>!</h2>

    <section class="card">
      <h3>Upload New House</h3>
      <form id="uploadHouseForm" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="House Title" required />
        <input type="text" name="location" placeholder="Location" required />
        <select name="category" required>
          <option value="1BHK">1BHK</option>
          <option value="2BHK">2BHK</option>
          <option value="3BHK">3BHK</option>
        </select>
        <input type="number" name="price" placeholder="Price (BDT)" required />
        <label>Latitude (optional)</label>
        <input type="number" step="any" name="latitude" placeholder="e.g., 23.8103" />
        <label>Longitude (optional)</label>
        <input type="number" step="any" name="longitude" placeholder="e.g., 90.4125" />
        <input type="file" name="image" accept="image/*" required />
        <button type="submit">Upload</button>
        <p id="uploadMsg"></p>
      </form>
    </section>

    <section class="card">
      <h3>Update Your Profile</h3>
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

    <section class="card">
      <h3>Your Listings</h3>
      <div id="ownerListings" class="house-listing"></div>
    </section>

    <p><a href="../php/logout.php" class="logout-btn">🚪 Logout</a></p>
  </div>

  <script>
    document.getElementById("updateProfileForm").addEventListener("submit", function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      fetch("../php/update-profile.php", { method: "POST", body: formData })
        .then(res => res.text())
        .then(data => { document.getElementById("profileMsg").textContent = data; });
    });

    document.getElementById("uploadHouseForm").addEventListener("submit", function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      fetch("../php/upload-house.php", { method: "POST", body: formData })
        .then(res => res.text())
        .then(data => { document.getElementById("uploadMsg").textContent = data; loadListings(); });
    });

    function loadListings() {
      fetch("../php/get-owner-listings.php")
        .then(res => res.text())
        .then(html => { document.getElementById("ownerListings").innerHTML = html; });
    }
    loadListings();
  </script>
</body>
</html>
