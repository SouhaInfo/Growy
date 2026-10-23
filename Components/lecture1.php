<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecture Page</title>
    <link rel="stylesheet" href="../Assets/CSS/lecture.css">
</head>
<body>
<header>
        <?php include 'HeaderLogIn.php'; ?>
    </header>
<main id="lecture-page">
    <h1 class="lecture-title">Lecture 01: Raising children</h1>
    <p class="lecture-description">Gain insight into the core Islamic teachings that outline what a healthy family looks like. This part offers a brief summary of family rights and obligations, as well as the keys to building strong, healthy relationships within the family. Drawing on Quranic principles, prophetic traditions, and expert advice, it highlights essential guidance for modern families seeking balance and harmony.</p>
    <div class="lecture-video">
        <iframe src="https://www.youtube.com/embed/zKBTee30f40" frameborder="0" allowfullscreen></iframe>
    </div>

    <div class="lecture-footer">
        <div class="previous-lecture">
            <a href="course1f.php" class="previous-lecture-link">← Course program</a> <!-- Update the link accordingly -->
        </div>
        <div class="next-lecture">
            <a href="lecture2.php" class="next-lecture-link">Next Lecture →</a> <!-- Update the link accordingly -->
        </div>
    </div>
</main>
<footer>
        <?php include 'Footer_LI.php'; ?>
    </footer>
</body>
</html>
