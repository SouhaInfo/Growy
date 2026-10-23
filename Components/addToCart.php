<?php
require_once "db_connect.php"; // Ensure your database connection file is included

session_start();

// Check if userID is set in session
$userID = isset($_SESSION['userID']) ? intval($_SESSION['userID']) : null;
$message = "";

// Check if the course ID is passed via GET (URL parameter)
if (isset($_GET['id']) && $userID !== null) {
    $courseId = intval($_GET['id']);

    // Check if the course is already in the user's cart
    $sqlCheck = "SELECT * FROM cartcourse WHERE cartID = (SELECT MAX(cartID) FROM cart WHERE userID = ?) AND courseID = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("ii", $userID, $courseId);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows === 0) {
        // Fetch the course price
        $sqlGetPrice = "SELECT coursePrice FROM course WHERE courseID = ?";
        $stmtGetPrice = $conn->prepare($sqlGetPrice);
        $stmtGetPrice->bind_param("i", $courseId);
        $stmtGetPrice->execute();
        $resultPrice = $stmtGetPrice->get_result();
        $coursePrice = 0;
        if ($resultPrice->num_rows > 0) {
            $coursePrice = $resultPrice->fetch_assoc()['coursePrice'];
        }

        // Add the course to the cart
        $sqlAdd = "INSERT INTO cartcourse (cartID, courseID) VALUES ((SELECT MAX(cartID) FROM cart WHERE userID = ?), ?)";
        $stmtAdd = $conn->prepare($sqlAdd);
        $stmtAdd->bind_param("ii", $userID, $courseId);
        $stmtAdd->execute();
        
        // Update the cart with total sum and num items
        $sqlUpdateCart = "UPDATE cart SET totalSum = totalSum + ?, numItems = numItems + 1 WHERE userID = ?";
        $stmtUpdateCart = $conn->prepare($sqlUpdateCart);
        $stmtUpdateCart->bind_param("di", $coursePrice, $userID);
        $stmtUpdateCart->execute();

        $message = "Course added successfully!";
    } else {
        $message = "Course is already in your cart.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/CSS/course1.css">
    <title>Course Added</title>
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
        <p><?php echo htmlspecialchars($message); ?></p>
        <p>Go back to
        <a href="courses.php" style="color: green;">list of courses</a></p>
    </main>
</body>
</html>
