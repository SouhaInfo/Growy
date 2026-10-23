<?php
session_start();

require_once 'db_connect.php'; // Ensure this connects to your database

if (!isset($_SESSION['userID']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];

    // Query to find a user with the provided remember_token
    $query = "SELECT * FROM user WHERE remember_token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Set session variables to log the user in
        $_SESSION['userID'] = $user['userID'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $user['username'];
    }

    $stmt->close();
}
?>
