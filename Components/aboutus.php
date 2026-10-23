


<?php 
session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Growy</title>
    <link rel="stylesheet" href="../Assets/Footer.css">
    <style>
        /* General styles */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #eefbf2;
            color: #333;
        }

        /* Container Styling */
        .ab-container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        .ab-h2 h2 {
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
        }

        p {
            font-size: 1em;
            color: #555;
        }

        /* About and Community Sections */
        .about, .community {
            padding: 40px 0;
        }

        .about-content, .community-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .about-text, .community-text {
            flex: 1;
            max-width: 50%;
        }

        .about-image img, .community-image img {
            width: 100%;
            max-width: 500px;
            height: auto;
            border-radius: 8px;
        }

        /* Team Section */
        .team {
            background-color: #e5f9ee;
            padding: 40px 0;
            text-align: center;
        }

        /* Carousel Styles */
        .carousel {
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .carousel-track-container {
            position: relative;
            width: 100%;
            overflow: hidden;
        }

        .carousel-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-slide {
            min-width: 100%;
            text-align: center;
            padding: 10px;
        }

        .carousel-slide img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: auto;
        }

        .carousel-slide h3 {
            margin-top: 10px;
            font-size: 1.2em;
            color: #333;
        }

        .carousel-slide p {
            font-size: 1em;
            color: #777;
        }

        /* Carousel Buttons */
        .carousel-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: #4e8c5e;
            color: white;
            border: none;
            padding: 12px 16px;
            cursor: pointer;
            z-index: 10;
            border-radius: 50%;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .carousel-button.left {
            left: 10px;
        }

        .carousel-button.right {
            right: 10px;
        }

        .carousel-button:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Carousel Dots */
        .carousel-dots {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .carousel-dot {
            width: 10px;
            height: 10px;
            background-color: #ccc;
            border-radius: 50%;
            margin: 0 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .carousel-dot.active {
            background-color: #333;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .about-content, .community-content {
                flex-direction: column-reverse;
            }

            .about-text, .community-text {
                max-width: 100%;
            }

            .carousel-slide img {
                width: 120px;
                height: 120px;
            }
        }
    </style>
</head>
<header>
        <?php    
        
        $userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
        if ($userID === null) {
            include 'Header.php'; 
        } elseif($userID<=60 && $userID>=57) {
            include 'adminHeader.php'; 
        }else{
            include 'headerlogin.php'; 
        }
        ?>
        
    </header>
<body>
    <section class="about">
        <div class="ab-container">
            <div class="about-content">
                <div class="about-image">
                    <img src="../assets/images/img/family7.jpg" alt="About Us">
                </div>
                <div class="about-text">
                    <div class="ab-h2"><h2>About Growy</h2></div>
                    <p>In Growy, we are dedicated to supporting families and enhancing the care experience for children. Our mission is to provide reliable resources and a community that empowers parents and caregivers.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="community">
        <div class="ab-container">
            <div class="community-content">
                <div class="community-text">
                    <div class="ab-h2"><h2>Our Community</h2></div>
                    <p>Growy's community is made up of engaged parents, caregivers, and professionals who share knowledge, resources, and support. Together, we create a nurturing environment for children to thrive.</p>
                </div>
                <div class="community-image">
                    <img src="../assets/images/img/community1.jpg" alt="Our Community">
                </div>
            </div>
        </div>
    </section>

    <section class="team">
        <div class="ab-container">
            <div class="ab-h2"><h2>Our Team</h2></div>
            <div class="carousel">
                <div class="carousel-track-container">
                    <div class="carousel-track">
                        <div class="carousel-slide current-slide">
                            <img src="../assets/images/img/malak.jpg" alt="Bouguezzana Malak">
                            <h3>Bouguezzana Malak</h3>
                            <p>Developer</p>
                        </div>
                        <div class="carousel-slide">
                            <img src="../assets/images/img/souha1.jpg" alt="Bendris Souha">
                            <h3>Bendris Souha</h3>
                            <p>Developer</p>
                        </div>
                        <div class="carousel-slide">
                            <img src="../assets/images/img/nour2.jpg" alt="Nour Malek Yahiaoui">
                            <h3>Nour Malek Yahiaoui</h3>
                            <p>Developer</p>
                        </div>
                        <div class="carousel-slide">
                            <img src="../assets/images/img/yazan.jpg" alt="Yazan Chibane">
                            <h3>Yazan Chibane</h3>
                            <p>Developer</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-button left">&lt;</button>
                <button class="carousel-button right">&gt;</button>
                <div class="carousel-dots">
                    <span class="carousel-dot active"></span>
                    <span class="carousel-dot"></span>
                    <span class="carousel-dot"></span>
                    <span class="carousel-dot"></span>
                </div>
            </div>
        </div>
    </section>

<footer>
    <?php include 'footer.php'; ?>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const track = document.querySelector('.carousel-track');
        const slides = Array.from(track.children);
        const nextButton = document.querySelector('.carousel-button.right');
        const prevButton = document.querySelector('.carousel-button.left');
        const dots = Array.from(document.querySelectorAll('.carousel-dot'));
        const slideWidth = slides[0].getBoundingClientRect().width;
        let currentIndex = 0;
        let autoSlideInterval;

        const moveToSlide = (index) => {
            const targetSlide = slides[index];
            track.style.transform = `translateX(-${slideWidth * index}px)`;
            slides[currentIndex].classList.remove('current-slide');
            dots[currentIndex].classList.remove('active');
            slides[index].classList.add('current-slide');
            dots[index].classList.add('active');
            currentIndex = index;
        };

        const moveToNextSlide = () => {
            const nextIndex = (currentIndex + 1) % slides.length;
            moveToSlide(nextIndex);
        };

        const startAutoSlide = () => {
            autoSlideInterval = setInterval(moveToNextSlide, 3000);
        };

        const stopAutoSlide = () => {
            clearInterval(autoSlideInterval);
        };

        nextButton.addEventListener('click', () => {
            stopAutoSlide();
            moveToNextSlide();
            startAutoSlide();
        });

        prevButton.addEventListener('click', () => {
            stopAutoSlide();
            const prevIndex = (currentIndex - 1 + slides.length) % slides.length;
            moveToSlide(prevIndex);
            startAutoSlide();
        });

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                stopAutoSlide();
                moveToSlide(index);
                startAutoSlide();
            });
        });

        startAutoSlide();
    });
</script>
</body>
</html>

