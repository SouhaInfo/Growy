<?php
require_once 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required fields are set
    if (isset($_POST['conID'], $_POST['userID'], $_POST['comment-con'])) {
        $conID = mysqli_real_escape_string($conn, $_POST['conID']);
        $userID = mysqli_real_escape_string($conn, $_POST['userID']);
        $commentContent = mysqli_real_escape_string($conn, $_POST['comment-con']);
     

        // Insert the comment into the database
        $sql = "INSERT INTO comment (conID, userID, commentContent, PostDate) 
                VALUES ('$conID', '$userID', '$commentContent', NOW())";

        if ($conn->query($sql) === TRUE) {
            // Retrieve the newly added comment with user info for dynamic display
            $commentID = $conn->insert_id;
            $sql_fetch = "SELECT 
                              comment.commentID, 
                              comment.commentContent, 
                              comment.PostDate, 
                              user.username, 
                              user.profilePhoto,
                              user.userID 
                          FROM 
                              comment
                          JOIN 
                              user ON comment.userID = user.userID 
                          WHERE 
                              comment.commentID = '$commentID'";

            $res = $conn->query($sql_fetch);
            if ($res->num_rows > 0) {
                $row = $res->fetch_assoc();
                // Output the comment in HTML format with dropdown menu
                echo '<div class="comment" id="comment-' . $row['commentID'] . '">
                      <p>
                      <img src="' . htmlspecialchars($row['profilePhoto']) . '" alt="User Photo" class="profile-picture">
                      <strong>' . htmlspecialchars($row['username']) . ':</strong> ' . htmlspecialchars($row['commentContent']) . '</p>';
                if ($_SESSION['userID'] == $row['userID']) {
                    echo '<div class="menu-dots">
                            <i class="fa fa-ellipsis-v"></i>
                            <div class="dropdown-menu">
                                <form method="POST" class="delete-comment-form" data-comment-id="' . $row['commentID'] . '">
                                    <button type="button" class="delete-comment-button" data-comment-id="' . $row['commentID'] . '">Delete</button>
                                </form>
                            </div>
                          </div>';
                }
                echo '</div>';
            }
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error: Missing required fields.";
    }
} else {
    echo "Invalid request method.";
}
?>
