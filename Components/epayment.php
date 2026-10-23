<?php

require_once "db_connect.php";

session_start();
$userID = isset($_SESSION['userID']) ? intval($_SESSION['userID']) : null; // If not set, use null

// Check if userID is null or is one of the restricted values
if ($userID === null || in_array($userID, [57, 58, 59, 60])) {
    header("Location: login.php"); // Redirect to login page
    exit(); // Ensure the script stops executing
}

$cartID = isset($_POST["cartID"]) ? intval($_POST["cartID"]) : 10;

// Initialize variables
$firstname = $surname = $phonenum = $email = $cardNum = $cvv = $expMonth = $expYear = "";
$errors = [];
$successMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    // Validate Firstname
    if (empty($_POST["firstname"])) {
        $errors["firstname"] = "Firstname is required.";
    } elseif (!preg_match("/^[A-Za-z ]+$/", $_POST["firstname"])) {
        $errors["firstname"] = "Firstname can only include letters and spaces.";
    } else {
        $firstname = htmlspecialchars($_POST["firstname"]);
    }

    // Validate Surname
    if (empty($_POST["surname"])) {
        $errors["surname"] = "Surname is required.";
    } elseif (!preg_match("/^[A-Za-z. ]+$/", $_POST["surname"])) {
        $errors["surname"] = "Surname can only include letters and spaces.";
    } else {
        $surname = htmlspecialchars($_POST["surname"]);
    }

    // Validate Phone Number
    if (empty($_POST["phonenum"])) {
        $errors["phonenum"] = "Phone number is required.";
    } elseif (!preg_match("/^([0]{1}[5-7]{1}[0-9]{8})$/", $_POST["phonenum"])) {
        $errors["phonenum"] = "Phone number must be 10 digits.";
    } else {
        $phonenum = htmlspecialchars($_POST["phonenum"]);
    }

    // Validate Email
    if (empty($_POST["email"])) {
        $errors["email"] = "Email is required.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format.";
    } else {
        $email = htmlspecialchars($_POST["email"]);
    }

    // Validate Card Number
    if (empty($_POST["cardNum"])) {
        $errors["cardNum"] = "Card number is required.";
    } elseif (!preg_match("/^\d{16}$/", str_replace(" ", "", $_POST["cardNum"]))) {
        $errors["cardNum"] = "Card number must be 16 digits.";
    } else {
        $cardNum = htmlspecialchars($_POST["cardNum"]);
    }

    // Validate CVV
    if (empty($_POST["cvv"])) {
        $errors["cvv"] = "CVV is required.";
    } elseif (!preg_match("/^\d{3}$/", $_POST["cvv"])) {
        $errors["cvv"] = "CVV must be 3 digits.";
    } else {
        $cvv = htmlspecialchars($_POST["cvv"]);
    }

    // Validate Expiry Month and Year
    if (empty($_POST["expMonth"])) {
        $errors["expMonth"] = "Expiry month is required.";
    } else {
        $expMonth = htmlspecialchars($_POST["expMonth"]);
    }

    if (empty($_POST["expYear"])) {
        $errors["expYear"] = "Expiry year is required.";
    } else {
        $expYear = htmlspecialchars($_POST["expYear"]);
    }

    // Check if the expiration month/year is valid
    $currentMonth = date("m");
    $currentYear = date("Y");

    // Convert selected expiration month to numeric
    $monthMap = [
        "January" => 1,
        "February" => 2,
        "March" => 3,
        "April" => 4,
        "May" => 5,
        "June" => 6,
        "July" => 7,
        "August" => 8,
        "September" => 9,
        "October" => 10,
        "November" => 11,
        "December" => 12
    ];
    $selectedMonth = $monthMap[$expMonth] ?? 0;
    $selectedYear = $expYear;

    // Check if the selected expiration date is later than the current date
    if ($selectedYear < $currentYear || ($selectedYear == $currentYear && $selectedMonth <= $currentMonth)) {
        $errors["expDate"] = "Card Already Expired";
    }

    $currentDateTime = date("Y-m-d H:i:s");
    // Check if there are no errors
    if (empty($errors)) {
        // Process the payment and store in the database
        $sql = "INSERT INTO payment (firstName, surName, payPhoneNum, email, cardNum, CVV, ExpYear, ExpMonth, payDateTime, cartID) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssssssssi", $firstname, $surname, $phonenum, $email, $cardNum, $cvv, $expYear, $expMonth, $currentDateTime, $cartID);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            //Update the boolean payed value to 1
            $sqlUpdateCart = "UPDATE cart SET payed = 1 WHERE cartID = ?";
            $stmtUpdateCart = mysqli_prepare($conn, $sqlUpdateCart);
            mysqli_stmt_bind_param($stmtUpdateCart, "i", $cartID);
            mysqli_stmt_execute($stmtUpdateCart);
            mysqli_stmt_close($stmtUpdateCart);
            
            // Retrieve the course IDs and userID based on the cartID
        $sqlGetCourses = "SELECT courseID FROM cartcourse WHERE cartID = ?";
        $stmtGetCourses = mysqli_prepare($conn, $sqlGetCourses);
        mysqli_stmt_bind_param($stmtGetCourses, "i", $cartID);
        mysqli_stmt_execute($stmtGetCourses);
        $result = mysqli_stmt_get_result($stmtGetCourses);

        // Loop through all the courses for the given cartID
        while ($row = mysqli_fetch_assoc($result)) {
            $courseID = $row['courseID'];

            // Insert a record into the mycourses table for each course
            $sqlInsertMyCourses = "INSERT INTO mycourses (userID, courseID) VALUES (?, ?)";
            $stmtInsertMyCourses = mysqli_prepare($conn, $sqlInsertMyCourses);
            mysqli_stmt_bind_param($stmtInsertMyCourses, "ii", $userID, $courseID);
            mysqli_stmt_execute($stmtInsertMyCourses);
            mysqli_stmt_close($stmtInsertMyCourses);
        }

        $payed=0;
        $totalSum=0;
        $numItems=0;

        //New cart Creation:
        $sqlCreateNewCart="INSERT INTO cart(userID, payed, totalSum, numItems) VALUES(?, ?, ?, ?)";
        $stmtCreateNewCart=mysqli_prepare($conn, $sqlCreateNewCart);
        mysqli_stmt_bind_param($stmtCreateNewCart, "iiii", $userID, $payed, $totalSum, $numItems);
        mysqli_stmt_execute($stmtCreateNewCart);
        mysqli_stmt_close($stmtCreateNewCart);


            // Success message
            $successMessage = "Payment done successfully!";
            header("Location: courses.php");
        } else {
            $errors["db"] = "Failed to save payment data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="../Assets/CSS/epayment.css">
    <script>
        // Function to hide success message and reset the form after 3 seconds
        function hideSuccessMessage() {
            setTimeout(function() {
                document.querySelector('.success-modal').style.display = 'none';
                document.getElementById('form').reset(); // Reset the form
            }, 3000); // 3 seconds
        }
    </script>
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

<div class="container">
    <form id="form" method="POST" action="">
        <div class="row">
            <div class="column">
                <h3 class="title">Personal Information </h3>
                <div class="input-box">
                    <span>Firstname:</span>
                    <br>
                    <input name="firstname" type="text" placeholder="Nour Malek" value="<?= htmlspecialchars($firstname) ?>">
                    <div class="error"><?= $errors["firstname"] ?? "" ?></div>
                </div>
                <div class="input-box">
                    <span>Surname:</span>
                    <br>
                    <input name="surname" type="text" placeholder="Yahiaoui" value="<?= htmlspecialchars($surname) ?>">
                    <div class="error"><?= $errors["surname"] ?? "" ?></div>
                </div>
                <div class="input-box">
                    <span>Phone number:</span>
                    <br>
                    <input name="phonenum" type="text" placeholder="0512345678" value="<?= htmlspecialchars($phonenum) ?>">
                    <div class="error"><?= $errors["phonenum"] ?? "" ?></div>
                </div>
                <div class="input-box">
                    <span>Email:</span>
                    <br>
                    <input name="email" type="text" placeholder="example@example.com" value="<?= htmlspecialchars($email) ?>">
                    <div class="error"><?= $errors["email"] ?? "" ?></div>
                </div>
            </div>

            <div class="column">
                <h3 class="title">Payment</h3>
                <div class="input-box">
                    <span>Card number:</span>
                    <br>
                    <input name="cardNum" type="text" placeholder="1234 5678 9124 4576" value="<?= htmlspecialchars($cardNum) ?>">
                    <div class="error"><?= $errors["cardNum"] ?? "" ?></div>
                </div>
                <div class="input-box">
                    <span>CVV:</span>
                    <br>
                    <input name="cvv" type="text" placeholder="123" value="<?= htmlspecialchars($cvv) ?>">
                    <div class="error"><?= $errors["cvv"] ?? "" ?></div>
                </div>
                <div class="input-box">
                    <span>Exp. Month:</span>
                    <br>
                    <select name="expMonth">
                        <option value="">Select Month</option>
                        <?php
                        $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                        foreach ($months as $month) {
                            echo "<option value='$month'" . ($month == $expMonth ? " selected" : "") . ">$month</option>";
                        }
                        ?>
                    </select>
                    <div class="error"><?= $errors["expMonth"] ?? "" ?></div>
                </div>
                <div class="input-box">
                    <span>Exp. Year:</span>
                    <br>
                    <select name="expYear">
                        <?php
                        for ($year = date("Y"); $year <= date("Y") + 2; $year++) {
                            echo "<option value='$year'" . ($year == $expYear ? " selected" : "") . ">$year</option>";
                        }
                        ?>
                    </select>
                    <div class="error"><?= $errors["expYear"] ?? "" ?></div>
                    <div class="error"><?= $errors["expDate"] ?? "" ?></div>
                </div>
            </div>
        </div>

        <input type="hidden" name="cartID" value="<?php echo "$cartID" ?>">
        <button type="submit" class="btn">Submit</button>
    </form>
</div>

<?php if ($successMessage): ?>
    <div class="success-modal" style="display: block;">
        <div class="success-content">
            <div class="checkmark-container">
                <span class="checkmark">&#10003;</span>
            </div>
            <p><?= $successMessage ?></p>
        </div>
    </div>
    <script>hideSuccessMessage();</script>
<?php endif; ?>

<div class="copyright">
    <p>Growy &copy; 2024 - All rights reserved</p>
</div>

</body>
</html>
