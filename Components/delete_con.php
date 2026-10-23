<?php
require_once 'db_connect.php'; // Database connection file
session_start();

// Ensure user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php"); // Redirect to login page if the user is not logged in
    exit();
}

$userID = $_SESSION['userID']; // Get the userID from the session

// Check if the form is submitted and if the conID is set
if (isset($_POST['conID'])) {
    $conID = $_POST['conID'];

    // Prepare the SQL query to delete the contribution, ensuring it's the logged-in user's contribution
    $sql = "DELETE FROM contribution WHERE conID = ? AND userID = ?";

    // Prepare and bind the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $conID, $userID);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect after successful deletion
            header("Location: My_Contributions_LI.php"); // Adjust this to the page you want to redirect to
            exit();
        } else {
            // Error deleting
            echo "Error deleting the contribution.";
        }
    } else {
        echo "Error preparing the SQL query.";
    }
}
?>

