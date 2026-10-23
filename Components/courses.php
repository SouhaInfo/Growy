<?php
require_once "db_connect.php";

session_start();
// Check if userID is set in session
$userID = isset($_SESSION['userID']) ? intval($_SESSION['userID']) : null; // If not set, use null


// Fetch user's courses (My Courses)
$sqlMyCourses = "SELECT c.* FROM course c 
                 INNER JOIN mycourses mc ON c.courseID = mc.courseID 
                 WHERE mc.userID = ?";
$stmt = $conn->prepare($sqlMyCourses);
$stmt->bind_param("i", $userID);
$stmt->execute();
$myCourses = $stmt->get_result();

// Fetch suggested courses (excluding My Courses)
$sqlSuggestedCourses = "SELECT * FROM course WHERE courseID NOT IN 
                        (SELECT courseID FROM mycourses WHERE userID = ?)";
$stmt = $conn->prepare($sqlSuggestedCourses);
$stmt->bind_param("i", $userID);
$stmt->execute();
$suggestedCourses = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/CSS/courses.css">
    <title>Courses Page</title>
</head>
<body>
<header>
        <?php 
        if ($userID === null) {
            include 'Header.php'; 
        } elseif($userID<=60 && $userID>=57) {
            include 'adminHeader.php'; 
        }else{
            include 'headerlogin.php'; 
        }
        ?>
        
    </header>
    <main>
        <!-- My Courses Section -->
        <?php if ($userID!= null && $userID!= 57 && $userID!= 58 && $userID!= 59 && $userID!= 60): ?>
        <div class="courses-container">
            <h3 style="color:black">My Courses</h3>
            <br>
            <div class="courses-grid">
                <?php if ($myCourses->num_rows > 0): ?>
                    <?php while ($row = $myCourses->fetch_assoc()): ?>
                        <div class="course-card">
                            <img src="<?php echo htmlspecialchars($row["courseImage"]); ?>" class="course-image" alt="Course Image">
                            <h3><?php echo htmlspecialchars($row["courseTitle"]); ?></h3>
                            <p>By: <?php echo htmlspecialchars($row["teacherName"]); ?></p>
                            <p>Language: <?php echo htmlspecialchars($row["language"]); ?></p>
                            <p><b>Price: <?php echo htmlspecialchars($row["coursePrice"]); ?> DZD</b></p>
                            <form method="POST" action="course.php">
                                <input type="hidden" name="id" value="<?php echo $row['courseID']; ?>">
                                <button type="submit" class="button explore-btn">Continue Learning</button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>You are currently not enrolled in any courses.</p>
                <?php endif; ?>
            </div>
            <br>
        </div>
        <?php endif; ?>
        <!-- Suggested Courses Section -->
        <div class="courses-container">
            <h3 style="color:black">Suggested Courses</h3>
            <br>
            <div class="courses-grid">
                <?php if ($suggestedCourses->num_rows > 0): ?>
                    <?php while ($row = $suggestedCourses->fetch_assoc()): ?>
                        <div class="course-card">
                            <img src="<?php echo htmlspecialchars($row["courseImage"]); ?>" class="course-image" alt="Course Image">
                            <h3><?php echo htmlspecialchars($row["courseTitle"]); ?></h3>
                            <p>By: <?php echo htmlspecialchars($row["teacherName"]); ?></p>
                            <p>Language: <?php echo htmlspecialchars($row["language"]); ?></p>
                            <p><b>Price: <?php echo htmlspecialchars($row["coursePrice"]); ?> DZD</b></p>
                            <form method="POST" action="coursecontent.php">
                                <input type="hidden" name="id" value="<?php echo $row['courseID']; ?>">
                                <button type="submit" class="button explore-btn">Explore Course</button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No courses are available right now.</p>
                <?php endif; ?>
            </div>
            <br>
        </div>
    </main>

    <footer>
    <?php include 'Footer.php'; ?>
    </footer>
</body>
</html>
