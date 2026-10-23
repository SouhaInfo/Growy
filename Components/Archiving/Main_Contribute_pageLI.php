<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contribute service</title>
    <link rel="stylesheet" href="../Assets/CSS/Main_Contribute_Page.css">
    <link rel="stylesheet" href="../Assets/CSS/Footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <header> <?php include 'HeaderLogIn.php'; ?></header>
<section class="search-section">
        <div class="container">
            <div class="search-bar-container">
                <div class="search">
                    <input class="search_input" type="text" placeholder="Search Article">
                    <a href="#" class="search_icon"><i class="fa fa-search"></i></a>
                </div> 
                <a href="../Components/Contribute_FormLI.php">
                <button class="add-contribution-btn"> Contribute</button></a>
            </div>  
            <div class="filter-container">
                <select class="filter">
                    <option value="">Type of Article</option>
                    <option value="Personal">Personal story/ Experience</option>
                    <option value="Article-parent">Article by a parent</option>
                    <option value="Article-caregiver">Article by a caregiver</option>
                    <option value="other">Others</option>
                </select>

                <select class="filter">
                    <option value="">Field</option>
                    <option value="medical">Medical</option>
                    <option value="teaching">Teaching</option>
                    <option value="babysitting">Babysitting</option>
                    <option value="sports">Sports</option>
                    <option value="Transportaion">Transportaion</option>
                    <option value="other">Others</option>
                </select>

            </div>
        </div>
    </section>
   
    <main class="grid-container">

       <div class="grid-item">
            <img src="../Assets/images/teacher1.jpg" alt="Template 1">
            <h3 id="main">My student</h3>
            <p>Personal Experience</p>
            <p>Teaching can be a demanding profession. There are times when students can seem uninterested in learning</p>
            <button class="read-more"><a href="../Components/Article5LI.php">Read More</a></button>
        </div>

        <div class="grid-item">
            <img src="../Assets/images/Feed.jpg" alt="Template 1">
            <h3 id="main">Bottle Feeding</h3>
            <p>Food & Nutrition</p>
            <p>The most obvious difference between bottle-feeding and breastfeeding is that the bottle lets you see how much your baby is drinking</p>
            <button class="read-more"><a href="../Components/Article3LI.php">Read More</a></button>
        </div>


        <div class="grid-item">
            <img src="../Assets/images/parents.jpg" alt="Template 2">
            <h3 id="main">Children's growth</h3>
            <p>Education</p>
            <p>Parents and caregivers are the most important people in a child's life. They offer love, acceptance, appreciation, encouragement</p>
            <button class="read-more"><a href="../Components/Article2LI.php">Read More</a></button>
        </div>
        <div class="grid-item">
            <img src="../Assets/images/isolation.jpg" alt="Template 3">
            <h3 id="main">Introversion</h3>
            <p>Personal Story</p>
            <p>Growing up as an introverted child in an extroverted world often felt like navigating a path not entirely built for me. Now, looking back as an adult</p>
            <button class="read-more"><a href="../Components/Article1LI.php">Read More</a></button>
        </div>

        <div class="grid-item">
            <img src="../Assets/images/Teenagers2.jpg" alt="Template 1">
            <h3 id="main">Teenagers ages</h3>
            <p>Education</p>
            <p>Let’s delve into the fascinating world of teenagers and understand what defines this age range</p>
            <button class="read-more"><a href="../Components/Article4LI.php">Read More</a></button>
        </div>
        
    </main>

    <footer> <?php include 'Footer_LI.php'; ?></footer>
   
</body>
</html>
