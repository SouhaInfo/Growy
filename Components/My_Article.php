<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Best Teaching Experience</title>
    <link rel="stylesheet" href="../Assets/CSS/Article.css">
    <link rel="stylesheet" href="../Assets/CSS/Footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <header >
         <?php include 'HeaderLogIn.php'; ?>
    </header>

    <section class="article-header">
        <h1> What I can share as a teacher?</h1>
        <p>Younes Fahmi | Published on: july 21, 2024</p>
    </section>

    <main>
        <article>
            <p>
            Teaching can be a demanding profession. There are times when students can seem uninterested in learning and disruptive to the classroom environment. There are plenty of studies and educational strategies for improving student behavior. But personal experience may be the best way to show how to turn a difficult student into a dedicated pupil. I had such an experience: one where I was able to help change a student with major behavioral issues into a learning success story.
            </p>
            <br>
            <img src="../Assets/images/teacher2.jpg" alt="Sample image">

            <h3 class="article">Troubled Student</h3>
            <p>Tyler was enrolled in my senior American government class for a semester, followed by a semester of economics. He had impulse-control and anger-management issues. He had been suspended many times in previous years. When he entered my class in his senior year, I assumed the worst.</p>
            <br>
            
            <p>Tyler sat in the back row. I never used a seating chart with students on the first day; this was always my opportunity to get to know my pupils before assigning them to specific seats after a few weeks. Every time I talked at the front of the class, I would ask questions of students, calling them by name. Doing this—sans seating chart—helped me to get to know them and learn their names. Unfortunately, every time I called on Tyler, he would respond with a glib answer. If he got an answer wrong, he would become angry. </p>
            <br>

            <p>About a month into the year, I was still trying to connect with Tyler. I can usually get students involved in class discussions or at least motivate them to sit quietly and attentively. By contrast, Tyler was just loud and obnoxious.</p>
            <br>

            <h3 class="article">Battle of Wills</h3>
            <p>Tyler had been in so much trouble through the years that being a problem student had become his modus operandi. He expected his teachers to know about his referrals, where he was sent to the office, and suspensions, where he was given mandatory days to stay out of school. He would push every teacher to see what it would take to get a referral. I tried to outlast him. I had rarely found referrals to be effective because students would return from the office behaving worse than before. </p>
            <br>

            <p>One day, Tyler was talking while I was teaching. In the middle of the lesson, I said in the same tone of voice, "Tyler why don't you join our discussion instead of having one of your own." With that, he got up from his chair, pushed it over and yelled something. I can't remember what he said other than that it included several profanity words. I sent Tyler to the office with a discipline referral, and he received a week-long out-of-school suspension. </p>
            <br>

            <p>To this point, this was one of my worst teaching experiences. I dreaded that class every day. Tyler's anger was almost too much for me. The week Tyler was out of school was a wonderful hiatus, and we got a lot accomplished as a class. However, the suspension week would soon come to an end, and I dreaded his return. </p>
            <br>

            <h3 class="article">The Plan</h3>
            <p>On the day of Tyler's return, I stood at the door awaiting him. As soon as I saw him, I asked Tyler to talk to me for a moment. He seemed unhappy to do it but agreed. I told him that I wanted to start over with him. I also told him that if he felt like he was going to lose control in class, he had my permission to step outside the door for a moment to collect himself.  </p>
            <br>

            <p>From that point on, Tyler was a changed student. He listened and he participated in class. He was a smart student, something I could finally witness in him. He even stopped a fight between two of his classmates one day. He never abused his break time privilege. Giving Tyler the power to leave the classroom showed him that he had the ability to choose how he would behave </p>
            <br>

            <p>At the end of the year, Tyler wrote me a thank you note about how well the year had gone for him. I still have that note today and find it touching to reread when I become stressed about teaching.</p>
            <br>

            <h3 class="article">Avoid Prejudgment</h3>
            <p>This experience changed me as a teacher. I came to understand that students are people who have feelings and who don't want to feel cornered. They want to learn, but they also want to feel as if they have some control over themselves. I never made assumptions again about students before they came into my class. Every student is different; no two students react in the same way.</p>
            <br> 

            <p>It is our task as teachers to find not only what motivates each student to learn but also what causes them to misbehave. If we can meet them at that point and take away their reason to misbehave, we can go a long way toward achieving more effective classroom management and a better learning environment.</p>
            <br>
        </article>
    </main>

    <section class="comments">
        <h3>Comments</h3>
        <form class="comment-form">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" >
            <div id="nameMessage" style="font-size:12px";></div>
            <label for="comment">Comment: <span id="charCount">0/1000</span></label>
            <textarea id="comment" name="comment" rows="4"></textarea>
            <div id="commentMessage" style="font-size:12px;"></div>
            <button type="submit">Submit Comment</button>
        </form>
        <div class="comment-list">
            <div class="comment">
                <p><strong>Hichem soula:</strong> I want a professor like you!</p>
            </div>
        </div>
    </section>

    <footer>
    <?php include 'Footer_LI.php'; ?>
    </footer>
    <script src="../Assets/JS/comment.js"></script>
    <script src="../Assets/JS/count.js"></script>
</body>
</html>
