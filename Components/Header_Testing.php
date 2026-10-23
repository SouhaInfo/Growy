<?php
require_once "db_connect.php";
session_start(); // Start the session to access session variables
$userID = $_SESSION['userID'] ?? null; // Retrieve the userID from session or set it to null if not available
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/CSS/header.css">
  <title>Header</title> 
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo">
        <a href="../index.php">Growy</a>
      </div>
      <input type="checkbox" id="menu-toggle">
      <label for="menu-toggle" class="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </label>
      <ul class="nav-links">
        <?php if ($userID): ?>
          <!-- Display this menu if the user is logged in -->
          <li><a href="../Components/profile.php">Profile</a></li>
          <li><a href="../index.php">Home</a></li>
          <li><a href="../Components/aboutus.php">About Us</a></li>
          <li><a href="../Components/announcements.php">Announcements</a></li>
          <li><a href="../Components/courses.php">Courses</a></li>
          <li><a href="../Components/Main_Contribute_page.php">Contribute</a></li>
          <li><a href="../Components/Contact_Us.php">Contact Us</a></li>
          <li><a href="../Components/Contact_Us.php">Cart</a></li>
          <li><a href="../Components/logout.php" class="login-btn">Log Out</a></li>
        <?php else: ?>
          <!-- Display this menu if the user is logged out -->
          <li><a href="../index.php">Home</a></li>
          <li><a href="../Components/aboutus.php">About Us</a></li>
          <li><a href="../Components/announcements.php">Announcements</a></li>
          <li><a href="../Components/courses.php">Courses</a></li>
          <li><a href="../Components/Main_Contribute_page.php">Contribute</a></li>
          <li><a href="../Components/Contact_Us.php">Contact Us</a></li>
          <li><a href="../Components/login.php" class="login-btn">Login / Sign Up</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>
</body>
</html>
