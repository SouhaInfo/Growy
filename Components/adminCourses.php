<?php
require_once "db_connect.php";
session_start();
// Check if a session ID is set and assign it to $userID
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;

// Fetch all courses from the database
$sql = "SELECT * FROM course";
$all_course = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/CSS/admincourses.css">
    <title>Courses Page</title>
</head>
<body>
    <!-- Header Section -->
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
        <!-- Main Content Section -->
        <div class="courses-container">
            <h2>All Courses</h2>
            <br>
            <a href="adminpostcourse.php" class="button cart-btn">Add new course</a>
            <br>
            <br>
            <br>
            <div class="courses-grid">
                <?php while ($row = mysqli_fetch_assoc($all_course)): ?>
                    <div class="course-card">
                        <img src="<?php echo htmlspecialchars($row["courseImage"]); ?>" class="course-image" alt="Course Image">
                        <h3><?php echo htmlspecialchars($row["courseTitle"]); ?></h3>
                        <p>By: <?php echo htmlspecialchars($row["teacherName"]); ?></p>
                        <p>Language: <?php echo htmlspecialchars($row["language"]); ?></p>
                        <p><b>Price: <?php echo htmlspecialchars($row["coursePrice"]); ?> DZD</b></p>
                        <a href="admincoursecontent.php?id=<?php echo $row['courseID']; ?>" class="button explore-btn">Explore Course</a>
                    </div>
                <?php endwhile; ?>
            </div>
            <br>
            
        </div>
    </main>
</body>
</html>
