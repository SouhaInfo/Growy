<?php
require_once 'auth_check.php';

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}
?>
<?php
require_once 'db_connect.php'; // Database connection file


// Ensure user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php"); // Redirect to login page if the user is not logged in
    exit();
}

$userID = $_SESSION['userID']; // Get the userID from the session

// Query to fetch the announcements for the logged-in user
$sql = "SELECT announcement.annID, announcement.annTitle, announcement.annType, announcement.annImage, announcement.annCreatedAt, user.fullname
        FROM announcement
        JOIN user ON announcement.userID = user.userID
        WHERE announcement.userID = '$userID'"; 

$res = $conn->query($sql); // Execute the query
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Announcements - Growy</title>
    <link rel="stylesheet" href="../Assets/CSS/myannouncements.css">
    <link rel="stylesheet" href="../Assets/Footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
<section class="announcements-section">
    <div class="ann-container">
        <h2>My Announcements</h2>
        <div class="announcements-grid"> 
     
            <?php 
            if ($res && $res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) { ?>
                    <div class="announcement-card">
                        <div class="menu-dots">
                            <i class="fa fa-ellipsis-v"></i>
                            <div class="dropdown-menu">
                                <a href="#" class="delete-btn" data-annid="<?php echo htmlspecialchars($row['annID']); ?>">Delete</a>
                                <a href="edit_announce.php?id=<?php echo htmlspecialchars($row['annID']); ?>>">Edit</a>

                            </div>
                        </div>
                        <div class="announcement-img">
                              <img src="<?php echo htmlspecialchars($row['annImage']); ?>" alt="Announcement Image">
                        </div>
                        <div class="announcement-details">
                            <h3><?php echo htmlspecialchars($row['annTitle']); ?></h3>
                            <p>Type: <?php echo htmlspecialchars($row['annType']); ?></p>
                            <p>Posted by: <?php echo htmlspecialchars($row['fullname']); ?></p>
                            <p>Posted on: <?php echo htmlspecialchars($row['annCreatedAt']); ?></p>
                        </div>
                        <div class="exp_btn">
                            <a href="../Components/announce1.php?annID=<?php echo htmlspecialchars($row['annID']); ?>">
                                <button class="explore-btn">Explore</button>
                            </a>
                        </div>
                    </div>
                <?php 
                }
            } else {
                echo "<p>No announcements found.</p>";
            }
            ?>
        </div>
    </div>
</section>

<footer> 
    <?php include 'footer_LI.php'; ?>
</footer>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const annID = button.getAttribute('data-annid');
            
            if (confirm("Are you sure you want to delete this announcement?")) {
                fetch('delete_ann.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ annID: annID })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Announcement deleted successfully.');
                        location.reload(); // Reload the page to reflect the changes
                    } else {
                        alert('Failed to delete the announcement.');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('An error occurred.');
                });
            }
        });
    });

    // Handle the dropdown menu toggle when clicking on the dots
    const menuDots = document.querySelectorAll('.menu-dots');

    menuDots.forEach(dot => {
        const dropdownMenu = dot.querySelector('.dropdown-menu');

        // Toggle dropdown menu visibility on click
        dot.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent the click from closing the menu immediately
            dropdownMenu.classList.toggle('show');  // Toggle the visibility of the dropdown menu
        });
    });

    // Close the dropdown if clicked anywhere outside
    document.addEventListener('click', (e) => {
        menuDots.forEach(dot => {
            const dropdownMenu = dot.querySelector('.dropdown-menu');
            if (!dot.contains(e.target)) {
                dropdownMenu.classList.remove('show'); // Hide the dropdown menu
            }
        });
    });
});
</script>

</body>
</html>
