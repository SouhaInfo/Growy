<?php
require_once 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['commentID']) && isset($_SESSION['userID'])) {
        // Sanitize input to prevent SQL injection
        $commentID = mysqli_real_escape_string($conn, $_POST['commentID']);
        $userID = $_SESSION['userID'];

        // Verify if the user owns the comment
        $sql_verify = "SELECT * FROM comment WHERE commentID = '$commentID' AND userID = '$userID'";
        $res_verify = $conn->query($sql_verify);

        if ($res_verify && $res_verify->num_rows > 0) {
            // Delete the comment
            $sql_delete = "DELETE FROM comment WHERE commentID = '$commentID'";
            if ($conn->query($sql_delete) === TRUE) {
                echo "success"; // Return success for JavaScript handling
            } else {
                echo "Error: " . $conn->error; // Return error message
            }
        } else {
            echo "Error: Unauthorized action or comment not found.";
        }
    } else {
        echo "Error: Missing required parameters.";
    }
} else {
    echo "Invalid request method.";
}
?>
