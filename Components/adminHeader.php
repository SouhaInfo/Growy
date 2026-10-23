<?php
require_once "db_connect.php";
// Check if a session ID is set and assign it to $userID
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
// If the user clicks on the logout link, destroy the session
if (isset($_GET['logout'])) {
  session_unset(); // Unset all session variables
  session_destroy(); // Destroy the session
  header("Location: ../index.php"); // Redirect to the homepage (or login page)
  exit();
}

// Check if the userID is set and matches one of the allowed values
$allowedUserIDs = [57, 58, 59, 60];
$isAllowedUser = isset($_SESSION['userID']) && in_array($_SESSION['userID'], $allowedUserIDs);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../Assets/CSS/headerLI.css">
  <!-- Link to Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        <li><a href="../Components/profile.php">profile</a></li>
        <li><a href="../Components/">Requests</a></li>
        <li><a href="../Components/">Contributions</a></li>
        <li><a href="../Components/">Announcements</a></li>
        <li><a href="../Components/adminCourses.php">Courses</a></li>
        <li><a href="../Components/Main_Contribute_pageLI.php">Messages</a></li>
        <li><a href="../Components/adminPayments.php">Payments</a></li>
        
        <?php if ($isAllowedUser): ?>
            <li><a href="?logout=true" class="login-btn">Log out</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>
</body>
</html>
