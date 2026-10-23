<?php
require_once "db_connect.php"; // Ensure your database connection file is included

session_start();
// Check if a session ID is set and assign it to $userID
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;

// Get the course ID from the query string
$courseId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the course details
$sql = "SELECT * FROM course WHERE courseID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $courseId);
$stmt->execute();
$result = $stmt->get_result();

$course = $result->fetch_assoc();

// Fetch the syllabus or lectures for the course
$sqlLectures = "SELECT * FROM lecture WHERE courseID = ?";
$stmt = $conn->prepare($sqlLectures);
$stmt->bind_param("i", $courseId);
$stmt->execute();
$lecturesResult = $stmt->get_result();

$lectures = [];
while ($row = $lecturesResult->fetch_assoc()) {
    $lectures[] = $row;
}

// Handle course deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course'])) {
    // Delete associated lectures first
    $sqlDeleteLectures = "DELETE FROM lecture WHERE courseID = ?";
    $stmt = $conn->prepare($sqlDeleteLectures);
    $stmt->bind_param("i", $courseId);
    $stmt->execute();

    // Delete the course
    $sqlDeleteCourse = "DELETE FROM course WHERE courseID = ?";
    $stmt = $conn->prepare($sqlDeleteCourse);
    $stmt->bind_param("i", $courseId);
    if ($stmt->execute()) {
        // Redirect to the courses list with a success message
        header("Location: adminCourses.php");
        exit;
    } else {
        $error = "Failed to delete the course. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['courseTitle'] ?? 'Course Not Found'); ?></title>
    <link rel="stylesheet" href="../Assets/CSS/course1.css">
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
<main>
    <?php if ($course): ?>
        <section id="course-info">
            <div class="course-info">
                <img src="<?php echo htmlspecialchars($course['courseImage']); ?>" alt="Course Image" class="course-image">
                <div class="course-details">
                    <h3><?php echo htmlspecialchars($course['courseTitle']); ?></h3>
                    <p><strong>By:</strong> <?php echo htmlspecialchars($course['teacherName']); ?></p>
                    <p><strong>Language:</strong> <?php echo htmlspecialchars($course['language']); ?></p>
                    <p><strong>Price:</strong> <?php echo htmlspecialchars($course['coursePrice']); ?> DZD</p>
                </div>
                <a href="adminLectures.php?id=<?php echo htmlspecialchars($course['courseID']); ?>" class="button cart-btn">Explore lectures</a>
            </div>
        </section>

        <section id="course-description">
            <h2>Course Description</h2>
            <p><?php echo nl2br(htmlspecialchars($course['courseDesc'])); ?></p>
        </section>

        <section id="syllabus">
            <h2>Syllabus</h2>
            <ul>
                <?php foreach ($lectures as $lecture): ?>
                    <li><?php echo htmlspecialchars($lecture['lectTitle']); ?></li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- Delete Course Section -->
        <section id="delete-course">
            <form method="POST">
                <button type="submit" name="delete_course" class="button cart-btn" style="background-color:red;" >Delete Course</button>
            </form>
            <?php if (isset($error)): ?>
                <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        </section>
    <?php else: ?>
        <section id="course-not-found">
            <h2>Course Not Found</h2>
            <p>The course you're looking for does not exist or may have been removed.</p>
        </section>
    <?php endif; ?>
</main>

</body>
</html>
