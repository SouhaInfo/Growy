<?php
// Start the session
session_start();
require_once "db_connect.php";

// Check if the user is logged in and get the userID
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
} else {
    // Redirect to login if no user session is found
    header("Location: login.php");
    exit;
}

// Initialize a message variable for feedback
$message = "";

// Check if the courseID is set for removal
if (isset($_GET['remove_courseID']) && isset($_GET['cartID'])) {
    $courseIDToRemove = $_GET['remove_courseID'];
    $cartID = $_GET['cartID'];

    // Fetch the price of the course to remove
    $sqlGetCoursePrice = "SELECT coursePrice FROM course WHERE courseID = ?";
    $stmt = $conn->prepare($sqlGetCoursePrice);
    $stmt->bind_param("i", $courseIDToRemove);
    $stmt->execute();
    $result = $stmt->get_result();

    $coursePrice = 0;
    if ($result->num_rows > 0) {
        $courseData = $result->fetch_assoc();
        $coursePrice = $courseData['coursePrice'];
    }

    // Delete the course from the 'cartcourse' table
    $sqlDeleteCourse = "DELETE FROM cartcourse WHERE cartID = ? AND courseID = ?";
    $stmt = $conn->prepare($sqlDeleteCourse);
    $stmt->bind_param("ii", $cartID, $courseIDToRemove);
    $stmt->execute();

    // Update the total sum and numItems in the cart
    $sqlUpdateCart = "UPDATE cart 
                      SET totalSum = totalSum - ?, numItems = numItems - 1 
                      WHERE cartID = ?";
    $stmtUpdateCart = $conn->prepare($sqlUpdateCart);
    $stmtUpdateCart->bind_param("di", $coursePrice, $cartID);
    $stmtUpdateCart->execute();

    // Set a success message for course removal
    $message = "Course removed successfully.";

    // Redirect back to the cart page after removal with a message
    header("Location: cart.php?message=" . urlencode($message));
    exit;
}

// Fetch the maximum cartID for the logged-in user
$sqlGetCartID = "SELECT MAX(cartID) AS cartID FROM cart WHERE userID = ?";
$stmt = $conn->prepare($sqlGetCartID);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

// If a cartID is found, assign it
$cartID = 0; // Default to 0 if no cart found
if ($result->num_rows > 0) {
    $cartData = $result->fetch_assoc();
    $cartID = $cartData['cartID'];
}

// Fetch the courses in the cart from the 'cartcourse' table if cartID exists
if ($cartID > 0) {
    $sqlGetCartCourses = "SELECT cc.courseID, c.courseTitle, c.courseImage, c.coursePrice
                          FROM cartcourse cc
                          INNER JOIN course c ON cc.courseID = c.courseID
                          WHERE cc.cartID = ?";
    $stmt = $conn->prepare($sqlGetCartCourses);
    $stmt->bind_param("i", $cartID);
    $stmt->execute();
    $cartCourses = $stmt->get_result();
} else {
    // Handle the case when no cart is found for the user
    $cartCourses = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Assets/CSS/header.css">
    <link rel="stylesheet" href="/Assets/CSS/cart.css">
    <link rel="stylesheet" href="/Assets/CSS/Footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Your Cart</title>
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
            include 'headerlogin.php'; 
        }
        ?>
</header>

    <div class="small-container cart-page">
        <?php if ($cartCourses && $cartCourses->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Course</th>
                    <th>Price</th>
                </tr>

                <?php while ($course = $cartCourses->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <div class="cart-info">
                                <img src="<?php echo htmlspecialchars($course['courseImage']); ?>" alt="Course Image">
                                <div>
                                    <p><?php echo htmlspecialchars($course['courseTitle']); ?></p>
                                    <small>Price: <?php echo htmlspecialchars($course['coursePrice']); ?> DZD</small>
                                    <br>
                                    <!-- Link to remove the course -->
                                    <a href="cart.php?remove_courseID=<?php echo $course['courseID']; ?>&cartID=<?php echo $cartID; ?>" class="remove-item">Remove</a>
                                </div>
                            </div>
                        </td>
                        <td class="cart-price"><?php echo htmlspecialchars($course['coursePrice']); ?> DZD</td>
                    </tr>
                <?php endwhile; ?>

            </table>

            <div class="total-price">
                <table>
                    <tr>
                        <td>Total</td>
                        <td>
                            <?php 
                            // Calculate total price
                            $totalPrice = 0;
                            $cartCourses->data_seek(0); // Reset result pointer
                            while ($course = $cartCourses->fetch_assoc()) {
                                $totalPrice += $course['coursePrice'];
                            }
                            echo $totalPrice . " DZD";
                            ?>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Pay Button Form -->
            <form action="epayment.php" method="POST">
                <input type="hidden" name="cartID" value="<?php echo $cartID ?>">
                <button type="submit" class="paybtn">Pay</button>
            </form>
        <?php else: ?>
            <!-- Empty cart message -->
            <div style="text-align: center; margin-top: 20%; font-family: Arial, sans-serif;">
                <p>Your cart is empty. <a href="courses.php" style="color: green;">Explore our courses</a> and start learning today!</p>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <?php include 'Footer_LI.php'; ?>
    </footer>

</body>
</html>
