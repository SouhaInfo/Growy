<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "growy_db");

// Function to fetch announcements based on filters
function fetchAnnouncements($conn, $search = "", $type = "", $location = "") {
    $query = "
        SELECT 
            announcement.annID,
            announcement.annTitle,
            announcement.annType,
            announcement.annImage,
            announcement.annCreatedAt,
            announcement.location,
            user.userName 
        FROM 
            announcement 
        INNER JOIN 
            user 
        ON 
            announcement.userID = user.userID
        WHERE 1=1
    ";

    if (!empty($search)) {
        $query .= " AND announcement.annTitle LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
    }

    if (!empty($type)) {
        $query .= " AND announcement.annType = '" . mysqli_real_escape_string($conn, $type) . "'";
    }

    if (!empty($location)) {
        $query .= " AND announcement.location = '" . mysqli_real_escape_string($conn, $location) . "'";
    }

    return mysqli_query($conn, $query);
}

// Fetch all announcements for page load
$allAnnouncements = fetchAnnouncements($conn);
if (!$allAnnouncements) {
    die("Error fetching announcements: " . mysqli_error($conn));
}

// AJAX request handling for real-time filtering
if (isset($_POST['ajax']) && $_POST['ajax'] === 'true') {
    $search = $_POST['search'] ?? "";
    $type = $_POST['type'] ?? "";
    $location = $_POST['location'] ?? "";

    $filteredAnnouncements = fetchAnnouncements($conn, $search, $type, $location);

    $response = [
        'jobAnnouncements' => [],
        'neededServices' => []
    ];

    while ($row = mysqli_fetch_assoc($filteredAnnouncements)) {
        if ($row['annType'] === 'Job Announcement') {
            $response['jobAnnouncements'][] = $row;
        } elseif ($row['annType'] === 'Needed Service') {
            $response['neededServices'][] = $row;
        }
    }

    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Growy</title>
    <link rel="stylesheet" href="../Assets/CSS/announcements.css">
    <link rel="stylesheet" href="../Assets/Footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<header> 
    <?php include 'headerLogIn.php'; ?>
</header> 



    <section class="search-section">
    <div class="ann-container">
        <div class="search-bar-container">
            <div class="search">
                <input class="search_input" id="searchInput" type="text" placeholder="Search announcements...">
                <button id="clearButton" class="clear-button" style="display: none;">Clear</button>
                <button id="voiceSearchButton" class="voice-button">🎤</button>
                <a href="#" class="search_icon"><i class="fa fa-search"></i></a>
            </div>
            <a href="../Components/urannouncementLI.php">
                <button class="add-announcement-btn">+ Add</button>
            </a>
        </div>
        <div class="filter-container">
            <select id="typeFilter" class="filter">
                <option value="">Type of Announcement</option>
                <option value="Job Announcement">Job Announcement</option>
                <option value="Needed service">Needed Service</option>
            </select>
            <select id="locationFilter" class="filter"> 
            <option value="">Select Location</option>
            <option value="Adrar">Adrar</option>
    <option value="Chlef">Chlef</option>
    <option value="Laghouat">Laghouat</option>
    <option value="Oum El Bouaghi">Oum El Bouaghi</option>
    <option value="Batna">Batna</option>
    <option value="Béjaïa">Béjaïa</option>
    <option value="Biskra">Biskra</option>
    <option value="Béchar">Béchar</option>
    <option value="Blida">Blida</option>
    <option value="Bouira">Bouira</option>
    <option value="Tamanrasset">Tamanrasset</option>
    <option value="Tébessa">Tébessa</option>
    <option value="Tlemcen">Tlemcen</option>
    <option value="Tiaret">Tiaret</option>
    <option value="Tizi Ouzou">Tizi Ouzou</option>
    <option value="Algiers">Algiers</option>
    <option value="Djelfa">Djelfa</option>
    <option value="Jijel">Jijel</option>
    <option value="Sétif">Sétif</option>
    <option value="Saïda">Saïda</option>
    <option value="Skikda">Skikda</option>
    <option value="Sidi Bel Abbès">Sidi Bel Abbès</option>
    <option value="Annaba">Annaba</option>
    <option value="Guelma">Guelma</option>
    <option value="Constantine">Constantine</option>
    <option value="Médéa">Médéa</option>
    <option value="Mostaganem">Mostaganem</option>
    <option value="M’Sila">M’Sila</option>
    <option value="Mascara">Mascara</option>
    <option value="Ouargla">Ouargla</option>
    <option value="Oran">Oran</option>
    <option value="El Bayadh">El Bayadh</option>
    <option value="Illizi">Illizi</option>
    <option value="Bordj Bou Arreridj">Bordj Bou Arreridj</option>
    <option value="Boumerdès">Boumerdès</option>
    <option value="El Tarf">El Tarf</option>
    <option value="Tindouf">Tindouf</option>
    <option value="Tissemsilt">Tissemsilt</option>
    <option value="El Oued">El Oued</option>
    <option value="Khenchela">Khenchela</option>
    <option value="Souk Ahras">Souk Ahras</option>
    <option value="Tipaza">Tipaza</option>
    <option value="Mila">Mila</option>
    <option value="Aïn Defla">Aïn Defla</option>
    <option value="Naâma">Naâma</option>
    <option value="Aïn Témouchent">Aïn Témouchent</option>
    <option value="Ghardaïa">Ghardaïa</option>
    <option value="Relizane">Relizane</option>
    <option value="Timimoun">Timimoun</option>
    <option value="Bordj Badji Mokhtar">Bordj Badji Mokhtar</option>
    <option value="Ouled Djellal">Ouled Djellal</option>
    <option value="Béni Abbès">Béni Abbès</option>
    <option value="In Salah">In Salah</option>
    <option value="In Guezzam">In Guezzam</option>
    <option value="Touggourt">Touggourt</option>
    <option value="Djanet">Djanet</option>
    <option value="El M’Ghair">El M’Ghair</option>
    <option value="El Meniaa">El Meniaa</option>
                <!-- Add more locations as needed -->
            </select>
        </div>
    </div>
 </section>
 <?php include 'cookies.php'; ?>
 <div id="ann">
 <section class="announcements-section light-section" id="jobAnnouncements">
    <div class="ann-container">
        <h2>Job Announcements</h2>
        <div class="announcements-grid">
            <?php 
            // Reset the result pointer for re-use
            mysqli_data_seek($allAnnouncements, 0);
            if (mysqli_num_rows($allAnnouncements) > 0) {
                while ($row = mysqli_fetch_assoc($allAnnouncements)) {
                    if ($row['annType'] === 'Job Announcement') { ?>
                        <div class="announcement-card">
                            <div class="announcement-img">
                                <img src="<?php echo htmlspecialchars($row['annImage']); ?>" alt="Announcement Image">
                            </div>
                            <div class="announcement-details">
                                <h3><?php echo htmlspecialchars($row['annTitle']); ?></h3>
                                <p>Type: <?php echo htmlspecialchars($row['annType']); ?></p>
                                <p>Posted by: <?php echo htmlspecialchars($row['userName']); ?></p>
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
                }
            } else {
                echo "<p>No Job Announcements found.</p>";
            }
            ?>
        </div>
    </div>
 </section>


    <section class="announcements-section dark-section" id="neededServices">
    <div class="ann-container">
        <h2>Needed Services</h2>
        <div class="announcements-grid">
            <?php 
            // Reset the result pointer for re-use
            mysqli_data_seek($allAnnouncements, 0);
            if (mysqli_num_rows($allAnnouncements) > 0) {
                while ($row = mysqli_fetch_assoc($allAnnouncements)) {
                    if ($row['annType'] === 'Needed Service') { ?>
                        <div class="announcement-card">
                            <div class="announcement-img">
                                <img src="<?php echo htmlspecialchars($row['annImage']); ?>" alt="Announcement Image">
                            </div>
                            <div class="announcement-details">
                                <h3><?php echo htmlspecialchars($row['annTitle']); ?></h3>
                                <p>Type: <?php echo htmlspecialchars($row['annType']); ?></p>
                                <p>Posted by: <?php echo htmlspecialchars($row['userName']); ?></p>
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
                }
            } else {
                echo "<p>No Needed Services found.</p>";
            }
            ?>
        </div>
    </div>
   </section> 


    <script src="../Assets/JS/announcements.js"></script>
</div>
<footer> 
    <?php include 'footer_LI.php'; ?>
</footer>

</body>
</html>

