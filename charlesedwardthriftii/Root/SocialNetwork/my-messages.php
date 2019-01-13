<?php
include('classes/DB.php');
include('classes/Login.php');
include('classes/Navbar.php');
include('classes/HeaderFooter.php');

HeaderFooter::getHeader("Messenger");

Navbar::displayNavbar();


if (Login::isLoggedIn()) {

    $userid = Login::isLoggedIn();
} else {
    die('Not logged in');
}

echo "<div class='container'>";

if (isset($_GET['mid'])) {
    $message = DB::query('SELECT * FROM messages WHERE id=:mid AND receiver=:receiver OR sender=:sender', array(':mid'=>$_GET['mid'], ':receiver'=>$userid, ':sender'=>$userid))[0];

    echo '<h1>View Message</h1>';
    echo htmlspecialchars($message['body']).'<hr />';

    if ($message['sender'] == $userid) {
        $id = $message['receiver'];
    } else {
        $id = $message['sender'];
    }

     DB::query('UPDATE messages SET `hasread`=1 WHERE id=:mid', array(':mid'=>$_GET['mid']));   
?>

<form action="/sendmessage?receiver=<?php echo $id; ?>" method="POST">
    <textarea name="body" cols="80" rows="8"></textarea>
    <input type="submit" name="send" value="Send Message">
</form>

<?php

} else {

?>
    <h1>My Messages</h1>

<?php

$messages = DB::query("SELECT * FROM messages WHERE sender=:sender OR receiver=:receiver", array(':sender'=>$userid, ':receiver'=>$userid));
    foreach($messages as $message) {
        
        $sender = DB::query("SELECT username FROM users WHERE id=:senderid", array(':senderid'=>$message['sender']))[0]['username'];
        
        if (strlen($message['body']) > 10) {
            $m = substr($message['body'], 0, 10)." ...";
        } else {
            $m = $message['body'];
        }

        if ($message['hasread'] == 0) {

            echo "<a href='/messages?mid=".$message['id']."'><strong>".$m."</strong></a> sent by ".$sender.'<hr />';
        } else {
            echo "<a href='/messages?mid=".$message['id']."'>".$m."</a> sent by ".$message['username'].'<hr />';
        }
    }  
}

echo "</div>";

HeaderFooter::getFooter();

?>