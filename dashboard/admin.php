<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') { header("Location: ../login.html"); exit(); }
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="glass">
  <div class="container" style="max-width: 900px;">
    <div class="logo">RentHouse</div>
    <h2>Welcome, Admin</h2>
    <section class="card">
      <h3>Manage Users</h3>
      <ul id="userList"></ul>
    </section>
    <section class="card">
      <h3>Site Stats</h3>
      <div id="siteStats">Loading...</div>
    </section>
    <p><a href="../php/logout.php">Logout</a></p>
  </div>
  <script>
    fetch('../php/admin/manage_users.php').then(r=>r.json()).then(users=>{
      const ul = document.getElementById('userList'); ul.innerHTML='';
      users.forEach(u=>{ const li=document.createElement('li'); li.textContent = (u.first_name+' '+u.last_name).trim()+' ('+u.user_type+')'; ul.appendChild(li); });
    });
    fetch('../php/admin/site_stats.php').then(r=>r.text()).then(t=>{ document.getElementById('siteStats').innerHTML=t; });
  </script>
</body>
</html>
