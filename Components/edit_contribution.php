<?php
require_once 'db_connect.php'; // Database connection file
session_start();

// Ensure user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];

// Get the conID from the URL
if (isset($_GET['conID'])) {
    $conID = $_GET['conID'];
    
    // Fetch the contribution details from the database
    $stmt = $conn->prepare("SELECT * FROM contribution WHERE conID = ? AND userID = ?");
    $stmt->bind_param("ii", $conID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if the contribution exists
    if ($result->num_rows > 0) {
        $contribution = $result->fetch_assoc();
    } else {
        echo "Contribution not found.";
        exit();
    }

    // Fetch user details from the database
    $stmt = $conn->prepare("SELECT fullName, email, phoneNumber FROM user WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "No contribution ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contribution</title>
    <link rel="stylesheet" href="../Assets/CSS/Contribute_Form.css">
</head>

<body>

    <header>
        <?php include 'headerLogIn.php'; ?>
    </header>

<main>
    <div class="container">
        <form id="edit" action="update_con.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="column">
                    <h3 class="title">Personal Information</h3>
                    <div class="input-box">
                        <span>Full name:</span>
                        <br>
                        <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($user['fullName']); ?>" readonly>
                       
                    </div>
                    <div class="input-box">
                        <span>Phone Number:</span>
                        <br>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['phoneNumber']); ?>" readonly>
                        
                    </div>
                   

                    <div class="input-box">
                        <span>Email:</span>
                        <br>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                        
                    </div>
                </div>
                <div class="column">
                    <h3 class="title">Share your voice</h3>
                    <div class="input-box">
                        <span>Title:</span>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($contribution['conTitle']); ?>">
                        <div id="titleMessage" style="color: red; font-size: 12px;"></div> <!-- Error message for title -->
                    </div>
                    <div class="input-box">
                        <span>Type:</span>
                        <input type="text" id="type" name="type" value="<?php echo htmlspecialchars($contribution['conType']); ?>" >
                        <div id="typeMessage" style="color: red; font-size: 12px;"></div> <!-- Error message for type -->
                    </div>
                    <div class="input-box">
                        <span>Description:</span>
                        <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($contribution['conDesc']); ?>" >
                        <div id="descriptionMessage" style="color: red; font-size: 12px;"></div> <!-- Error message for description -->
                    </div>
                    
                    <div>
                        <input type="file" id="fileUpload" name="file">
                    </div>
                </div>
            </div>

            <input type="hidden" name="conID" value="<?php echo htmlspecialchars($contribution['conID']); ?>">

            <button type="submit" class="btn"  name="submit">Submit</button>
        </form>
    </div>
</main>
<script src="../Assets/JS/edit_con.js"></script>
</body>

</html>
