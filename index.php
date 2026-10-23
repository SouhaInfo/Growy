<?php
// Start the session
session_start();
// Check if a session ID is set and assign it to $userID
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Growy Home</title>
    <link rel="stylesheet" href="../Assets/CSS/mainHome.css">
</head>
<body>
    <header>
        <?php 
        if ($userID === null) {
            include '../BiteEase/Components/Header.php'; 
        } elseif($userID<=60 && $userID>=57) {
            include '../BiteEase/Components/adminHeader.php'; 
        }else{
            include '../BiteEase/Components/headerlogin.php'; 
        }
        ?>
        
    </header>
    <?php include '../BiteEase/Components/cookies.php'; ?>
<main>
    <!-- 0 Section: Description Section -->
    <section id="description-section">
        <div class="content">
            <div class="image">
                <img src="../Assets/images/home1.jpg" alt="Image representing parental support and community">
            </div>
            <div class="text">
                <h2>Empowering Parents, Enriching Lives.</h2>
                <p>Discover a supportive community dedicated to enhancing the journey of childcare, development, and education. Our platform makes it easy for parents and caregivers to connect, share experiences, and access valuable resources. Together, we’ll foster growth and understanding as children transition into adulthood. Let’s empower each other with knowledge and support, transforming parenting from a challenge into a shared adventure!</p>
                <a href="#services-section" class="scroll-link">Find More</a>
            </div>
        </div>
    </section>

    <!-- first Section: Services Section -->
    <section id="services-section">
        <div class="services-content">
        <div class="service-box" onclick="window.location.href='#Announcement-section'">
                <h3>Announcements</h3>
                <p>Stay updated with the latest news, events, and job openings.</p>
            </div>
            <div class="service-box" onclick="window.location.href='#courses-section'">
                <h3>Online Courses</h3>
                <p>Access a variety of online courses tailored for parents and caregivers.</p>
            </div>
            <div class="service-box" onclick="window.location.href='#contribution-section'">
                <h3>Contributions</h3>
                <p>Share resources, articles, and experiences with the community.</p>
            </div>
        </div>
    </section>

    <!-- 2nd Section: Announcement-section -->
    <section id="Announcement-section" class="reverse-layout">
        <div class="content">
            <div class="text">
                <h2>Find trusted services to support your family’s needs.</h2>
                <p>From babysitting and tutoring to sports coaching and school transportation, our Announcements section connects you with qualified professionals ready to help. Browse listings, post specific requests, and discover reliable support tailored to your family. Make parenting easier with resources you can trust!</p>
                <a href="../Components/announcements.php" class="courses-link">Find More</a>
            </div>
            <div class="image">
                <img src="../Assets/images/home3.jpg" alt="Image showcasing our courses">
            </div>
        </div>
    </section>
    <!-- 3rd  Section: courses Section -->
    <section id="courses-section">
        <div class="content">
            <div class="image">
                <img src="../Assets/images/home4.jpg" alt="Image representing parental support and community">
            </div>
            <div class="text">
                <h2>Empowering Parents, Enriching Lives.</h2>
                <p>Discover a supportive community dedicated to enhancing the journey of childcare, development, and education. Our platform makes it easy for parents and caregivers to connect, share experiences, and access valuable resources. Together, we’ll foster growth and understanding as children transition into adulthood. Let’s empower each other with knowledge and support, transforming parenting from a challenge into a shared adventure!</p>
                <a href="../Components/courses.php" class="scroll-link">Explore Courses</a>
            </div>
        </div>
    </section>
    <!-- 4th Section: contribution-section -->
    <section id="contribution-section" class="reverse-layout">
        <div class="content">
            <div class="text">
                <h2>Share your insights and enrich our community.</h2>
                <p>In our Contributions section, parents, teachers, and experts can upload helpful resources like videos, podcasts, and articles to support families. Each submission is categorized for easy browsing, and users can interact through comments and feedback. Join us in building a collaborative space where shared experiences and expert advice empower families.</p>
                <a href="../Components/Main_Contribute_page.php" class="courses-link">Find more</a>
            </div>
            <div class="image">
                <img src="../Assets/images/home6 .jpg" alt="Image showcasing our courses">
            </div>
        </div>
    </section>

    <section id="additional-links-section">
        <div class="link-box" onclick="window.location.href='#about-growy'">
            <h3>Want to know more about Growy?</h3>
            <a href="../Components/aboutus.php" class="link-button">Click here</a>
        </div>
        <div class="link-box" onclick="window.location.href='#contact-us'">
            <h3>To contact us</h3>
            <a href="../Components/Contact_Us.php" class="link-button">Click here</a>
        </div>
    </section>

    
</main>
<footer>
<?php include '../BiteEase/Components/Footer.php'; ?>
</footer>

</body>
</html>