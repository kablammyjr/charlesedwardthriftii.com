<?php
include('classes/DB.php');
include('classes/Login.php');
include('classes/PageSwitch.php');
include('classes/Navbar.php');
include('classes/HeaderFooter.php');

HeaderFooter::getHeader("Logout");

Navbar::displayNavbar();


if (!Login::isLoggedIn()) {
    die("Not logged in");
}

if (isset($_POST['confirm'])) {
    if (isset($_POST['alldevices'])) {

        DB::query('DELETE FROM login_tokens WHERE user_id=:userid', array(':userid'=>Login::isLoggedIn()));

    } else {
        if (isset($_COOKIES['SNID'])) {
            DB::query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])));
        }
        setcookie('SNID', '1', time()-3600);
        setcookie('SNID_', '1', time()-3600);
    }

    $ps = new PageSwitch();
    $ps->changePage('/login');
    die();
    
}
?>

<h1>Logout of your account</h1>
<p>Are you sure you want to logout?</p>
<form action="/logout" method="post">
    <input type="checkbox" name="alldevices" value="alldevices"> Logout of all devices? <br />
    <input type="submit" name="confirm" value="Confirm">
</form>

<?php

HeaderFooter::getFooter();