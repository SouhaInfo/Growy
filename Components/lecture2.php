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
    <h1 class="lecture-title">Lecture 02: Parents and relatives</h1>
    <p class="lecture-description">Discover the Prophet Muhammad's ﷺ approach to engaging with children and teenagers, illustrating a model of compassion and wisdom. This part also covers the duties and respectful treatment owed to parents and relatives, drawing lessons from the Prophet's ﷺ life. </p>
    <div class="lecture-video">
        <iframe src="https://www.youtube.com/embed/EpzGYgiUdaI" frameborder="0" allowfullscreen></iframe>
    </div>

    <div class="lecture-footer">
        <div class="previous-lecture">
            <a href="lecture1.php" class="previous-lecture-link">← Previous lecture</a> <!-- Update the link accordingly -->
        </div>
        <div class="lecture-checkbox">
            <input type="checkbox" id="lectureDone" class="styled-checkbox">
            <label for="lectureDone" class="checkbox-label">Lecture done</label>
        </div>
        <div class="next-lecture">
            <a href="lecture3.php" class="next-lecture-link">Next Lecture →</a> <!-- Update the link accordingly -->
        </div>
    </div>
</main>
<footer>
        <?php include 'Footer_LI.php'; ?>
    </footer>
</body>
</html>
