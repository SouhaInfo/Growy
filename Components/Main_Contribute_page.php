<?php
 require_once 'db_connect.php';
session_start();
$userID = isset($_SESSION['userID']);


// Default query to display all approved contributions
$sql_dynamic = "SELECT * FROM contribution WHERE status='approved'";
$all_contribution = $conn->query($sql_dynamic);

$search_results = []; // Array to store search results
$search_query = ""; // To retain the search query in the input field

if (isset($_POST["submit-search"])) {
    $search_value = mysqli_real_escape_string($conn, $_POST["search_input"]); // Sanitize user input
    $search_query = $search_value; // Retain the search query for the input field

    // SQL query for searching contributions
    $sql = "SELECT * FROM contribution WHERE status='approved' AND (conTitle LIKE '%$search_value%' OR conType LIKE '%$search_value%')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $search_results = $result->fetch_all(MYSQLI_ASSOC); // Fetch all results as an associative array
    } else {
        $search_results = null; // Indicate no results found
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contribute Service</title>
    <link rel="stylesheet" href="../Assets/CSS/Main_Contribute_Page.css">
    <link rel="stylesheet" href="../Assets/CSS/Footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<header>
        <?php 
        if ($userID == null) {
            // If userID is null, include 'header.php'
            include 'header.php'; 
        } else {
            // If userID is set, include 'headerlogin.php'
            include 'headerlogin.php'; 
        }
        ?>
</header>

    <section class="search-section">
        <div class="container">
            <form class="search-bar-container" action="" method="POST"> 
                <div class="search">
                    <input class="search_input" type="text" name="search_input" placeholder="Search Article" value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" class="search_icon" name="submit-search"><i class="fa fa-search"></i></button>
                </div>

                 <a href="<?php echo $userID ? '../Components/Contribute_FormLI.php' : '../Components/login.php'; ?>">
                <button type="button" class="add-contribution-btn">Contribute</button>
                 </a>
            </form>
        </div>
    </section>
    <main class="grid-container">
        <?php
        if (isset($_POST["submit-search"])) { // in case the click button of search engine was clicked this will trigger this if statement to exec
            if ($search_results) {
                foreach ($search_results as $row) {
                    echo '<div class="grid-item">';
                    echo '<img src="' . htmlspecialchars($row["conVisual_staff"]) . '" alt="Visual">';
                    echo '<h3 id="main">' . htmlspecialchars($row["conTitle"]) . '</h3>';
                    echo '<p>' . htmlspecialchars($row["conType"]) . '</p>';
                    echo '<p>' . htmlspecialchars($row["conDesc"]) . '</p>';
                    echo '<form action="../Components/Article.php" method="POST">';
                    echo '<input type="hidden" name="conID" value="' . htmlspecialchars($row["conID"]) . '">';
                    echo '<button type="submit" class="read-more">Read More</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo "<p>No matched results to your search.</p>";
            }
        } else {
            while ($row = mysqli_fetch_assoc($all_contribution)) { // dynamic loading for contributions in my DB - no search engine input  
                echo '<div class="grid-item">';
                echo '<img src="' . htmlspecialchars($row["conVisual_staff"]) . '" alt="Visual">';
                echo '<h3 id="main">' . htmlspecialchars($row["conTitle"]) . '</h3>';
                echo '<p>' . htmlspecialchars($row["conType"]) . '</p>';
                echo '<p>' . htmlspecialchars($row["conDesc"]) . '</p>';
                echo '<form action="../Components/Article.php" method="POST">';
                echo '<input type="hidden" name="conID" value="' . htmlspecialchars($row["conID"]) . '">';
                echo '<button type="submit" class="read-more">Read More</button>';
                echo '</form>';
                echo '</div>';
            }
            
        }
        ?>
    </main>

    <?php include 'Footer.php'; ?>
</body>
</html>