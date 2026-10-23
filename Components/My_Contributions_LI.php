<?php
require_once 'db_connect.php'; // Database connection file
session_start();

// Ensure user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID']; // Get the userID from the session

// Secure query using prepared statements
$stmt = $conn->prepare("
    SELECT contribution.*, user.userID 
    FROM contribution 
    JOIN user ON contribution.userID = user.userID 
    WHERE contribution.userID = ?
");
$stmt->bind_param("i", $userID); // Bind the parameter
$stmt->execute();
$res = $stmt->get_result(); // Execute the query and get the result

$hasContributions = $res->num_rows > 0; // Check if user has contributions
$search_results = []; // Array to store search results
$search_query = ""; // To retain the search query in the input field

if (isset($_POST["submit-search"])) {
    $search_value = mysqli_real_escape_string($conn, $_POST["search_input"]); // Sanitize user input
    $search_query = $search_value; // Retain the search query for the input field

    // SQL query for searching contributions
    $sql = "SELECT * FROM contribution WHERE status='approved' AND userID='$userID' AND (conTitle LIKE '%$search_value%' OR conType LIKE '%$search_value%')";
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
    <title>My Contributions</title>
    <link rel="stylesheet" href="../Assets/CSS/Main_Contribute_Page.css">
    <link rel="stylesheet" href="../Assets/CSS/Footer.css">
    <link rel="stylesheet" href="../Assets/CSS/My_Contribution.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

    <section class="search-section">
    <div class="container">
        <form class="search-bar-container" action="" method="POST">
            <div class="search">
                <input class="search_input" type="text" name="search_input" placeholder="Search Article" value="<?php echo htmlspecialchars($search_query ?? ''); ?>">
                <button type="submit" class="search_icon" name="submit-search"><i class="fa fa-search"></i></button>
            </div>
            <!-- Conditionally render the Contribute button -->
            <a href="../Components/Contribute_FormLI.php">
                <button type="button" class="add-contribution-btn">Contribute</button>
            </a>
        </form>
    </div>
</section>

    <main class="grid-container">
        <?php
        if (isset($_POST["submit-search"])) { // Search mode
            if ($search_results) { // Search results exist
                foreach ($search_results as $row) {
                    echo '<div class="grid-item">';
                    echo '<div class="menu-dots">';
                    echo '<i class="fa fa-ellipsis-v"></i>';
                    echo '<div class="dropdown-menu">';
                    echo '<a href="edit_contribution.php?conID=' . htmlspecialchars($row['conID'], ENT_QUOTES, 'UTF-8') . '">Delete</a>';
                    echo '<a href="edit_contribution.php?conID=' . htmlspecialchars($row['conID'], ENT_QUOTES, 'UTF-8') . '">Edit</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '<img src="' . htmlspecialchars($row['conVisual_staff'], ENT_QUOTES, 'UTF-8') . '" alt="Visual">';
                    echo '<h3 id="main">' . htmlspecialchars($row['conTitle'], ENT_QUOTES, 'UTF-8') . '</h3>';
                    echo '<p>' . htmlspecialchars($row['conType'], ENT_QUOTES, 'UTF-8') . '</p>';
                    echo '<p>' . htmlspecialchars($row['conDesc'], ENT_QUOTES, 'UTF-8') . '</p>';
                    echo '<form action="../Components/Article.php" method="POST">';
                    echo '<input type="hidden" name="conID" value="' . htmlspecialchars($row['conID'], ENT_QUOTES, 'UTF-8') . '">';
                    echo '<button type="submit" class="read-more">Read More</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo "<p>No matching contributions found.</p>";
            }
        } elseif ($hasContributions) { // No search but contributions exist
            while ($row = mysqli_fetch_assoc($res)) {
                echo '<div class="grid-item">';
                echo '<div class="menu-dots">';
                echo '<i class="fa fa-ellipsis-v"></i>';
                echo '<div class="dropdown-menu">';
                echo '<form action="delete_con.php" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this contribution?\');">';
                echo '<input type="hidden" name="conID" value="' . htmlspecialchars($row['conID'], ENT_QUOTES, 'UTF-8') . '">';
                echo '<button type="submit" class="delete-btn">Delete</button>';
                echo '</form>';
                echo '<a href="edit_contribution.php?conID=' . htmlspecialchars($row['conID'], ENT_QUOTES, 'UTF-8') . '">Edit</a>';
                echo '</div>';
                echo '</div>';
                echo '<img src="' . htmlspecialchars($row['conVisual_staff'], ENT_QUOTES, 'UTF-8') . '" alt="Visual">';
                echo '<h3 id="main">' . htmlspecialchars($row['conTitle'], ENT_QUOTES, 'UTF-8') . '</h3>';
                echo '<p>' . htmlspecialchars($row['conType'], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<p>' . htmlspecialchars($row['conDesc'], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<form action="../Components/Article.php" method="POST">';
                echo '<input type="hidden" name="conID" value="' . htmlspecialchars($row['conID'], ENT_QUOTES, 'UTF-8') . '">';
                echo '<button type="submit" class="read-more">Read More</button>';
                echo '</form>';
                echo '</div>';
            }
        } else { // No contributions
            echo "<p>You currently have no contributions. <a href='Contribute_FormLI.php'>Click here to add one</a>.</p>";
       
        }
        ?>
    </main>

    <?php include 'Footer.php'; ?>
    

</body>
</html>
