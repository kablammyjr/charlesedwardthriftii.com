<?php
include('classes/DB.php');
include('classes/Mail.php');
include('classes/PageSwitch.php');
include('classes/Navbar.php');
include('classes/HeaderFooter.php');

HeaderFooter::getHeader("Create Account");

Navbar::displayNavbar();


if (isset($_POST['createaccount'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if (!DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {

        if (strlen($username) >= 3 && strlen($username) <= 32) {

            if (preg_match('/[a-zA-Z0-9_-]+/', $username)) {

                if (strlen($password) >= 6 && strlen($password) <= 60) {

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    
                    if (!DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))) {

                    DB::query('INSERT INTO users VALUES (\'\', :username, :password, :email, \'0\', \'\')', array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email));
                    
                    Mail::sendMail('Welcome to the world of Ed boy!', 'Your Ed boy account is as ready as ever to be used to log in to Ed boy\'s website palooza!', $email);
                    
                    $ps = new PageSwitch();
                    $ps->changePage('/login');
                    die();

                    } else {
                        echo 'Email already in use';
                    }
                } else {
                    echo 'Invalid email!';
                }

            } else {
                echo 'Invalid password length!';
            }

            } else {
                echo 'Username selected include invalid characters!';
            }

        } else {
            echo 'Invalid username length!';
        }

    } else {
        echo 'User already exists!';
    }
}

?>

<div class="container">
<h1 class="text-success">Create an Account</h1>
<form action="/createaccount" method="post">
<input type="text" name="username" id="username" value="" placeholder="Username..."><p></p>
<input type="email" name="email" id="email" value="" placeholder="Email..."><p></p>
<input type="password" name="password" id="password" value="" placeholder="Password..."><p></p>
<input type="submit" name="createaccount" id="ca" value="Create Account">
</form>
</div>
<?php

HeaderFooter::getFooter("Root/SocialNetwork/scripts/create-account.js");