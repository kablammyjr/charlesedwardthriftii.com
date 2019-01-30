<?php
include('classes/DB.php');
include('classes/Login.php');
include('classes/PageSwitch.php');
include('classes/Navbar.php');
include('classes/HeaderFooter.php');

if (Login::isLoggedIn()) {
    $ps = new PageSwitch();
    $ps->redirect('/');
    die();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {

        if (password_verify($password, DB::query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password'])) {
                   
            $cstrong = true;
            $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
            $user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];

            DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
           
            setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
            setcookie("SNID_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
            
            $ps = new PageSwitch();
            $ps->changePage('/');
            die();
        } else {
            echo 'Incorrect password!';
        }

    } else {
        echo 'Username provided is not registered!';
    }
}

HeaderFooter::getHeader("Login");

Navbar::displayNavbar();
?>

<div class='container text-success'>
    <h1>Login to your account</h1>
    <form action="/login" method="post">
    <input type="text" name="username" id="username" value="" placeholder="Username..."><p></p>
    <input type="password" name="password" id="password" value="" placeholder="Password..."><p></p>
    <input type="submit" name="login" id="login" value="Login">
    </form>
    <h5>Don't have an account?</h5>
    <h5><a href="/createaccount">Create Account</a></h5>
</div>

<?php

HeaderFooter::getFooter();