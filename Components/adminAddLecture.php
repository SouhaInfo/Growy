<?php

require_once "db_connect.php";
session_start();
// Check if a session ID is set and assign it to $userID
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;

$titleError = "";
$duartionError = ""; 
$imgError = ""; 
$linkError = "";
$descError = "";

// Retrieve courseID from the URL
$courseId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (isset($_POST["submit"])) {
    $title = $_POST["lecture-title"];
    $duration = $_POST["lecture-duration"];
    $image = $_POST["lecture-image"];
    $desc = $_POST["lecture-description"];
    $link = $_POST["lecture-link"];

    if (empty($title)) {
        $titleError = "Title is required";
    }

    if (empty($duration)) {
        $duartionError = "Duration is required";
    } else {
        $duration = trim($duration);
        $duration = htmlspecialchars($duration);
        if (!preg_match("/^(?:[0-1]?\d|2[0-3]):[0-5]?\d:[0-5]?\d$/", $duration)) {
            $duartionError = "Duration must be in the form HH:MM:SS";
        }
    }

    if (empty($image)) {
        $imgError = "Lecture image is required";
    } else {
        $image = trim($image);
        $image = htmlspecialchars($image);
        if (!preg_match("/^(https?:\/\/.*\.(?:png|jpg|jpeg|gif|bmp|webp|svg))$/i", $image)) {
            $imgError = "Invalid image URL";
        }
    }

    if (empty($desc)) {
        $descError = "Lecture description is required";
    }

    if (empty($link)) {
        $linkError = "Lecture Link is required";
    } else {
        $link = trim($link);
        $link = htmlspecialchars($link);
        if (!preg_match("/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)[a-zA-Z0-9_-]{11}$/", $link)) {
            $linkError = "Invalid lecture link";
        }
    }

    // Connection to DB:
    if (!empty($title) && !empty($image) && !empty($desc) && !empty($link) && !empty($duration) &&
        preg_match("/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)[a-zA-Z0-9_-]{11}$/", $link) &&
        preg_match("/^(https?:\/\/.*\.(?:png|jpg|jpeg|gif|bmp|webp|svg))$/i", $image) && 
        preg_match("/^(?:[0-1]?\d|2[0-3]):[0-5]?\d:[0-5]?\d$/", $duration)) {
        
        $host = "localhost";
        $dbname = "growy_db";
        $username = "root";
        $password = "";
        
        $conn = mysqli_connect($host, $username, $password, $dbname);
        
        if (mysqli_connect_errno()) {
            die("Connection error: " . mysqli_connect_error());
        }
        
        // Insert query with courseID
        $sql = "INSERT INTO lecture (courseID, lectTitle, duration, lectDesc, lectLink, lectImage)
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_stmt_init($conn);
        
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            die(mysqli_error($conn));
        }

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "isssss", $courseId, $title, $duration, $desc, $link, $image);
        
        // Execute query
        mysqli_stmt_execute($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lecture</title>
    <link rel="stylesheet" href="/Assets/CSS/adminAddLecture.css">
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
            include 'adminHeader.php'; 
        }else{
            header("Location: login.php");
                exit; // Always include exit after a header redirect
        }
        ?>
</header>

<main>
    <div class="container">
        <form method="post">
            <div class="row">
                <div class="column">
                    <h3 class="form-title">Lecture Information: </h3>
                    
                    <div class="input-box">
                        <label for="lecture-title">Lecture Title:</label>
                        <br>
                        <input type="text" id="lecture-title" name="lecture-title" placeholder="Enter lecture title">
                        <span style="color: red"><?php echo $titleError ?></span>
                    </div>

                    <div class="input-box">
                        <label for="lecture-duration">Duration: </label>
                        <br>
                        <input type="text" id="lecture-duration" name="lecture-duration" placeholder="Enter lecture duration">
                        <span style="color: red"><?php echo $duartionError ?></span>
                    </div>

                    <div class="input-box">
                        <label for="lecture-link">Image Link (URL): </label>
                        <br>
                        <input type="text" id="lecture-link" name="lecture-image" placeholder="Enter image link">
                        <span style="color: red"><?php echo $imgError ?></span>
                    </div>
                </div>

                <div class="column">
                    <div class="input-box">
                        <label for="lecture-link">Lecture Link: </label>
                        <br>
                        <input type="text" id="lecture-link" name="lecture-link" placeholder="Enter lecture link">
                        <span style="color: red"><?php echo $linkError ?></span>
                    </div>

                    <div class="input-box">
                        <label for="lecture-description">Description: </label>
                        <textarea id="lecture-description" name="lecture-description" rows="5" cols="40" placeholder="Enter lecture description"></textarea>
                        <span style="color: red"><?php echo $descError ?></span>
                    </div>
                </div>
            </div>

            <button type="submit" class="submit-btn" name="submit">Submit</button>
        </form>
    </div>

    <div class="copyright">
        <br>
        <p>Growy &copy; 2024 - All rights reserved</p>
        <br>
    </div>
</main>

</body>
</html>
