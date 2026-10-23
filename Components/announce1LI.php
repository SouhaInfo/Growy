<?php
// Include the database connection file
include '../Components/db_connect.php';

// Check if 'id' is passed in the URL
if (isset($_GET['annID']) && is_numeric($_GET['annID'])) {
    $annID = $_GET['annID'];

    // Fetch the announcement details along with the user's information using a JOIN
    $sql = "SELECT 
                announcement.annTitle, 
                announcement.annImage, 
                announcement.annType, 
                announcement.avaDatetime, 
                announcement.location, 
                announcement.annPrice, 
                announcement.annDesc, 
                user.fullname, 
                user.username, 
                announcement.annEmail, 
                announcement.annPhone 
            FROM 
                announcement 
            JOIN 
                user 
            ON 
                announcement.userID = user.userID
            WHERE 
                announcement.annID = ?";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $annID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the announcement data
        $announcement = $result->fetch_assoc();
    } else {
        // Redirect if no announcement is found
        echo 'error';
        header("Location: announcements.php");
        exit();
    }
} else {
    // Redirect if 'id' is not set or invalid
    echo 'error';
    header("Location: announcements.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement Details</title>
    <link rel="stylesheet" href="../Assets/CSS/announce.css">
</head>
<header> 
    <?php include 'headerLogIn.php'; ?>
</header>
<body>
    <div class="container">
        <div class="announcement-header">
            <span class="Title"><?= htmlspecialchars($announcement['annTitle']) ?></span>
            <span class="User">by: <?= htmlspecialchars($announcement['username']) ?></span>
        </div>
        <div class="announcement-img">
            <img src="<?= htmlspecialchars($announcement['annImage']) ?>" alt="<?= htmlspecialchars($announcement['annTitle']) ?>">
        </div>
        <div class="announcement-details">
            <div class="details-row">
                <label>Announcement Type:</label>
                <span><?= htmlspecialchars($announcement['annType']) ?></span>
            </div>
            <div class="details-row">
                <label>Full Name:</label>
                <span><?= htmlspecialchars($announcement['fullname']) ?></span>
            </div>
            <div class="details-row">
                <label>Date:</label>
                <span><?= htmlspecialchars($announcement['avaDatetime']) ?></span>
            </div>
            <div class="details-row">
                <label>Location:</label>
                <span><?= htmlspecialchars($announcement['location']) ?></span>
            </div>
            <div class="details-row">
                <label>Price:</label>
                <span><?= htmlspecialchars($announcement['annPrice']) ?></span>
            </div>
        </div>

        <div class="announcement-description">
            <h2>Description</h2>
            <p><?= nl2br(htmlspecialchars($announcement['annDesc'])) ?></p>
        </div>

        <div class="contact-info">
            <h2>Contact Information</h2>
            <p>Email: <a href="mailto:<?= htmlspecialchars($announcement['annEmail']) ?>"><?= htmlspecialchars($announcement['annEmail']) ?></a></p>
            <p>Phone: <?= htmlspecialchars($announcement['annPhone']) ?></p>
        </div>  

        <div class="last">
            <a href="../Components/announcements.php">
                <button class="back-button">See Announces</button>
            </a>
            <a href="../Components/urannouncement.php">
                <button class="add-announcement-btn">Add Yours</button>
            </a>
        </div>
    </div>

    <div class="copyright">
        <br>
        <p>Growy &copy; 2024 - All rights reserved</p>
        <br>
        <br>
    </div>
</body>
</html>
