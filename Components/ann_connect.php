<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $type = $_POST['type'];
    $name = $_POST['name'];
    $title = $_POST['title'];
    $location = $_POST['location'];
    $datetime = $_POST['datetime'];
    $price = $_POST['price'];
    $phone = (string) $_POST['phone'];
    $email = $_POST['email'];
    $description = $_POST['description'] ?? '';
    $created = date('Y-m-d H:i:s');
    
    // Validation logic
    $errors = [];

    if (empty($type)) {
        $errors['type'] = "Type is required.";
    }
    if (empty($name) || strlen($name) > 30) {
        $errors['name'] = "Name is required and cannot exceed 30 characters.";
    }
    if (empty($title) || strlen($title) > 30) {
        $errors['title'] = "Title is required and cannot exceed 30 characters.";
    }
    if (empty($location) || strlen($location) > 35) {
        $errors['location'] = "Location is required and cannot exceed 35 characters.";
    }
    if (empty($price) || $price < 0 || $price > 10000000) {
        $errors['price'] = "Price must be between 0 and 10,000,000 DZD.";
    }
    if (empty($phone) || !preg_match('/^(05|06|07)[0-9]{8}$/', $phone)) {
        $errors['phone'] = "Phone number must start with 05, 06, or 07 and contain exactly 10 digits.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Valid email is required.";
    }
    if (empty($description) || strlen($description) > 1000) {
        $errors['description'] = "Description cannot exceed 1000 characters.";
    }

    // Handle image upload validation and saving
    $imagePath = '../assets/images/img/default.jpg'	; // Initialize to null
    if (isset($_FILES['announcement_image']) && $_FILES['announcement_image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['announcement_image']['tmp_name'];
        $imageName = $_FILES['announcement_image']['name'];
        $imageSize = $_FILES['announcement_image']['size'];
        $imageType = $_FILES['announcement_image']['type'];

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
            $newImageName = uniqid() . '.' . $imageExtension;
            $uploadDir = '../assets/images/img/';
            $imagePath = $uploadDir . $newImageName;

            // Move uploaded file to the server directory
            if (!move_uploaded_file($imageTmpPath, $imagePath)) {
                $errors['image'] = "Failed to upload image.";
            }
        }
    }

    // If there are errors, stop and show them
    if (empty($errors)) {
       
    

    // If no errors, insert into the database
    $host = 'localhost';
    $dbname = 'growy_db';
    $username = 'root';
    $password = '';

    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        // Prepare the SQL query for inserting data
        $stmt = $conn->prepare("INSERT INTO announcement 
            (annType, annFullName, annTitle, location, avaDateTime, annPrice, annPhone, annEmail, annDesc, annCreatedAt, annImage, userID) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"); 

session_start(); // Start the session to access session data

// Check if the user is logged in (session data exists)
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID']; // Fetch userID from session
    echo "User ID: " . $userID; // Display userID
} else {
    echo "No user is logged in.";
}

        // Bind the parameters for the SQL query
        $stmt->bind_param("sssssisssssi", $type, $name, $title, $location, $datetime, $price, $phone, $email, $description, $created, $imagePath, $userID);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect after successful insertion
            header("Location: announcementsLI.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the prepared statement and the connection
        $stmt->close();
        $conn->close();
    }}
}
?>