<?php 
require_once 'auth_check.php';

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..\Assets\CSS\urannouncement.css">
    <title>Announcements Form</title>
</head>
<body>  
<header> 
    <?php include 'headerLogIn.php'; ?>
</header>
    <div class="container">
        <h2 class="form-heading">Post an Announcement</h2>
        <form action="ann_connect.php" method="post"  enctype="multipart/form-data" id="announcementForm" class="announcement-form">
            <label for="type">Announcement Type</label>
            <select id="type" name="type" >
    
    <option value="Job Announcement">Job Announcement</option>
    <option value="Needed Service">Needed Service</option>
       </select>
            <span class="error-message" id="typeError"></span>

            
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name">
            <span class="error-message" id="nameError"></span>
            

            <label for="title">Title</label>
            <input type="text" id="title" name="title">
            <span class="error-message" id="titleError"></span>

            <label for="location">Location</label>
<select id="location" name="location">
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
</select>
<span class="error-message" id="locationError"></span>
            <label for="datetime">Date and Time</label>
<input type="datetime-local" id="datetime" name="datetime" >
<span class="error-message" id="datetimeError"></span>


            <label for="price">Price</label>
            <input type="text" id="price" name="price">
            <span class="error-message" id="priceError"></span>

            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone">
            <span class="error-message" id="phoneError"></span>

            <label for="email">Email</label>
            <input type="email" id="email" name="email">
            <span class="error-message" id="emailError"></span>

            <label for="description">Announcement Description</label>
            <textarea id="description" name="description"></textarea>
            <span class="error-message" id="descriptionError"></span>
            
            
            <div class="image-upload">
    <label for="fileInput">Click to upload an image</label>
    <input type="file" id="fileInput" name="announcement_image">
     </div>
<span class="error-message" id="fileInputError"></span>


            <div class="last">
            <input type="submit" class="submit-btn" value="post" >
            
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

