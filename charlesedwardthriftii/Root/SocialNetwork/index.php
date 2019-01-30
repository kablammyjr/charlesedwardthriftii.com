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

if (isset($_POST['submitcomment'])) {
  Comment::createComment($_POST['commentbody'], $_GET['commentid'], $userid);
  $ps = new PageSwitch();
  $ps->changePage('/');
  die();
}

?>

<div class='container bg-dark border-right border-left border-success'>

  <div class="row">
    <div class="col-2"></div>
    <div class="col-5 bg-light">
      <div class='timelineposts' style='word-break: break-word;'>

      </div>
    </div>
    <div class="col-2"></div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Comments</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="max-height: 400px; overflow-y: auto">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php
HeaderFooter::getFooter("Root/SocialNetwork/scripts/index.js");
?>