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
    <h1 class="lecture-title">Lecture 03: Spouses and In-laws</h1>
    <p class="lecture-description">Explore the mutual rights and responsibilities of spouses toward each other and their in-laws, with an emphasis on creating a supportive and balanced family environment. This part delves into strategies for achieving harmony within marriage, focusing on respect, empathy, and cooperation as outlined in Islamic teachings. </p>
    <div class="lecture-video">
        <iframe src="https://www.youtube.com/embed/O3tGv4tAffM" frameborder="0" allowfullscreen></iframe>
    </div>

    <div class="lecture-footer">
        <div class="previous-lecture">
            <a href="lecture2.php" class="previous-lecture-link">← Previous lecture</a> <!-- Update the link accordingly -->
        </div>
        <div class="lecture-checkbox">
            <input type="checkbox" id="lectureDone" class="styled-checkbox">
            <label for="lectureDone" class="checkbox-label">Lecture done</label>
        </div>
        <div class="next-lecture">
            <a href="coursesLI.php" class="next-lecture-link">Courses →</a> <!-- Update the link accordingly -->
        </div>
    </div>
</main>
<footer>
        <?php include 'Footer_LI.php'; ?>
    </footer>
</body>
</html>
