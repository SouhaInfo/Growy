<?php
require_once "db_connect.php";

// If the user clicks on the logout link, destroy the session
if (isset($_GET['logout'])) {
  session_unset(); // Unset all session variables
  session_destroy(); // Destroy the session
  header("Location: ../index.php"); // Redirect to the homepage (or login page)
  exit();
}

// Fetch the number of items in the highest cart for the logged-in user
$numItems = 0; // Default value if conditions aren't met
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID']; // Ensure userID is retrieved correctly

    // Check if userID is not null and not equal to 57, 58, 59, or 60
    if (!in_array($userID, [57, 58, 59, 60])) {
        // Step 1: Get the highest cartID for the logged-in user
        $sql = "SELECT MAX(cartID) AS maxCartID FROM cart WHERE userID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row_cart = $result->fetch_assoc();
        $maxCartID = $row_cart['maxCartID'];

        // Step 2: Get the number of items for that cartID
        if ($maxCartID) {
            $sql = "SELECT COUNT(*) AS numItems FROM cartcourse WHERE cartID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $maxCartID);
            $stmt->execute();
            $result = $stmt->get_result();
            $row_cart = $result->fetch_assoc();
            $numItems = $row_cart['numItems'];
        }
    }
}
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
        <li><a href="../Components/profile.php">Profile</a></li>
        <li><a href="../index.php">Home</a></li>
        <li><a href="../Components/aboutus.php">About Us</a></li>
        <li><a href="../Components/courses.php">Courses</a></li>
        <li><a href="../Components/announcements.php">Announcements</a></li>
        <li><a href="../Components/Main_Contribute_page.php">Contribute</a></li>
        <li><a href="../Components/Contact_UsLI.php">Contact Us</a></li>
        
        <!-- Cart Icon with item count -->
        <li>
          <a href="../Components/cart.php" class="cartt-btn">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-item-count">
              <?php echo $numItems; ?>
            </span>
            Cart
          </a>
        </li>
        
        <?php if (isset($_SESSION['userID']) && !in_array($_SESSION['userID'], [57, 58, 59, 60])): ?>
            <li><a href="?logout=true" class="login-btn">Log out</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>
</body>
</html>
