<?php
include('classes/DB.php');
include('classes/Login.php');
include('classes/Post.php');
include('classes/Image.php');
include('classes/Notify.php');
include('classes/Navbar.php');
include('classes/PageSwitch.php');
include('classes/HeaderFooter.php');

$username = "";
$verified = false;
$isFollowing = false;

if (isset($_GET['username'])) {

    HeaderFooter::getHeader($_GET['username']."'s Profile");
    if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))) {
        
        $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
        $userid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
        $verified = DB::query('SELECT verified FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['verified'];
        $followerid = Login::isLoggedIn();

        if (isset($_POST['follow'])) {

            if (Login::isLoggedIn()) {
                if ($userid != $followerid) {
                    if (!DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
                    
                        if ($followerid == 11) {
                        DB::query('UPDATE users SET verified=1 WHERE id=:userid', array(':userid'=>$userid));
                        } 

                        DB::query('INSERT INTO followers VALUES (\'\', :userid, :followerid)', array(':userid'=>$userid, ':followerid'=>$followerid));
                    
                    }

                    $isFollowing = true;
                }
            } else {
                die('You need to be logged in to follow users!');
            }
        }

        if (isset($_POST['unfollow'])) {

            if (Login::isLoggedIn()) {
                if ($userid != $followerid) {
                    if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
                    
                        if ($followerid == 11) {
                            DB::query('UPDATE users SET verified=0 WHERE id=:userid', array(':userid'=>$userid));
                        } 

                        DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid));
                    }
                    $isFollowing = false;
                }
            } else {
                die('You need to be logged in to follow users!');
            }
        }
    

        if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
            
            $isFollowing = true;
        }

        if (isset($_POST['deletepost'])) {
            if (DB::query('SELECT id FROM posts WHERE id=:postid AND user_id=:userid', array(':postid'=>$_GET['postid'], ':userid'=>$followerid))) {
                DB::query('DELETE FROM posts WHERE id=:postid AND user_id=:userid', array(':postid'=>$_GET['postid'], ':userid'=>$followerid));
                DB::query('DELETE FROM post_likes WHERE post_id=:postid', array(':postid'=>$_GET['postid']));
            }
        }

        if (isset($_POST['post'])) {
            if ($_FILES['postimg']['size'] == 0) {
                Post::createPost($_POST['postbody'], Login::isLoggedIn(), $userid);
            } else {
                $postid = Post::createImgPost($_POST['postbody'], Login::isLoggedIn(), $userid);
                Image::uploadImage('postimg', "UPDATE posts SET postimg=:postimg WHERE id=:postid", array(':postid'=>$postid));
            }
        }

        if (isset($_GET['postid']) && !isset($_POST['deletepost'])) {
            if (Login::isLoggedIn()) {
                Post::likePost($_GET['postid'], $followerid);
            } else {
                die('You need to be logged in to like posts!');
            }
        }

        $posts = Post::displayPosts($userid, $username, $followerid);

    } else {
        die('User not found!');
    }
} else {
    if (Login::isLoggedIn()) {
        $userid = Login::isLoggedIn();
        $username = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['username'];

        $ps = new PageSwitch();
        $ps->redirect("/profile?username=".$username);
        die();
    } else {
        $ps = new PageSwitch();
        $ps->redirect("/login");
        die();
    }
}

Navbar::displayNavbar();

?>

<h1><?php echo $username?>'s Profile <?php if ($verified) { echo ' - Verified'; } ?></h1>
<form action="/profile?username=<?php echo $username; ?>" method="POST">
    <?php
    if ($userid != $followerid) {
        if ($isFollowing) {
            echo '<button type="submit" name="unfollow" class="btn btn-secondary">Unfollow</button>';
        } else {
            echo '<button type="submit" name="follow" class="btn btn-secondary">Follow</button>';    
        }
    }
    
    ?>
</form>

<form action="/profile?username=<?php echo $username; ?>" method="POST" enctype="multipart/form-data">
    <textarea name="postbody" cols="80" rows="8"></textarea>
    <br> Upload an image:
    <input type="file" name="postimg"> 
    <input type="submit" name="post" value="Post">
</form>

<div class="posts">
    <?php echo $posts; ?>
</div>

<?php
    HeaderFooter::getFooter();
?>