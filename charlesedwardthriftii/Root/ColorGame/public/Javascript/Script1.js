var colors = ["0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0"];

var squares = document.querySelectorAll(".square");
var chosenColorText = document.querySelector("#chosenColorText");
var chosenRed = document.querySelector("#red");
var chosenGreen = document.querySelector("#green");
var chosenBlue = document.querySelector("#blue");
var feedback = document.querySelector("#feedback");
var scoreText = document.querySelector("#score"); 
var nextButton = document.querySelector("#nextButton");
var resetButton = document.querySelector("#resetButton");
var difficultyDropDown = document.querySelector("#difficulty");
var highScoreText = document.querySelector("#highScore");
var yourBestScore = document.querySelector("#yourBestScore");

var numberOfSquares = difficulty.options[difficulty.selectedIndex].value;

var score = 0;
var scoreBonus = 0;
var scoreLoss = 0;
var highScore = 0;
var bestScore = 0;

var bgameOver = false;

var chosenColorRed;
var chosenColorGreen;
var chosenColorBlue;
var chosenColor;

updateHighScore();
newColors();
Start();

function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min)) + min;
}

function getRandomRGB() {
    
    return {
        "r": getRandomInt(0, 255),
        "g": getRandomInt(0, 255),
        "b": getRandomInt(0, 255)
    }
}

function newColors() {
    for (var i = 0; i < squares.length; i++) {
        squares[i].style.borderColor = "black";
    }
    for (var i = 0; i < numberOfSquares; i++) {
        colors[i] = "rgb(" + getRandomRGB().r.toString() + ", " + getRandomRGB().g.toString() + ", " + getRandomRGB().b.toString() + ")"; 
        squares[i].style.borderColor = "white";
    }
    chosenColorRed = getRandomRGB().r.toString();
    chosenColorGreen = getRandomRGB().g.toString();
    chosenColorBlue = getRandomRGB().b.toString();
    colors[getRandomInt(0, numberOfSquares)] = chosenColor = "rgb(" + chosenColorRed + ", " + chosenColorGreen + ", " + chosenColorBlue + ")";
}

function Start() {
    nextButton.disabled = true;
    nextButton.style.color = "grey";
    chosenRed.textContent = chosenColorRed;
    chosenGreen.textContent = chosenColorGreen;
    chosenBlue.textContent = chosenColorBlue;

    for (var i = 0; i < numberOfSquares; i++) {

        squares[i].style.backgroundColor = colors[i];

        squares[i].addEventListener("click", function () {
            if (this.style.backgroundColor == chosenColor) {             
                for (var i = 0; i < numberOfSquares; i++) {
                    squares[i].style.backgroundColor = chosenColor;
                    squares[i].style.borderColor = "white";
                }
                if (!bgameOver) {
                    feedbackText.textContent = "Correct!";
                    score += 100 + scoreBonus;
                    updateBestScore();
                    scoreText.textContent = score.toString();
                    scoreBonus += 50;
                    nextButton.disabled = false;
                    nextButton.style.color = "white";
                    bgameOver = true;                  
                }                
            } else {
                if (!bgameOver) {                   
                    if (this.style.backgroundColor != "black") {
                        feedbackText.textContent = "Try again";
                        scoreLoss += 25;
                        score -= scoreLoss;
                        scoreText.textContent = score.toString();                       
                        this.style.backgroundColor = "black";
                        this.style.borderColor = "black";
                        scoreBonus = 0;                       
                    } else {
                        
                    }
                }
            }
        })
    }
}

function Reset() {
    bgameOver = false;
    score = 0;
    scoreLoss = 0;
    scoreBonus = 0;
    scoreText.textContent = score.toString();
    feedbackText.textContent = "Click the color that represents the above RGB";
    numberOfSquares = difficulty.options[difficulty.selectedIndex].value;
    for (var i = 0; i < squares.length; i++) {
        if (i >= numberOfSquares) {
            squares[i].style.backgroundColor = "black";
        }
    }
    newColors();
    Start();
}

resetButton.addEventListener("click", Reset);

nextButton.addEventListener("click", function () {
    if (bgameOver) {
        bgameOver = false;
        scoreLoss = 0;
        feedbackText.textContent = "Click the color that represents the above RGB";
        newColors();
        Start();
    }
    else {
        return;
    }
});

function updateBestScore() {
    if (score > bestScore) {
        bestScore = score;
        yourBestScore.textContent = bestScore;                  
        updateHighScore();
    }
}

function updateHighScore() {
    $.post('Root/ColorGame/colorgameinfo.php', {highScore: bestScore}, function(data) {
        highScoreText.textContent = data;
    });
}

difficultyDropDown.addEventListener("change", Reset);