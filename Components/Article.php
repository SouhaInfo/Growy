<?php
require_once 'db_connect.php';
session_start(); // Start session to check login status

if (!isset($_SESSION['userID'])) {
    $_SESSION['userID'] = null; // For non-logged-in users
}

$userID= $_SESSION['userID'];
$row = null;
$res_comments = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['conID'])) {
    // Sanitize the input to prevent SQL injection
    $conID = mysqli_real_escape_string($conn, $_POST['conID']);

    // Fetch the article details
    $sql = "SELECT contribution.*, user.fullname
            FROM contribution
            JOIN user ON contribution.userID = user.userID
            WHERE contribution.conID = '$conID'";

    $res = $conn->query($sql); // Execute query to fetch one article record only 

    // Check if the result has data
    if ($res->num_rows > 0) {
        $row = mysqli_fetch_assoc($res); 
    }

    // Fetch the comments for this article
    $sql_comments = "SELECT 
                        comment.commentID, 
                        comment.commentContent, 
                        comment.PostDate, 
                        user.fullname, 
                        user.profilePhoto,
                        user.userID 
                     FROM 
                        comment
                     JOIN 
                        user ON comment.userID = user.userID 
                     WHERE 
                        comment.conID = '$conID' 
                     ORDER BY 
                        comment.PostDate DESC LIMIT 40"; // Limit the number of displayed comments

    $res_comments = $conn->query($sql_comments); // Execute query to fetch comments

    // Check for query errors
    if ($res_comments === false) {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/CSS/Footer.css">
    <title><?php echo isset($row["conTitle"]) ? htmlspecialchars($row["conTitle"]) : 'Article Not Found'; ?></title>
    <link rel="stylesheet" href="../Assets/CSS/Article.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
<header>
        <?php 
        if ($userID === null) {
            // If userID is null, include 'header.php'
            include 'header.php'; 
        } else {
            // If userID is set, include 'headerlogin.php'
            include 'headerlogin.php'; 
        }
        ?>
    </header>
    <section class="article-header">
    <video autoplay muted loop class="bg-video">
        <source src="../Assets/Introducing Our Parenting Videos_  Raising Caring, Courageous Kids.mp4" type="video/mp4">
    </video>
        <h1><?php echo isset($row["conTitle"]) ? htmlspecialchars($row["conTitle"]) : 'Article Not Found'; ?></h1>
        <p> <?php echo isset($row["fullname"]) ? htmlspecialchars($row["fullname"]) : ''; ?> | Published on: <?php echo isset($row["postDate"]) ? htmlspecialchars($row["postDate"]) : ''; ?></p>
    </section>

    <main>
      

        <article id="print-area">
            <?php 
            if (isset($row["conHtml"])) {
                echo $row["conHtml"]; // Output the HTML content of the article
            } else{
                echo"error"; 
            }
            ?>
        </article>
    </main>

    <section class="comments">
    <form class="comment-form" id="commentForm" method="POST" action="">
        <?php if (isset($_SESSION['userID'])): ?>
            <label for="comment">Comment: <span id="charCount">0/1000</span></label>
            <textarea id="comment" name="comment-con" rows="4" maxlength="1000" required></textarea>
            <input type="hidden" name="conID" value="<?php echo $conID; ?>">
            <input type="hidden" name="userID" value="<?php echo $_SESSION['userID']; ?>">
            <button type="submit">Submit Comment</button>
        <?php else: ?>
            <p>You need to <a href="login.php">log in</a> to submit your comment.</p>
        <?php endif; ?>
    </form>

    <div class="comment-list" id="commentList">
        <?php 
        if ($res_comments && $res_comments->num_rows > 0): 
            while ($row_comment = mysqli_fetch_assoc($res_comments)): ?>
                <div class="comment" id="comment-<?php echo $row_comment['commentID']; ?>">
                    <img src="<?php echo htmlspecialchars($row_comment['profilePhoto']); ?>" alt="User Photo" class="profile-picture">
                    <strong><?php echo htmlspecialchars($row_comment['fullname']); ?>:</strong>
                    &nbsp; <?php echo htmlspecialchars($row_comment['commentContent']); ?>
                    
                    <?php if (isset($_SESSION['userID']) && $_SESSION['userID'] == $row_comment['userID']): ?>
                        <div class="menu-dots">
                            <i class="fa fa-ellipsis-v"></i>
                            <div class="dropdown-menu">
                                <form method="POST" class="delete-comment-form" data-comment-id="<?php echo $row_comment['commentID']; ?>">
                                    <button type="button" class="delete-comment-button" data-comment-id="<?php echo $row_comment['commentID']; ?>">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-comments">No comments available.</p>
        <?php endif; ?>
    </div>
</section>


    <footer>
        <?php include 'Footer.php'; ?>
    </footer>

    <script>
       // Real-time Comment Submission
$('#commentForm').on('submit', function (e) {
    e.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        type: 'POST',
        url: 'add_comment.php',
        data: formData,
        success: function (response) {
            // Add the new comment dynamically
            $('#commentList').prepend(response);

            // Clear the input field
            $('#comment').val('');

            // Remove the "No comments available" message if it exists
            $('#commentList p.no-comments').remove();
        },
        error: function () {
            alert('Error occurred while submitting the comment.');
        },
    });
});

// Real-time Comment Deletion
$(document).on('click', '.delete-comment-button', function () {
    var commentID = $(this).data('comment-id');

    $.ajax({
        type: 'POST',
        url: 'delete_comment.php',
        data: { commentID: commentID },
        success: function () {
            // Remove the comment from the DOM
            $('#comment-' + commentID).remove();

            // Check if there are no more comments
            if ($('#commentList .comment').length === 0) {
                // Add the "No comments available" message
                $('#commentList').html('<p class="no-comments">No comments available.</p>');
            }
        },
        error: function () {
            alert('Error occurred while deleting the comment.');
        },
    });
});
    </script>
    <script src="../Assets/JS/count.js"> </script>
    <script src="../Assets/JS/video.js"></script>
    <script src="../Assets/JS/control.js"></script>
</body>
</html>
