<?php
include('classes/DB.php');
include('classes/Login.php');
include('classes/Navbar.php');
include('classes/HeaderFooter.php');

HeaderFooter::getHeader("My Account");

Navbar::displayNavbar();



if (Login::isLoggedIn()) {

    $userid = Login::isLoggedIn();

} else {
    die('Not logged in');
}

if (isset($_POST['uploadprofileimg'])) {

    Image::uploadImage('profileimg', 'UPDATE users SET profileimg = :profileimg WHERE id=:userid', array(':userid'=>$userid));
}

?>

<h1>My Account</h1>

<form action="/myaccount" method="POST" enctype="multipart/form-data">
    Upload a profile image:
    <input type="file" name="profileimg">
    <input type="submit" name="uploadprofileimg" value="Upload Image">
</form>

<?php

HeaderFooter::getFooter();