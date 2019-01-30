<?php
include('../SocialNetwork/classes/Navbar.php');

Navbar::displayNavbar();
?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Color game</title>
    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css' integrity='sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS' crossorigin='anonymous'>
    <link href="Root/ColorGame/public/CSS/StyleSheet1.css" rel="stylesheet" />
</head>
<body>
    <div id="topContainer">
       <div>
            <h4 id="highScoreText">HighScore:</h6>     
            <h4 id="highScore">0</h6>

            <h4 id="bestScoreText">Your Best Score:</h6>     
            <h4 id="yourBestScore">0</h6>  
        </div>     

        <h1 id="scoretext">Score: <span id="score">0</span></h1>

        <div>       
            <h1 id="chosenColorText">
                <span>RGB(</span><span id="red"></span>, <span id="green"></span>, <span id="blue" class="text-primary"></span>)
            </h1>
        </div>

        <div id="difficultyDropDown">
            <label style="color: #d68888">
                Difficulty:
                <select id="difficulty">
                    <option value="4">Very Easy</option>
                    <option value="8" selected="selected">Easy</option>
                    <option value="12">Medium</option>
                    <option value="16">Hard</option>
                    <option value="24">Very Hard</option>
                </select>
            </label>
    </div>

        <div id="feedback"><h3 id="feedbackText">Click the color that represents the above RGB</h3> <button id="resetButton">Reset</button> <button id="nextButton">Next</button></div>
    </div>
    <div id="squaresContainer">
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js'></script>
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js'></script>
    <script type="text/javascript" src="Root/ColorGame/public/Javascript/Script1.js"></script>  
</body>
</html>