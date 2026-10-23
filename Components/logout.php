<?php
session_start();
require_once 'db_connect.php';

// Clear remember_token from the database
if (isset($_SESSION['userID'])) {
    $query = "UPDATE user SET remember_token = NULL WHERE userID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_SESSION['userID']);
    $stmt->execute();
    $stmt->close();
}

// Destroy the session
session_unset();
session_destroy();

// Clear the remember_token cookie
setcookie("remember_token", "", time() - 3600, "/"); // Expire the cookie

header("Location: login.php");
exit();
?>
