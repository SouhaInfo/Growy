<?php
require_once "db_connect.php"; // Ensure your database connection file is included
session_start();
// Check if a session ID is set and assign it to $userID
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
// Get the course ID and lecture ID from the query string
$courseId = isset($_GET['courseID']) ? intval($_GET['courseID']) : 0;
$lectId = isset($_GET['lectID']) ? intval($_GET['lectID']) : 0;

// Fetch the course details
$sql = "SELECT * FROM course WHERE courseID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $courseId);
$stmt->execute();
$courseResult = $stmt->get_result();
$course = $courseResult->fetch_assoc();

// Fetch the lecture details
$sqlLecture = "SELECT * FROM lecture WHERE lectID = ? AND courseID = ?";
$stmt = $conn->prepare($sqlLecture);
$stmt->bind_param("ii", $lectId, $courseId);
$stmt->execute();
$lectureResult = $stmt->get_result();
$lecture = $lectureResult->fetch_assoc();

// Function to convert YouTube URL to embed format
function convertToEmbedURL($url) {
    // Check if the URL is a YouTube URL
    if (preg_match('/https:\/\/www\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
        // Return the YouTube embed URL
        return "https://www.youtube.com/embed/" . $matches[1];
    }
    return $url;  // Return the original URL if it's not a YouTube URL
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['courseTitle']); ?> - <?php echo htmlspecialchars($lecture['lectTitle']); ?></title>
    <link rel="stylesheet" href="../Assets/CSS/lecture.css">
</head>
<body>
<header>
        <?php 
        if ($userID == Null) {
            // If userID is null, include 'header.php'
            if ($userID == null) {
                header("Location: login.php");
                exit; // Always include exit after a header redirect
            }
            
        } elseif($userID<=60 && $userID>=57) {
            include 'adminHeader.php'; 
        }else{
            header("Location: login.php");
                exit; // Always include exit after a header redirect
        }
        ?>
</header>

<main id="lecture-page">
    <!-- Course Title -->
    <h1 class="lecture-title"><?php echo htmlspecialchars($lecture['lectTitle']); ?></h1>

    <!-- Lecture Description -->
    <p class="lecture-description"><?php echo nl2br(htmlspecialchars($lecture['lectDesc'])); ?></p>

    <!-- Lecture Video -->
    <div class="lecture-video">
        <iframe 
            src="<?php echo convertToEmbedURL(htmlspecialchars($lecture['lectLink'])); ?>" 
            frameborder="0" 
            allowfullscreen>
        </iframe>
    </div>

    <div class="lecture-footer">
       <div class="previous-lecture">
          <a href="adminLectures.php?id=<?php echo htmlspecialchars($course['courseID']); ?>" class="previous-lecture-link">Lectures list</a>
        </div>
    </div>
</main>

</body>
</html>
