<?php
require_once 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? null;
    $type = $_POST['type'] ?? null;
    $description = $_POST['description'] ?? null;
    $conID = $_POST['conID'] ?? null;
    $userID = $_SESSION['userID'] ?? null;
    $visualPath = null; // Default to null for file field

    // Handle file upload (if any)
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $file = $_FILES['file'];
        $fileName = $_FILES['file']['name'];
        $fileTmp = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('png', 'jpg', 'jpeg');

        if (in_array($fileActualExt, $allowed)) {
            if ($fileSize < 500000) {
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDes = '../Assets/images/contributions_user_edit' . $fileNameNew;

                if (move_uploaded_file($fileTmp, $fileDes)) {
                    $visualPath = $fileDes; // Assign the uploaded file path
                } else {
                    error_log("File upload failed.");
                    header("Location: My_Contributions_LI.php?error=file_upload_failed");
                    exit();
                }
            } else {
                error_log("File size exceeds limit.");
                header("Location: My_Contributions_LI.php?error=file_size_exceeded");
                exit();
            }
        } else {
            error_log("Invalid file type.");
            header("Location: My_Contributions_LI.php?error=invalid_file_type");
            exit();
        }
    }

    // Validate inputs
    if (!$title || !$type || !$description || !$conID || !$userID) {
        error_log("Missing fields: Title, Type, Description, conID, or userID");
        header("Location: My_Contributions_LI.php?error=missing_fields");
        exit();
    }

    // Prepare SQL query
    if ($visualPath) {
        // If a file was uploaded, update visual staff
        $stmt = $conn->prepare("UPDATE contribution SET conTitle = ?, conType = ?, conDesc = ?, conVisual_staff = ? WHERE conID = ? AND userID = ?");
        $stmt->bind_param("ssssii", $title, $type, $description, $visualPath, $conID, $userID);
    } else {
        // If no file was uploaded, skip updating visual staff
        $stmt = $conn->prepare("UPDATE contribution SET conTitle = ?, conType = ?, conDesc = ? WHERE conID = ? AND userID = ?");
        $stmt->bind_param("sssii", $title, $type, $description, $conID, $userID);
    }

    // Execute query
    if ($stmt->execute()) {
        echo "Record updated successfully.";
        header("Location: My_Contributions_LI.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
        exit();
    }
}
?>
