<?php
require_once "db_connect.php"; // Ensure your database connection file is included

session_start();

// Check if userID is set in session
$userID = isset($_SESSION['userID']) ? intval($_SESSION['userID']) : null;

$course = null;
$lectures = [];
$message = "";

// Handle the POST request (fetch course details and lectures)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseId = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // Fetch course details
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

    while ($row = $lecturesResult->fetch_assoc()) {
        $lectures[] = $row;
    }
}

// Check if course is in cart
if ($userID !== null && isset($course)) {
    // Check if the course is already in the user's cart
    $sqlCheck = "SELECT * FROM cartcourse WHERE cartID = (SELECT MAX(cartID) FROM cart WHERE userID = ?) AND courseID = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("ii", $userID, $course['courseID']);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $isInCart = $resultCheck->num_rows > 0;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['courseTitle'] ?? 'Course Not Found'); ?></title>
    <link rel="stylesheet" href="../Assets/CSS/course1.css">
    <script type="text/javascript">
        function addToCart(courseId) {
            // Redirect to addToCart.php with the courseId as a URL parameter
            window.location.href = 'addToCart.php?id=' + courseId;
        }
    </script>
</head>
<body>
<header>
    <?php 
    if ($userID === null) {
        include 'header.php'; 
    } else {
        include 'headerlogin.php'; 
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
                
                <?php if ($userID !== null): ?>
                    <?php if ($isInCart): ?>
                        <!-- If the course is already in the cart, show this message -->
                        <span class="cart-message" style="color: green">Course already added to cart</span>
                    <?php else: ?>
                        <!-- If the course is not in the cart, show Add to cart button -->
                        <button onclick="addToCart(<?php echo $course['courseID']; ?>)" class="button cart-btn">+ Add to cart</button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>

        <?php if (!empty($message)): ?>
            <p style="color: green; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

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
    <?php endif; ?>
</main>

<footer>
<footer>
    <?php include 'Footer.php'; ?>
    </footer>
</footer>

</body>
</html>
