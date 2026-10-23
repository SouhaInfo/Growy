<?php
include_once 'db_connect.php' ;

session_start();
// Check if a session ID is set and assign it to $userID
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;


$titleError=""; //title
$teacherError=""; //t name
$priceError=""; //price
$language="";
$descError="";
$imgError="";

if (isset($_POST["submit"])){

    
    $title=$_POST["course-title"];
    $name=$_POST["teacher-name"];
    $price=$_POST["course-price"];
    $desc=$_POST["course-description"];
    $language=$_POST["course-language"];
    $img=$_POST["course-image"];

    if(empty($title)){
        $titleError = "Title is required";
    }

    if(empty($name)){
        $teacherError = "Teacher name is required";
    }else{
        $name=trim($name);
        $name=htmlspecialchars($name);
        if(!preg_match("/^[A-Za-z. ]+$/", $name)){
            $teacherError="Teacher name can only include letters, spaces, and periods.";
        }
    }

    if(empty($price)){
        $priceError="Course price is required";
    }else{
        $price=trim($price);
        $price=htmlspecialchars($price);
        if(!preg_match("/^\d+(\.\d+)?([eE]\+?\d+)?$/", $price)){
            $priceError="The price must be a positive real number";
        }
    }

    if(empty($desc)){
        $descError = "Course description is required";
    }


    if(empty($img)){
        $imgError = "Image URL is required";
    }else{
        $img=trim($img);
        $img=htmlspecialchars($img);
        if(!preg_match("/^(https?:\/\/.*\.(?:png|jpg|jpeg|gif|bmp|webp|svg))$/i", $img)){
            $imgError="Invalid image URL";
        }
    }
 
$valid=(!empty($title)) && (!empty($name)) && (!empty($price)) && (!empty($desc)) && (!empty($img)) &&( preg_match("/^(https?:\/\/.*\.(?:png|jpg|jpeg|gif|bmp|webp|svg))$/i", $img)) && 
(preg_match("/^\d+(\.\d+)?([eE]\+?\d+)?$/", $price))&& (preg_match("/^[A-Za-z. ]+$/", $name)) ;
//Conncetion to DB:
if($valid){
$host = "localhost";
$dbname = "growy_db";
$username = "root";
$password = "";
        


$conn = mysqli_connect(hostname: $host,
                       username: $username,
                       password: $password,
                       database: $dbname);
        
if (mysqli_connect_errno()) {
    die("Connection error: " . mysqli_connect_error());
}           

$sql = "INSERT INTO course (courseTitle, teacherName, coursePrice, language, courseDesc, courseImage)
        VALUES (?, ?, ?, ?, ?, ?)";


$stmt = mysqli_stmt_init($conn);

if ( ! mysqli_stmt_prepare($stmt, $sql)) {
 
    die(mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "ssdsss",
                       $title,
                       $name,
                       $price,
                       $language,
                       $desc,
                       $img
                    );

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
    <title>Add course</title>
    <link rel="stylesheet" href="/Assets/CSS/adminaddcourse.css">
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
        <form   method="post" >
            <div class="row">
                <div class="column">
                    <h3 class="form-title">Course Information: </h3>
                    <div class="input-box">
                        <label for="course-title">Course Title:</label>
                        <br>
                        <input type="text" id="course-title" name="course-title">
                        <span style="color: red"><?php echo $titleError ?></span>
                    </div>

                    <div class="input-box">
                        <label for="teacher-name">Teacher's Full Name:</label>
                        <br>
                        <input type="text" id="teacher-name" name="teacher-name" placeholder="">
                        <span style="color: red"><?php echo $teacherError ?></span>
                    </div>

                    <div class="input-box">

                        <label for="course-price">Course Price (DZD):</label>
                        <br>
                        <input type="text" id="course-price" name="course-price" placeholder="">
                        <span style="color: red"><?php echo $priceError ?></span>
                        
                    </div>
                </div>

                <div class="column">

                <div class="input-box">
                        <label for="course-language">Language: </label>
                        <select id="course-language" name="course-language">
                            <option value="arabic">Arabic</option>
                            <option value="english" selected>English</option>
                            <option value="french">French</option>
                        </select>
                    </div>
                    
                    <div class="input-box">
                        <label for="course-description">Description: </label>
                        <textarea id="course-description" name="course-description" rows="5" cols="40"></textarea>
                        <span style="color: red"><?php echo $descError ?></span>
                        

                    </div>
                    <div class="input-box">
                        <label for="course-image">Course Image (URL):</label>
                        <br>
                        <input type="text" id="course-image" name="course-image" placeholder="">
                        <span style="color: red"><?php echo $imgError ?></span>
                    </div>
                </div>
            </div>

            <button type="submit" name="submit" class="submit-btn">Submit</button>
        </form>

        <br>
    </div>
    
    <div class="copyright">
        <br>
        <p>Growy &copy; 2024 - All rights reserved</p>
        <br>
        <br>
    </div>
</main>

</body>

</html>
