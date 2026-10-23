<?php
session_start();
include 'db_connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
$userID = $_SESSION['userID'];
$stmt = $conn->prepare("SELECT username, phoneNumber, email , fullName FROM user WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conTitle = $_POST['conTitle'];
    $conDesc = $_POST['conDesc'];
    $contype = $_POST['contype'];
    $imagePath = null; // Initialize to null

if (isset($_FILES['conVisual_form']) && $_FILES['conVisual_form']['error'] === UPLOAD_ERR_OK) {
    $imageTmpPath = $_FILES['conVisual_form']['tmp_name'];
    $imageName = $_FILES['conVisual_form']['name'];
    $imageSize = $_FILES['conVisual_form']['size'];
    $imageType = $_FILES['conVisual_form']['type'];

    $errors = []; // Array to store errors

    // File validation: Only allow images
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($imageType, $allowedTypes)) {
        $errors['image'] = "Only JPG, PNG, and GIF images are allowed.";
    }

    // File size validation (limit 5MB)
    if ($imageSize > 5000000) {
        $errors['image'] = "File size exceeds the allowed 5MB limit.";
    }

    if (empty($errors)) {
        // Generate a unique filename to prevent overwriting
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $safeFileName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', pathinfo($imageName, PATHINFO_FILENAME));
        $newImageName = $safeFileName . '_' . uniqid() . '.' . strtolower($imageExtension);
        $uploadDir = '../Assets/images/img/';
        $imagePath = $uploadDir . $newImageName;

        // Ensure the directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move uploaded file to the server directory
        if (!move_uploaded_file($imageTmpPath, $imagePath)) {
            $errors['image'] = "Failed to upload the image.";
        }
    }

    // Handle any errors
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<script>alert('" . htmlspecialchars($error) . "');</script>";
        }
    } else {
        echo "<script>alert('Image uploaded successfully to: " . htmlspecialchars($imagePath) . "');</script>";
    }
}

    
    $stmt = $conn->prepare("INSERT INTO contribution (conTitle, conDesc, contype, conVisual_form, userID) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $conTitle, $conDesc, $contype, $imagePath, $userID);
    
   if(!$stmt->execute()) {
        echo "Error: " . $stmt->error;
        exit();
    } else {
        $message = "The form is submitted.";
        header("Location: contribute_FormLI.php?message=".urlencode($message));
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
    <title>Contribute Form</title>
    <link rel="stylesheet" href="../Assets/CSS/Contribute_Form.css">
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
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="column">
                        <h3 class="title">Personal Information </h3>
                        <div class="input-box">
                            <span>Full name:</span>
                            <br>
                            <input type="text" name="fullName" value="<?php echo htmlspecialchars($userData['fullName']); ?>" readonly>
                            <div id="nameMessage" style="font-size: 12px;"></div> 
                        </div>

                        <div class="input-box">
                            <span>Phone number:</span>
                            <br>
                            <input type="text" name="phoneNumber" value="<?php echo $userData['phoneNumber'] ?? ''; ?>">
                            <div id="phoneMessage" style="font-size: 12px;"></div> 
                        </div>

                        <div class="input-box">
                            <span>Email</span>
                            <br>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" readonly>
                            <div id="emailMessage" style="font-size: 12px;"></div> 
                        </div>
                    </div>

                    <div class="column">
                        <h3 class="title">Share your voice</h3>
                        <div class="input-box">
                            <span>Title</span>
                            <input type="text" name="conTitle" placeholder="Title of your contribution" required>
                            <div id="titleMessage" style="font-size: 12px;"></div> 
                        </div>
                        <div class="input-box">
                            <span>Type</span>
                            <select name="contype" required>
                                <option value="Article">Article</option>
                                <option value="Visual">Visual</option>
                                <option value="Both">Both</option>
                            </select>
                            <div id="optionsMessage" style="font-size: 12px;"></div> 
                        </div>
                        <div class="input-box">
                            <span>Article</span>
                            <textarea name="conDesc" rows="5" cols="40" required></textarea>
                            <div id="descriptionMessage" style="font-size: 12px;"></div> 
                        </div>
                        <div>
                            <input type="file" name="conVisual_form">
                            <div id="fileUploadMessage" style="font-size: 12px;"></div> 
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn">Submit</button>
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

    <script src="../Assets/JS/Contribute_Validation.js"></script>
</body>
</html>