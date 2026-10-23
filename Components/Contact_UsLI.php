<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = $_SESSION['userID'];
$email = $_SESSION['email'];
$fullName = $_SESSION['fullName'] ?? '';
$phoneNumber = $_SESSION['phoneNumber'] ?? null; // Default to null if not set.
if (!$phoneNumber) {
    $sql = "SELECT phoneNumber FROM user WHERE userID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID); // Bind userID as an integer
    $stmt->execute();
    $stmt->bind_result($phoneNumber);
    $stmt->fetch();
    $stmt->close();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data
        $contactFullName = $_POST['contactFullName'];
        $contactPhone = $_POST['contactPhone'];
        $contactEmail = $_POST['contactEmail'];
        $contactMotif = $_POST['contactMotif'];
        $contactDesc = $_POST['contactDesc'];
        
        // Check if we're updating an existing record
        if (isset($_POST['contactFormID'])) {
            // Update existing record
            $sql = "UPDATE contactus SET 
                    contactFullName = ?, 
                    contactPhone = ?, 
                    contactEmail = ?, 
                    contactMotif = ?, 
                    contactDesc = ?,
                    WHERE contactFormID = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([$contactFullName, $contactPhone, $contactEmail, $contactMotif, $contactDesc, $_POST['contactFormID']]);
            
            $message = "Contact updated successfully!";
        } else {
            // Insert new record
            $sql = "INSERT INTO contactus (contactFullName, contactPhone, contactEmail, contactMotif, contactDesc) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([$contactFullName, $contactPhone, $contactEmail, $contactMotif, $contactDesc]);
            
            $message = "Submitted";
        }
        
        // Redirect back with success message
        header("Location: Contact_UsLI.php?" . urlencode($message));
        exit();
        
    } catch(PDOException $e) {
        // Redirect back with error message
        header("Location: Contact_UsLI.php?success?" . urlencode("Error: " . $e->getMessage()));
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/CSS/Contact_Us.css">
    <title>Contact Us</title>
</head>

<body>

<header>
        <?php 
        if ($userID === null) {
            // If userID is null, include 'header.php'
            include 'header.php'; 
        } else {
            // If userID is set, include 'headerlogin.php'
            include 'headerlogin.php'; 
        }
        ?>
    </header>

    <main>
        <div class="container">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="contact-form" method="post">
                <div class="row">
                    <div class="column">
                        <h3 class="title">Personal Information</h3>
                        <div class="input-box">
                            <span>Full name:</span>
                            <br>
                            <input type="text" id="name" name="contactFullName" placeholder="Enter your full name" value="<?php echo $fullName ?>">
                            <div id="nameMessage" class="message"><span></span></div>
                        </div>
                        <div class="input-box">
                            <span>Phone number</span>
                            <br>
                            <input type="text" id="phone" name="contactPhone" placeholder="0512345678" value="<?php echo $phoneNumber ?>">
                            <div id="phoneMessage" class="message"><span></span></div>
                        </div>
                        <div class="input-box">
                            <span>Email</span>
                            <br>
                            <input type="email" id="email" name="contactEmail" placeholder="example@example.com" value="<?php echo $email ?>">
                            <div id="emailMessage" class="message"><span></span></div>
                        </div>
                    </div>

                    <div class="column">
                        <h3 class="title">Talk to our staff</h3>
                        <div class="input-box">
                            <span>Type</span>
                            <br>
                            <select id="options" name="contactMotif">
                                <option value="">Please select an option</option>
                                <option value="Technical">Technical</option>
                                <option value="Suggestion">Suggestion</option>
                                <option value="Opinion">Opinion</option>
                                <option value="Other">Other</option>
                            </select>
                            <div id="optionsMessage" class="message"><span></span></div>
                        </div>
                        <div class="input-box">
                            <span>Explain </span>
                            <br>
                            <textarea id="description" name="contactDesc" rows="5" cols="40"></textarea>
                            <div id="descriptionMessage" class="message"><span></span></div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn">Contact us</button>
            </form>

            <br>
        </div>

        <div class="copyright">
            <br>
            <p>Growy &copy; <?php echo date("Y"); ?> - All rights reserved</p>
            <br>
            <br>
        </div>

    </main>
    <script src="../Assets/JS/Contact_Us.js"></script>
</body>

</html>