<?php

include_once('DB.php');
include_once('Login.php');


class Navbar {

    public static function displayNavbar() {

        $loggedInId = Login::isLoggedIn();

        $loggedInUsername = DB::query('SELECT username FROM users WHERE id=:id', array(":id"=>$loggedInId))[0]['username'];

        echo "

        <nav class='navbar navbar-expand-lg navbar-dark bg-dark'>
        <a class='navbar-brand' href='/'>Ed's Hub</a>
        <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
            <span class='navbar-toggler-icon'></span>
        </button>

        <div class='collapse navbar-collapse' id='navbarSupportedContent'>
            <ul class='navbar-nav mr-auto'>
            <li class='nav-item active'>
                <a class='nav-link' href='/'>Home <span class='sr-only'>(current)</span></a>
            </li>
            <li class='nav-item active'>";
            if (!$loggedInId) {
                echo "<a class='nav-link' href='/login'>Profile <span class='sr-only'>(current)</span></a>";
            } else {
                echo "<a class='nav-link' href='/profile?username=".$loggedInUsername."'>Profile <span class='sr-only'>(current)</span></a>";
            }
            echo "
            </li>
            <li class='nav-item active'>";
            if (!$loggedInId) {
                echo "<a class='nav-link' href='/login'>Messenger <span class='sr-only'>(current)</span></a>";
            } else {
                echo "<a class='nav-link' href='/messages'>Messenger <span class='sr-only'>(current)</span></a>";
            }
            echo "
            </li>
            <li class='nav-item active'>";
            if (!$loggedInId) {
                echo "<a class='nav-link' href='/login'>Notifications <span class='sr-only'>(current)</span></a>";
            } else {
                echo "<a class='nav-link' href='/notifications'>Notifications <span class='sr-only'>(current)</span></a>";
            }
            echo "
            </li>
            <li class='nav-item'>
            ";

                if (!$loggedInId) {
                    echo "<a class='nav-link' href='/login'>Login</a>";
                } else {
                    echo "<a class='nav-link' href='/logout'>Logout</a>";
                }
        echo "
            </li>
            </ul>
            <form class='form-inline my-2 my-lg-0' action='/search' method='GET'>
            <input class='form-control mr-sm-2' type='search' name='result' placeholder='Search' aria-label='Search'>
            <button class='btn btn-outline-success my-2 my-sm-0' type='submit'>Search</button>
            </form>
        </div>
        </nav>

        ";



    }









}