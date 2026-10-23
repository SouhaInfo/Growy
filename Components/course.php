<?php

require_once "db_connect.php"; // Ensure your database connection file is included
session_start();
// Check if a session ID is set and assign it to $userID
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
// Get the course ID from the query string
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $courseId = intval($_POST['id']);
} else {
    $courseId = 0; // Or handle as an error
}
// Fetch the course details
$sql = "SELECT * FROM course WHERE courseID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $courseId);
$stmt->execute();
$courseResult = $stmt->get_result();
$course = $courseResult->fetch_assoc();

// Fetch all lectures for the course
$sqlLectures = "SELECT * FROM lecture WHERE courseID = ? ORDER BY lectID";
$stmt = $conn->prepare($sqlLectures);
$stmt->bind_param("i", $courseId);
$stmt->execute();
$lecturesResult = $stmt->get_result();

$lectures = [];
while ($row = $lecturesResult->fetch_assoc()) {
    $lectures[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['courseTitle']); ?></title>
    <link rel="stylesheet" href="../Assets/CSS/course1f.css">
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
            header("Location: login.php");
            exit; // Always include exit after a header redirect
        }else{
            include 'HeaderLogIn.php'; 
        }
        ?>
</header>

<main id="course-page">


    <!-- Course Title -->
    <h1 class="course-title"><?php echo htmlspecialchars($course['courseTitle']); ?></h1>

    <!-- Lessons Section -->
    <section class="lessons-section">
        <?php if ($lectures): ?>
            <!-- Loop through the lectures and display them -->
            <?php foreach ($lectures as $lecture): ?>
                <a href="Lecture.php?courseID=<?php echo $courseId; ?>&lectID=<?php echo $lecture['lectID']; ?>" class="lesson-box"> <!-- Link to each lesson -->
                    <img src="<?php echo htmlspecialchars($lecture['lectImage']); ?>" alt="Lesson Image" class="lesson-image">
                    <div class="lesson-info">
                        <h2 class="lesson-title"><?php echo htmlspecialchars($lecture['lectTitle']); ?></h2>
                        <p class="lesson-duration">Duration: <?php echo htmlspecialchars($lecture['duration']); ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No lectures available for this course.</p>
        <?php endif; ?>
    </section>
</main>

</body>
</html>