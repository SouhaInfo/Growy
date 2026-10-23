<?php
session_start();

if (!isset($_SESSION['userID'])) {
    echo "Please log in to edit the announcement.";
    exit();
}

// Fetch the announcement data
if (isset($_GET['id'])) {
    $announcementID = $_GET['id'];
    $host = 'localhost';
    $dbname = 'growy_db';
    $username = 'root';
    $password = '';
    
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM announcement WHERE annID = ?");
    $stmt->bind_param("i", $announcementID);
    $stmt->execute();
    $result = $stmt->get_result();
    $announcement = $result->fetch_assoc();

    if (!$announcement) {
        echo "Announcement not found.";
        exit();
    }
} else {
    echo "No announcement ID provided.";
    exit();
}

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
    $updated = date('Y-m-d H:i:s');
    
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

    // Handle image upload (optional)
    $imagePath = $announcement['annImage']; // Keep the current image if no new one is uploaded
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
    if (!empty($errors)) {
        foreach ($errors as $field => $message) {
            echo "<p style='color:red;'>$message</p>";
        }
        exit();
    }

    // Update announcement data in the database
    $stmt = $conn->prepare("UPDATE announcement SET 
        annType = ?, annFullName = ?, annTitle = ?, location = ?, avaDateTime = ?, 
        annPrice = ?, annPhone = ?, annEmail = ?, annDesc = ?, annCreatedAt = ?, annImage = ? 
        WHERE annID = ?");
    $stmt->bind_param("sssssisssssi", $type, $name, $title, $location, $datetime, $price, $phone, $email, $description, $updated, $imagePath, $announcementID);

    if ($stmt->execute()) {
        echo "Announcement updated successfully!";
        header("Location: announcementsLI.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..\Assets\CSS\urannouncement.css">
    <title>Edit Announcement</title>
</head>
<body>
    <header> 
        <?php include 'headerLogIn.php'; ?>
    </header>
    <div class="container">
        <h2 class="form-heading">Edit Announcement</h2>
        <form action="edit_announce.php?id=<?php echo $announcement['annID']; ?>" method="post" enctype="multipart/form-data" id="announcementForm" class="announcement-form">
            <label for="type">Announcement Type</label>
            <select id="type" name="type" >
                <option value="Job Announcement" <?php echo $announcement['annType'] == 'Job Announcement' ? 'selected' : ''; ?>>Job Announcement</option>
                <option value="Needed Service" <?php echo $announcement['annType'] == 'Needed Service' ? 'selected' : ''; ?>>Needed Service</option>
            </select>
            <span class="error-message" id="typeError"></span>

            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?php echo $announcement['annFullName']; ?>">
            <span class="error-message" id="nameError"></span>

            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?php echo $announcement['annTitle']; ?>">
            <span class="error-message" id="titleError"></span>

            <label for="location">Location</label>
<select id="location" name="location">
    <option value="">Select Location</option>
    <option value="Adrar" <?php echo $announcement['location'] == 'Adrar' ? 'selected' : ''; ?>>Adrar</option>
    <option value="Chlef" <?php echo $announcement['location'] == 'Chlef' ? 'selected' : ''; ?>>Chlef</option>
    <option value="Laghouat" <?php echo $announcement['location'] == 'Laghouat' ? 'selected' : ''; ?>>Laghouat</option>
    <option value="Oum El Bouaghi" <?php echo $announcement['location'] == 'Oum El Bouaghi' ? 'selected' : ''; ?>>Oum El Bouaghi</option>
    <option value="Batna" <?php echo $announcement['location'] == 'Batna' ? 'selected' : ''; ?>>Batna</option>
    <option value="Béjaïa" <?php echo $announcement['location'] == 'Béjaïa' ? 'selected' : ''; ?>>Béjaïa</option>
    <option value="Biskra" <?php echo $announcement['location'] == 'Biskra' ? 'selected' : ''; ?>>Biskra</option>
    <option value="Béchar" <?php echo $announcement['location'] == 'Béchar' ? 'selected' : ''; ?>>Béchar</option>
    <option value="Blida" <?php echo $announcement['location'] == 'Blida' ? 'selected' : ''; ?>>Blida</option>
    <option value="Bouira" <?php echo $announcement['location'] == 'Bouira' ? 'selected' : ''; ?>>Bouira</option>
    <option value="Tamanrasset" <?php echo $announcement['location'] == 'Tamanrasset' ? 'selected' : ''; ?>>Tamanrasset</option>
    <option value="Tébessa" <?php echo $announcement['location'] == 'Tébessa' ? 'selected' : ''; ?>>Tébessa</option>
    <option value="Tlemcen" <?php echo $announcement['location'] == 'Tlemcen' ? 'selected' : ''; ?>>Tlemcen</option>
    <option value="Tiaret" <?php echo $announcement['location'] == 'Tiaret' ? 'selected' : ''; ?>>Tiaret</option>
    <option value="Tizi Ouzou" <?php echo $announcement['location'] == 'Tizi Ouzou' ? 'selected' : ''; ?>>Tizi Ouzou</option>
    <option value="Algiers" <?php echo $announcement['location'] == 'Algiers' ? 'selected' : ''; ?>>Algiers</option>
    <option value="Djelfa" <?php echo $announcement['location'] == 'Djelfa' ? 'selected' : ''; ?>>Djelfa</option>
    <option value="Jijel" <?php echo $announcement['location'] == 'Jijel' ? 'selected' : ''; ?>>Jijel</option>
    <option value="Sétif" <?php echo $announcement['location'] == 'Sétif' ? 'selected' : ''; ?>>Sétif</option>
    <option value="Saïda" <?php echo $announcement['location'] == 'Saïda' ? 'selected' : ''; ?>>Saïda</option>
    <option value="Skikda" <?php echo $announcement['location'] == 'Skikda' ? 'selected' : ''; ?>>Skikda</option>
    <option value="Sidi Bel Abbès" <?php echo $announcement['location'] == 'Sidi Bel Abbès' ? 'selected' : ''; ?>>Sidi Bel Abbès</option>
    <option value="Annaba" <?php echo $announcement['location'] == 'Annaba' ? 'selected' : ''; ?>>Annaba</option>
    <option value="Guelma" <?php echo $announcement['location'] == 'Guelma' ? 'selected' : ''; ?>>Guelma</option>
    <option value="Constantine" <?php echo $announcement['location'] == 'Constantine' ? 'selected' : ''; ?>>Constantine</option>
    <option value="Médéa" <?php echo $announcement['location'] == 'Médéa' ? 'selected' : ''; ?>>Médéa</option>
    <option value="Mostaganem" <?php echo $announcement['location'] == 'Mostaganem' ? 'selected' : ''; ?>>Mostaganem</option>
    <option value="M’Sila" <?php echo $announcement['location'] == 'M’Sila' ? 'selected' : ''; ?>>M’Sila</option>
    <option value="Mascara" <?php echo $announcement['location'] == 'Mascara' ? 'selected' : ''; ?>>Mascara</option>
    <option value="Ouargla" <?php echo $announcement['location'] == 'Ouargla' ? 'selected' : ''; ?>>Ouargla</option>
    <option value="Oran" <?php echo $announcement['location'] == 'Oran' ? 'selected' : ''; ?>>Oran</option>
    <option value="El Bayadh" <?php echo $announcement['location'] == 'El Bayadh' ? 'selected' : ''; ?>>El Bayadh</option>
    <option value="Illizi" <?php echo $announcement['location'] == 'Illizi' ? 'selected' : ''; ?>>Illizi</option>
    <option value="Bordj Bou Arreridj" <?php echo $announcement['location'] == 'Bordj Bou Arreridj' ? 'selected' : ''; ?>>Bordj Bou Arreridj</option>
    <option value="Boumerdès" <?php echo $announcement['location'] == 'Boumerdès' ? 'selected' : ''; ?>>Boumerdès</option>
    <option value="El Tarf" <?php echo $announcement['location'] == 'El Tarf' ? 'selected' : ''; ?>>El Tarf</option>
    <option value="Tindouf" <?php echo $announcement['location'] == 'Tindouf' ? 'selected' : ''; ?>>Tindouf</option>
    <option value="Tissemsilt" <?php echo $announcement['location'] == 'Tissemsilt' ? 'selected' : ''; ?>>Tissemsilt</option>
    <option value="El Oued" <?php echo $announcement['location'] == 'El Oued' ? 'selected' : ''; ?>>El Oued</option>
    <option value="Khenchela" <?php echo $announcement['location'] == 'Khenchela' ? 'selected' : ''; ?>>Khenchela</option>
    <option value="Souk Ahras" <?php echo $announcement['location'] == 'Souk Ahras' ? 'selected' : ''; ?>>Souk Ahras</option>
    <option value="Tipaza" <?php echo $announcement['location'] == 'Tipaza' ? 'selected' : ''; ?>>Tipaza</option>
    <option value="Mila" <?php echo $announcement['location'] == 'Mila' ? 'selected' : ''; ?>>Mila</option>
    <option value="Aïn Defla" <?php echo $announcement['location'] == 'Aïn Defla' ? 'selected' : ''; ?>>Aïn Defla</option>
    <option value="Naâma" <?php echo $announcement['location'] == 'Naâma' ? 'selected' : ''; ?>>Naâma</option>
    <option value="Aïn Témouchent" <?php echo $announcement['location'] == 'Aïn Témouchent' ? 'selected' : ''; ?>>Aïn Témouchent</option>
    <option value="Ghardaïa" <?php echo $announcement['location'] == 'Ghardaïa' ? 'selected' : ''; ?>>Ghardaïa</option>
    <option value="Relizane" <?php echo $announcement['location'] == 'Relizane' ? 'selected' : ''; ?>>Relizane</option>
    <option value="Timimoun" <?php echo $announcement['location'] == 'Timimoun' ? 'selected' : ''; ?>>Timimoun</option>
    <option value="Bordj Badji Mokhtar" <?php echo $announcement['location'] == 'Bordj Badji Mokhtar' ? 'selected' : ''; ?>>Bordj Badji Mokhtar</option>
    <option value="Ouled Djellal" <?php echo $announcement['location'] == 'Ouled Djellal' ? 'selected' : ''; ?>>Ouled Djellal</option>
    <option value="Béni Abbès" <?php echo $announcement['location'] == 'Béni Abbès' ? 'selected' : ''; ?>>Béni Abbès</option>
    <option value="In Salah" <?php echo $announcement['location'] == 'In Salah' ? 'selected' : ''; ?>>In Salah</option>
    <option value="In Guezzam" <?php echo $announcement['location'] == 'In Guezzam' ? 'selected' : ''; ?>>In Guezzam</option>
    <option value="Touggourt" <?php echo $announcement['location'] == 'Touggourt' ? 'selected' : ''; ?>>Touggourt</option>
    <option value="Djanet" <?php echo $announcement['location'] == 'Djanet' ? 'selected' : ''; ?>>Djanet</option>
    <option value="El M’Ghair" <?php echo $announcement['location'] == 'El M’Ghair' ? 'selected' : ''; ?>>El M’Ghair</option>
    <option value="El Meniaa" <?php echo $announcement['location'] == 'El Meniaa' ? 'selected' : ''; ?>>El Meniaa</option>
</select>

            <span class="error-message" id="locationError"></span>

            <label for="datetime">Date and Time</label>
            <input type="datetime-local" id="datetime" name="datetime" value="<?php echo date('Y-m-d\TH:i', strtotime($announcement['avaDateTime'])); ?>" required>
            <span class="error-message" id="datetimeError"></span>

            <label for="price">Price</label>
            <input type="text" id="price" name="price" value="<?php echo $announcement['annPrice']; ?>">
            <span class="error-message" id="priceError"></span>

            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="<?php echo $announcement['annPhone']; ?>">
            <span class="error-message" id="phoneError"></span>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $announcement['annEmail']; ?>">
            <span class="error-message" id="emailError"></span>

            <label for="description">Announcement Description</label>
            <textarea id="description" name="description"><?php echo $announcement['annDesc']; ?></textarea>
            <span class="error-message" id="descriptionError"></span>

           <!-- Hidden field to store the current image path -->
<input type="hidden" name="current_image" value="<?php echo htmlspecialchars($announcement['annImage']); ?>">

<!-- Image upload and display -->
<div class="image-upload">
    <label for="fileInput">Click to upload an image (optional)</label>

    <!-- Display current image if available -->
    <?php if (!empty($announcement['annImage'])): ?>
        <img src="<?php echo htmlspecialchars($announcement['annImage']); ?>" alt="Current Image" style="max-width: 200px; margin-bottom: 10px;">
    <?php endif; ?>

    <!-- File input for uploading a new image -->
    <input type="file" id="fileInput" name="announcement_image">
</div>

<span class="error-message" id="fileInputError"></span>


            <div class="last">
                <input type="submit" class="submit-btn" value="Update Announcement">
            </div>
        </form>
    </div>
    <div class="copyright">
        <br>
        <p>Growy &copy; 2024 - All rights reserved</p>
        <br><br>
    </div>
    <script src="../Assets/JS/urannouncement.js"></script>
</body>
</html>