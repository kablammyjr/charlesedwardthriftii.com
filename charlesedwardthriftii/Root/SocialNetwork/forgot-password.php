<?php
include('classes/DB.php');
include('classes/Mail.php');
include('classes/Navbar.php');
include('classes/HeaderFooter.php');

HeaderFooter::getHeader("Forgot Password");

Navbar::displayNavbar();

if (isset($_POST['resetpassword'])) {

    $cstrong = true;
    $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
    $email = $_POST['email'];
    $user_id = DB::query('SELECT id FROM users WHERE email=:email', array(':email'=>$email))[0]['id'];

    DB::query('INSERT INTO password_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
    Mail::sendMail('Forgot Edboy World Password', "Follow this link to reset your password! <br /> <a href='www.charlesedwardthriftii.com/changepassword?token=$token'>Reset Password</a>", $email);
    echo 'Email sent!';
}

?>

<h1>Forgot Password</h1>
<form action="/forgotpassword" method="POST">
    <input type="text" name="email" value="" placeholder="Email address..."><p></p>
    <input type="submit" name="resetpassword" value="Reset Password">
</form>

<?php

HeaderFooter::getFooter();