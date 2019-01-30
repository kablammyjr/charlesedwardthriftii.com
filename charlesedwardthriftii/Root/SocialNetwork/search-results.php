<?php
include('classes/DB.php');
include('classes/Login.php');
include('classes/PageSwitch.php');
include('classes/Navbar.php');
include('classes/HeaderFooter.php');

HeaderFooter::getHeader("Search Results", "Root/SocialNetwork/stylesheets/search-results.css");

Navbar::displayNavbar();

if (isset($_GET['result'])) {

    $tosearch = explode(" ", $_GET['result']);

    if (count($tosearch) == 1) {
        $tosearch = str_split($tosearch[0], 2);
    }

    $paramsarray = array(':username'=>'%'.$_GET['result'].'%');

    $whereclause = "";
    for ($i = 0; $i < count($tosearch); $i++) {
        $whereclause .= " OR username LIKE :u$i ";
        $paramsarray[":u$i"] = $tosearch[$i];
    }

    $users = DB::query('SELECT users.username FROM users WHERE users.username LIKE :username '.$whereclause.'', $paramsarray);

    echo "<div class='container text-success'>";

    echo "<h1>Search Results</h1><hr />";
    echo "<h3>People</h3><hr />";

    if (!$users) {
        echo "<div>No people match your search</div><br />";
    }

    foreach($users as $user) {
        echo "<div id='userresult'><a class='text-primary' href='/profile?username=".$user['username']."'>".$user['username']."</a></div><hr />";
    }

    $paramsarray = array(':body'=>'%'.$_GET['result'].'%');

    $whereclause = "";
    for ($i = 0; $i < count($tosearch); $i++) {
        if ($i % 2) {
            $whereclause .= " OR body LIKE :p$i ";
            $paramsarray[":p$i"] = $tosearch[$i];
        }
    }

    $posts = DB::query('SELECT posts.body FROM posts WHERE posts.body LIKE :body '.$whereclause.'', $paramsarray);

    echo "<h3>Posts</h3><hr />";

    if (!$posts) {
        echo "<div>No posts match your search</div><br />";
    }

    foreach($posts as $post) {
        echo "<div class='text-info' id='postresult'>".htmlspecialchars($post['body'])."</div><hr />";
    }
} else {
    $ps = new PageSwitch();
    $ps->changePage('/');
    die();
}
echo "</div>";

HeaderFooter::getFooter();

?>