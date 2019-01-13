<?php
include('classes/DB.php');
include('classes/Login.php');
include('classes/HeaderFooter.php');

session_start();
$cstrong = true;
$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = $token;
}

if (Login::isLoggedIn()) {

    $userid = Login::isLoggedIn();
} else {
    die('Not logged in');
}

if (isset($_POST['send'])) {
    
    if (!isset($_POST['nocsrf']) || $_POST['nocsrf'] != $_SESSION['token']) {
        die('INVALID TOKEN');
    }

    if (DB::query('SELECT id FROM users WHERE id=:receiver', array(':receiver'=>$_GET['receiver']))) {

        DB::query("INSERT INTO messages VALUES ('', :body, :sender, :receiver, 0)", array(':body'=>$_POST['body'], ':sender'=>$userid, ':receiver'=>htmlspecialchars($_GET['receiver'])));
        echo "Message sent!";
    } else {
        die('Invalid ID!');
    }
    session_destroy();
}

HeaderFooter::getHeader("Send a Message");

?>


<div class="container">

<h1>Send a Message</h1>
<form action="/sendmessage?receiver=<?php echo htmlspecialchars($_GET['receiver']); ?>" method="POST">
    <span>Recipient</span>
    <textarea name="inputreceiver" cols="30" rows="1"></textarea> <br />
    <textarea name="body" cols="80" rows="8"></textarea>
    <input type="hidden" name="nocsrf" value="<?php echo $_SESSION['token']; ?>">
    <input type="submit" name="send" value="Send Message">
</form>

</div>

<?php

HeaderFooter::getFooter();