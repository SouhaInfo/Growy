<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/CSS/courses.css">
    <title>Courses Page</title>
</head>
<body>

    <!-- Header Section -->
    <header>
        <?php include 'HeaderLogIn.php'; ?>
    </header>

    <!-- Main Content Section -->
    <div class="courses-container">
        <!-- My Courses Section -->
        <div class="courses-row">
            <h2>My Courses</h2>
            <div class="courses-grid">
                <!-- Course 1 -->
                <div class="course-card">
                    <img src="../Assets/images/course11.jpg" alt="Course 1" class="course-image">
                    <h3>Home sweet home</h3>
                    <p>By: Belal Assaad</p>
                    <a href="course1f.php" class="button explore-btn">Continue Learning</a>
                </div>
            </div>
        </div>

        <!-- Suggested Courses Section -->
        <div class="courses-row">
            <h2>Suggested Courses</h2>
            <div class="courses-grid">
                <!-- Course 2 -->
                <div class="course-card">
                <img src="../Assets/images/course2.jpg" alt="Course 2" class="course-image">
                    <h3>تربية الأبناء: الدليل الكامل</h3>
                    <p>By: Dr. Muhammed Kheir E-Sheaal</p>
                    <p>Language: Arabic</p>
                    <p><b>Price: 6000.00 DZD</b></p>
                    <div class="button-container">
                       <a href="course2LI.php" class="button explore-btn">Explore</a>
                       <a href="#" class="button cart-btn">Add to Cart</a>
                     </div>
                </div>
                <!-- Course 3 -->
                <div class="course-card">
                    <img src="../Assets/images/course3.jpg" alt="Course 3" class="course-image">
                    <h3>Dealing with kids with special needs</h3>
                    <p>By: Live on purpose TV</p>
                    <p>Language: English</p>
                    <p><b>Price: 1000.00 DZD</b></p>
                    <div class="button-container">
                       <a href="course3LI.php" class="button explore-btn">Explore</a>
                       <a href="#" class="button
                     </div>
                </div>
                <!-- Course 4 -->
                <div class="course-card">
                    <img src="../Assets/images/course4.jpg" alt="Course 4" class="course-image">
                    <h3>How to Get Your Children to Listen Without Yelling?</h3>
                    <p>By: Live on Purpose TV</p>
                    <p>Language: English</p>
                    <p><b>Price: 1500.0 cart-btn">Add to Cart</a>0 DZD</b></p>
                    <div class="button-container">
                       <a href="course4LI.php" class="button explore-btn">Explore</a>
                       <a href="#" class="button cart-btn">Add to Cart</a>
                     </div>
                </div>
                <!-- Course 5 -->
                <div class="course-card">
                    <img src="../Assets/images/course5.jpg" alt="Course 5" class="course-image">
                    <h3>Babycare Basics</h3>
                    <p>By: Haog Health</p>
                    <p>Language: English</p>
                    <p><b>Price: 1200.00 DZD</b></p>
                    <div class="button-container">
                       <a href="course5LI.php" class="button explore-btn">Explore</a>
                       <a href="" class="button cart-btn">Add to Cart</a>
                     </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <?php include 'Footer_LI.php'; ?>
    </footer>

</body>
</html>
