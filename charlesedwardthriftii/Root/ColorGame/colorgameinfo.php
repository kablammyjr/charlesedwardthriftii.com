<?php
require 'mysqli_connect.php';

$query = "SELECT score FROM highscore";
$response = mysqli_query($con, $query);

if($response) {
    while($row = mysqli_fetch_array($response)) { 

        if (isset($_POST['highScore'])) {
            $posted_score = $_POST['highScore'];

            if ($posted_score > $row['score'])
            {               
                $query2 = "UPDATE highscore SET score = $posted_score";

                $stmt = mysqli_prepare($con, $query2);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                $query = "SELECT score FROM highscore";
                $response = mysqli_query($con, $query);
                while($row = mysqli_fetch_array($response)) { 
                echo $row['score'];
                }
            } else {
                echo $row['score'];
            }
        } else {
            echo $row['score'];
        }       
    } 
}
?>