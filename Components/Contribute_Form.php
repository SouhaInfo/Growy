<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contribute Form</title>
    <link rel="stylesheet" href="../Assets/CSS/Contribute_Form.css">
</head>

<body>

    <header >
        <?php include 'header.php'; ?>
    </header>

<main>
    <div class="container">
        <form action="">
            <div class="row">
                <div class="column">
                    <h3 class="title">Personal Information </h3>
                    <div class="input-box">
                        <span>Full name:</span>
                        <br>
                        <input type="text" id="fullName">
                        <div id="nameMessage" style=" font-size: 12px;"></div> 
                    </div>

                    <div class="input-box">
                        <span>Phone number:</span>
                        <br>
                        <input type="text" id="phoneNumber" placeholder="0512345678">
                        <div id="phoneMessage" style=" font-size: 12px;"></div> 
                    </div>

                    <div class="input-box">
                        <span>Email</span>
                        <br>
                        <input type="email" id="email">
                        <div id="emailMessage" style=" font-size: 12px;"></div> 
                    </div>
                    <div class="input-box">
                        <span>Job/ Position</span>
                        <br>
                        <input type="email" id="email" placeholder="optional">
                        
                    </div>
                </div>

                <div class="column">
                    <h3 class="title">Share your voice</h3>
                    <div class="input-box">
                        <span> Title </span>
                        <input type="text" id="title" placeholder="Title of your contribution">
                        <div id="titleMessage" style=" font-size: 12px;"></div> 
                    </div>
                    <div class="input-box">
                        <span>Type</span>
                        <select id="options" name="options">
                            <option value="option1">Article</option>
                            <option value="option2">Visual</option>
                            <option value="option3">Articel& Visual</option>
                        </select>
                        <div id="optionsMessage" style=" font-size: 12px;"></div> 
                    </div>
                    <div class="input-box">
                        <span> Article </span>
                        <textarea id="description" name="description" rows="5" cols="40"></textarea>
                        <div id="descriptionMessage" style=" font-size: 12px;"></div> 
                    </div>

                    <div class="input-box">
                        <span> Video URL </span>
                        <input type="text" id="video" placeholder="URL video from Youtube">
                    </div>
                   
                    <div>
                        <input type="file" id="fileUpload" name="fileUpload">
                        <div id="fileUploadMessage" style="font-size: 12px;"></div> 
                    </div>
                </div>
            </div>

            <button type="submit" class="btn">Submit</button>
        </form>

        <br>
    </div>
    
    <div class="copyright">
        <br>
        <p>Growy &copy; 2024 - All rights reserved</p>
        <br>
        <br>
    </div>
</main>

<script src="../Assets/JS/Contribute_Validation.js"></script>

</body>

</html>
