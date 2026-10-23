<?php
session_start(); // Start the session
include 'db_connect.php'; // Include your database connection

// Set default image path if no profile photo is found

// Check if userID is stored in the session
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID']; // Get the userID from the session
    $dbinfo = "SELECT fullName, bio FROM user WHERE userID = ?";
    $stmt = $conn->prepare($dbinfo);
    $stmt->bind_param("i", $userID); // Bind the userID to the query
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Get profile photo if it's not set in the session
    $profilePhoto = $_SESSION['profilePhoto'] ?? null; // Default to null if not set.
    
    // If profile photo is not in the session, fetch it from the database
    if (!$profilePhoto) {
        $sql = "SELECT profilePhoto FROM user WHERE userID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userID); // Bind userID as an integer
        $stmt->execute();
        $stmt->bind_result($profilePhoto);
        $stmt->fetch();
    }

    // If no profile photo is found, set it to the default image
    if (empty($profilePhoto)) {
        $defaultProfilePic = '../Assets/images/img/cropped_image.png';
        $profilePhoto = $defaultProfilePic;
    }

    if ($result->num_rows > 0) {
        $rt = $result->fetch_assoc();
        $fullName = $rt['fullName']; // Get the username
        $bio = $rt['bio']; // Get the bio
    } else {
        $username = "Unknown User";
        $bio = null;
    }

    // Close the statement after it's done
    $stmt->close();
} else {
    $fullName = "Guest";
    $bio = null;
}
?>

<head>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-material-design@4.1.1/dist/css/bootstrap-material-design.min.css" integrity="sha384-wXznGJNEXNG1NFsbm0ugrLFMQPWswR3lds2VeinahP8N0zJw9VWSopbjv2x7WCvX" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons">
    <link rel="stylesheet" href="../Assets/CSS/profilestyle.css">
</head>

<body class="profile-page">
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
            include 'headerlogin.php'; 
        }
        ?>
</header>
    <div class="page-header header-filter" style="background-image:url('http://wallpapere.org/wp-content/uploads/2012/02/black-and-white-city-night.png');"></div>
    <div class="main main-raised">
    <div class="profile-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 ml-auto mr-auto">
                        <div class="profile">
                            <div class="avatar">
                                <!-- Display the profile photo -->
                                <img src="<?php echo $profilePhoto; ?>" alt="Profile Picture" class="img-fluid rounded-circle">
                            </div>
                            <div class="name">
                                <h3 class="title"><?php echo htmlspecialchars($fullName); ?></h3> <!-- Display the username -->
                                <li class="nav-item">
                                    <a class="nav-link" href="../Components/settings.php" onclick="window.location=this.href;return false;">
                                      <i class="material-icons">settings</i>
                                      Settings
                                    </a>
                                </li>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="description text-center">
                    <p>
                        <?php
                        if (empty(trim($bio))) {
                            echo "Add a bio! <a href='../Components/settings.php'>Edit in settings</a>";
                        } else {
                            echo htmlspecialchars($bio);
                        }
                        ?>
                    </p>
                </div>

         <?php //if($userID!=57 && $userID!=58 && $userID!=59 && $userID!=60): ?>
          <div class="row">
          <div class="col-md-6 ml-auto mr-auto">
                        <div class="profile-tabs">
                          <ul class="nav nav-pills nav-pills-icons justify-content-center" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" href="../Components/courses.php" onclick="window.location=this.href;return false;">
                                  <i class="material-icons"></i>
                                  Courses
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../Components/My_Contributions_LI.php" onclick="window.location=this.href;return false;">
                                  <i class="material-icons"></i>
                                  Contributions
                                </a>
                                </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../Components/myannouncementsLI.php" onclick="window.location=this.href;return false;">
                                  <i class="material-icons"></i>
                                  Announcements
                                </a>
                            </li>
                          </ul>
                        </div>
            </div>
        </div>
        <?php //endif; ?>
    </div>
</body>
