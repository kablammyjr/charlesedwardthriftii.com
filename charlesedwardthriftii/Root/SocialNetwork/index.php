<?php
include('classes/DB.php');
include('classes/Login.php');
include('classes/Post.php');
include('classes/Comment.php');
include('classes/Notify.php');
include('classes/PageSwitch.php');
include('classes/Navbar.php');
include('classes/HeaderFooter.php');

$showTimeline = false;

if (Login::isLoggedIn()) {
    $showTimeline = true;
    $userid = Login::isLoggedIn();
    $username = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['username'];
    HeaderFooter::getHeader("Welcome home, ".$username, "Root/SocialNetwork/stylesheets/index.css");
} else {
    HeaderFooter::getHeader("Welcome home", "Root/SocialNetwork/stylesheets/index.css");
}

Navbar::displayNavbar();

if (isset($_GET['postid'])) {
    Post::likePost($_GET['postid'], $userid);
}

if (isset($_POST['comment'])) {
    Comment::createComment($_POST['commentbody'], $_GET['postid'], $userid);
}

echo "<div class='container'>";

// $followingposts = DB::query('SELECT posts.id, posts.body, posts.likes, users.`username` 
// FROM users, posts, followers 
// WHERE posts.user_id = followers.user_id 
// AND users.id = posts.user_id AND follower_id=:userid 
// ORDER BY posts.likes DESC', array(':userid'=>$userid));

// if ($showTimeline) {
//     foreach($followingposts as $post) {

        echo "<div class='timelineposts'>";

            // echo "<div id='postSenderName'><strong><a href='/profile?username=".$post['username']."'>".$post['username']."</a></strong></div>";
            
            // echo "<div id='postBodyText'>".htmlspecialchars($post['body'])."</div>";

            // echo "
            // <form action='/index?postid=".$post['id']."' method='POST'>";

            // if (!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$post['id'], ':userid'=>$userid))) {
            //     echo "<button type='submit' name='like' class='btn btn-primary btn-sm'>Like</button>";
            // } else {
            //     echo "<button type='submit' name='unlike' class='btn btn-primary btn-sm'>Unlike</button>";
            // }
            //     echo " <span>".$post['likes']." likes</span> <br />
            //     <form action='/index?postid=".$post['id']."' method='POST'> <br />
            //         <textarea name='commentbody' cols='30' rows='2'></textarea>
            //         <input type='submit' name='comment' value='Comment'>
            //     </form>
            // </form>
            // <div id='commentText'>
            // ";
            // Comment::displayComments($post['id']);
            // "</div><hr /></br />
            // ";

        echo "</div>";

//     }
// } else {
//     echo '<h3>Log in to follow and view posts!</h3>';
// }

echo "</div>";

HeaderFooter::getFooter("Root/SocialNetwork/scripts/index.js");
?>