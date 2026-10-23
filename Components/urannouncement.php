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
    <?php include 'header.php'; ?>
</header>
    <div class="container">
        <h2 class="form-heading">Post an Announcement</h2>
        <form id="announcementForm" class="announcement-form">
            <label for="type">Announcement Type</label>
            <input type="text" id="type" name="type">
            <span class="error-message" id="typeError"></span>

            <label for="name">Full Name</label>
            <input type="text" id="name" name="name">
            <span class="error-message" id="nameError"></span>

            <label for="title">Title</label>
            <input type="text" id="title" name="title">
            <span class="error-message" id="titleError"></span>

            <label for="location">Location</label>
            <input type="text" id="location" name="location">
            <span class="error-message" id="locationError"></span>

            <label for="date">Date</label>
            <input type="date" id="date" name="date">
            <span class="error-message" id="dateError"></span>

            <label for="time">Time</label>
            <input type="time" id="time" name="time">
            <span class="error-message" id="timeError"></span>

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

            <div class="last">
            <button type="button" class="submit-btn" onclick="validateAndPost()">Post</button>

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


