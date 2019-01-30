<?php
include('classes/DB.php');
include('classes/Login.php');
include('classes/Post.php');
include('classes/Image.php');
include('classes/Notify.php');
include('classes/Comment.php');
include('classes/Navbar.php');
include('classes/PageSwitch.php');
include('classes/HeaderFooter.php');

$username = "";
$verified = false;
$isFollowing = false;

if (isset($_GET['username'])) {

    HeaderFooter::getHeader($_GET['username']."'s Profile", "https://use.fontawesome.com/releases/v5.6.3/css/all.css", "Root/SocialNetwork/stylesheets/profile.css");
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

        if (isset($_POST['submitcomment'])) {
            Comment::createComment($_POST['commentbody'], $_GET['commentid'], $followerid);
            $ps = new PageSwitch();
            $ps->changePage('/profile?username='.$username);
            die();
        }

        if (isset($_GET['postid']) && !isset($_POST['deletepost'])) {
            if (Login::isLoggedIn()) {
                Post::likePost($_GET['postid'], $followerid);
            } else {
                die('You need to be logged in to like posts!');
            }
        }


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
<div class="container border-left border-right border-success">
<div class="row">
    <div class="col-2"></div>
    <div class="col-5 bg-light">
<h1><?php echo $username?>'s Profile <?php if ($verified) { echo '<span style="color:#2565f9" data-toggle="tooltip" title="Verified"><i class="fas fa-check-circle"></i></span>'; } ?></h1>
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

    <button class='btn btn-outline-success my-2 my-sm-0 bg-dark' type='button' id="newPostButton" onclick="showNewPostModal()">New Post</button>

        <div class="profileposts" style='word-break: break-word;'>
        
        </div>
    </div>
    <div class="col-2"></div>
  </div>
</div>

<div class="modal" id="commentsmodal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Comments</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="max-height: 400px; overflow-y: auto">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" id="newpost" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-success">
                <h5 class="modal-title">New Post</h5>
                <button type="button" class="btn btn-outline-success my-2 my-sm-0 bg-dark" data-dismiss="modal" aria-label="Cancel">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="bg-dark" style="max-height: 400px; overflow-y: auto">
                <form action="/profile?username=<?php echo $username; ?>" method="POST" enctype="multipart/form-data">
                    <textarea name="postbody" cols="65" rows="5"></textarea> 
            </div>
            <div class="modal-footer bg-dark text-success">
                <span class="container float-left">
                    <input type="file" class="btn btn-outline-success my-2 my-sm-0 bg-dark" name="postimg">
                </span>
                <input type="submit" class="btn btn-outline-success my-2 my-sm-0 bg-dark float-right" name="post" value="Post">
                </form>
            </div>
        </div>
    </div>
</div>

<script src='https://code.jquery.com/jquery-3.1.1.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js'></script>
<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js'></script>

<script type="text/javascript">

var start = 5;
var working = false;
$(window).scroll(function() {
    if ($(this).scrollTop() + 1 >= $('body').height() - $(window).height()) {
        if (working == false) {
            working = true;

            $.ajax({
            type: "GET",
            url: "Root/SocialNetwork/api/profileposts?username=<?php echo $username; ?>&start="+start,
            processData: false,
            contentType: "application/json",
            data: '',
            success: function(r) {
                var posts = JSON.parse(r);
                $.each(posts, function(index) {

                    var likeButtonText = "Like";
                        if (posts[index].isliking == false) {
                            likeButtonText = "Like";
                        } else {
                            likeButtonText = "Unlike";
                        }

                        var anycomments = "disabled";
                        var anycommentscolor = "style='background-color: grey;'";
                        if (posts[index].Comments > 0) {
                            anycomments = "";
                            anycommentscolor = "";
                        } else {
                            anycomments = "disabled";
                            anycommentscolor = "style='background-color: grey; border-color: grey'";
                        }

                    if (posts[index].PostImage == "") {

                        $('.profileposts').html(
                            $('.profileposts').html() + "<div id='"+posts[index].PostId+"'><div id='postSenderName'><strong><a href='/profile?username="+posts[index].PostedBy+"'>"+posts[index].PostedBy+"</a></strong><span style='font-size: 11px;'> on "+posts[index].PostDate+"</span></div><div id='postBodyText'>"+posts[index].PostBody+"</div><div><button type='button' name='comments' "+anycommentscolor+" data-postid='"+posts[index].PostId+"' id='commentsButton' class='btn btn-warning btn-sm' "+anycomments+">Comments</button><button type='button' name='like' data-id='"+posts[index].PostId+"' id='likeButton' style='margin: auto 10px' class='btn btn-primary btn-sm'><span>"+likeButtonText+"</span></button><span class='likes' likes-id='"+posts[index].PostId+"'>"+posts[index].Likes+" likes</span><br /><span><form action='/profile?username="+posts[index].PostedBy+"&commentid="+posts[index].PostId+"' method='POST' enctype='multipart/form-data'><textarea placeholder='Comment...' style='margin-top: 10px;' name='commentbody' id='commentbody' cols='40' rows='1'></textarea><button type='submit' name='submitcomment' class='btn btn-outline-success my-2 my-sm-0 bg-dark' style='margin-left: 10px; position: relative; bottom: 12px'>Send</button></form></span></div></div> <br />"
                        )
                    } else {
                        $('.profileposts').html(
                            $('.profileposts').html() + "<div id='"+posts[index].PostId+"'><div id='postSenderName'><strong><a href='/profile?username="+posts[index].PostedBy+"'>"+posts[index].PostedBy+"</a></strong><span style='font-size: 11px;'> on "+posts[index].PostDate+"</span></div><div id='postBodyText'>"+posts[index].PostBody+"</div><a href='"+posts[index].PostImage+"'><img src='' data-tempsrc='"+posts[index].PostImage+"' class='postimg' id='img"+posts[index].PostId+"'></a><div><button type='button' name='comments' "+anycommentscolor+" data-postid='"+posts[index].PostId+"' id='commentsButton' class='btn btn-warning btn-sm' "+anycomments+">Comments</button><button type='button' name='like' data-id='"+posts[index].PostId+"' id='likeButton' style='margin: auto 10px' class='btn btn-primary btn-sm'><span>"+likeButtonText+"</span></button><span class='likes' likes-id='"+posts[index].PostId+"'>"+posts[index].Likes+" likes</span><br /><span><form action='/profile?username="+posts[index].PostedBy+"&commentid="+posts[index].PostId+"' method='POST' enctype='multipart/form-data'><textarea placeholder='Comment...' style='margin-top: 10px;' name='commentbody' id='commentbody' cols='40' rows='1'></textarea><button type='submit' name='submitcomment' class='btn btn-outline-success my-2 my-sm-0 bg-dark' style='margin-left: 10px; position: relative; bottom: 12px'>Send</button></form></span></div></div> <br />"
                        )
                    }

                    $('[data-postid').click(function() {
                        var buttonid = $(this).attr('data-postid');
                        $.ajax({
                            type: "GET",
                            url: "Root/SocialNetwork/api/comments?postid=" + $(this).attr('data-postid'),
                            processData: false,
                            contentType: "application/json",
                            data: '',
                            success: function(r) {
                                var res = JSON.parse(r);
                                showCommentsModal(res);
                            },
                            error: function(r) {
                                console.log(r);
                            }
                        });
                    });

                    $('[data-id').click(function() {
                        var buttonid = $(this).attr('data-id');
                        $.ajax({
                            type: "POST",
                            url: "Root/SocialNetwork/api/likes?id=" + $(this).attr('data-id'),
                            processData: false,
                            contentType: "application/json",
                            data: '',
                            success: function(r) {
                                var res = JSON.parse(r);
                                var likeButtonText = "Like";
                                if (res.isliking == false) {
                                    likeButtonText = "Like";
                                } else {
                                    likeButtonText = "Unlike";
                                }
                                $("[data-id='"+buttonid+"']").html("<span>"+likeButtonText+"</span>")
                                $("[likes-id='"+buttonid+"']").html("<span>"+res.Likes+" likes</span>")
                            },
                            error: function(r) {
                                console.log(r);
                            }
                        });
                    })
                })

                $('.postimg').each(function() {
                    this.src=$(this).attr('data-tempsrc')
                    this.onload = function() {
                        this.style.opacity = '1';
                    }
                })

                scrollToAnchor(location.hash)

                start += 5;
                setTimeout(function() {
                    working = false;
                }, 4000)
                },
                error: function(r) {
                    console.log(r);
                }
            });
        }
    }
});

function scrollToAnchor(aid){
    try {
        if (aid.length) {
            var aTag = $(aid);
            $('html,body').animate({scrollTop: aTag.offset().top},'slow');
        }
    } catch (error) {
        console.log(error);
    }
}






$(document).ready(function() {

    $.ajax({
        type: "GET",
        url: "Root/SocialNetwork/api/profileposts?username=<?php echo $username; ?>&start=0",
        processData: false,
        contentType: "application/json",
        data: '',
        success: function(r) {
            var posts = JSON.parse(r);
            $.each(posts, function(index) {

                var likeButtonText = "Like";
                    if (posts[index].isliking == false) {
                        likeButtonText = "Like";
                    } else {
                        likeButtonText = "Unlike";
                    }

                    var anycomments = "disabled";
                    var anycommentscolor = "style='background-color: grey;'";
                    if (posts[index].Comments > 0) {
                        anycomments = "";
                        anycommentscolor = "";
                    } else {
                        anycomments = "disabled";
                        anycommentscolor = "style='background-color: grey; border-color: grey'";
                    }

                if (posts[index].PostImage == "") {

                    $('.profileposts').html(
                        $('.profileposts').html() + "<div id='"+posts[index].PostId+"'><div id='postSenderName'><strong><a href='/profile?username="+posts[index].PostedBy+"'>"+posts[index].PostedBy+"</a></strong><span style='font-size: 11px;'> on "+posts[index].PostDate+"</span></div><div id='postBodyText'>"+posts[index].PostBody+"</div><div><button type='button' name='comments' "+anycommentscolor+" data-postid='"+posts[index].PostId+"' id='commentsButton' class='btn btn-warning btn-sm' "+anycomments+">Comments</button><button type='button' name='like' data-id='"+posts[index].PostId+"' id='likeButton' style='margin: auto 10px' class='btn btn-primary btn-sm'><span>"+likeButtonText+"</span></button><span class='likes' likes-id='"+posts[index].PostId+"'>"+posts[index].Likes+" likes</span><br /><span><form action='/profile?username="+posts[index].PostedBy+"&commentid="+posts[index].PostId+"' method='POST' enctype='multipart/form-data'><textarea placeholder='Comment...' style='margin-top: 10px;' name='commentbody' id='commentbody' cols='40' rows='1'></textarea><button type='submit' name='submitcomment' class='btn btn-outline-success my-2 my-sm-0 bg-dark' style='margin-left: 10px; position: relative; bottom: 12px'>Send</button></form></span></div></div> <br />"
                    )
                } else {
                    $('.profileposts').html(
                        $('.profileposts').html() + "<div id='"+posts[index].PostId+"'><div id='postSenderName'><strong><a href='/profile?username="+posts[index].PostedBy+"'>"+posts[index].PostedBy+"</a></strong><span style='font-size: 11px;'> on "+posts[index].PostDate+"</span></div><div id='postBodyText'>"+posts[index].PostBody+"</div><a href='"+posts[index].PostImage+"'><img src='' data-tempsrc='"+posts[index].PostImage+"' class='postimg' id='img"+posts[index].PostId+"'></a><div><button type='button' name='comments' "+anycommentscolor+" data-postid='"+posts[index].PostId+"' id='commentsButton' class='btn btn-warning btn-sm' "+anycomments+">Comments</button><button type='button' name='like' data-id='"+posts[index].PostId+"' id='likeButton' style='margin: auto 10px' class='btn btn-primary btn-sm'><span>"+likeButtonText+"</span></button><span class='likes' likes-id='"+posts[index].PostId+"'>"+posts[index].Likes+" likes</span><br /><span><form action='/profile?username="+posts[index].PostedBy+"&commentid="+posts[index].PostId+"' method='POST'><textarea placeholder='Comment...' style='margin-top: 10px;' name='commentbody' id='commentbody' cols='40' rows='1'></textarea><button type='submit' name='submitcomment' class='btn btn-outline-success my-2 my-sm-0 bg-dark' style='margin-left: 10px; position: relative; bottom: 12px'>Send</button></form></span></div></div> <br />"
                    )
                }

                $('[data-postid').click(function() {
                    var buttonid = $(this).attr('data-postid');
                    $.ajax({
                        type: "GET",
                        url: "Root/SocialNetwork/api/comments?postid=" + $(this).attr('data-postid'),
                        processData: false,
                        contentType: "application/json",
                        data: '',
                        success: function(r) {
                            var res = JSON.parse(r);
                            showCommentsModal(res);
                        },
                        error: function(r) {
                            console.log(r);
                        }
                    });
                });

                $('[data-id').click(function() {
                    var buttonid = $(this).attr('data-id');
                    $.ajax({
                        type: "POST",
                        url: "Root/SocialNetwork/api/likes?id=" + $(this).attr('data-id'),
                        processData: false,
                        contentType: "application/json",
                        data: '',
                        success: function(r) {
                            var res = JSON.parse(r);
                            var likeButtonText = "Like";
                            if (res.isliking == false) {
                                likeButtonText = "Like";
                            } else {
                                likeButtonText = "Unlike";
                            }
                            $("[data-id='"+buttonid+"']").html("<span>"+likeButtonText+"</span>")
                            $("[likes-id='"+buttonid+"']").html("<span>"+res.Likes+" likes</span>")
                        },
                        error: function(r) {
                            console.log(r);
                        }
                    });
                })
            })

            $('.postimg').each(function() {
                this.src=$(this).attr('data-tempsrc')
                this.onload = function() {
                    this.style.opacity = '1';
                }
            })

            scrollToAnchor(location.hash)
        },
        error: function(r) {
            console.log(r);
        }
    });
});

function showNewPostModal() {
$('#newpost').modal('show')
}

function showCommentsModal(res) {
$('#commentsmodal').modal('show')

var output = "";
for (var i = 0; i < res.length; i++) {
output += "<a href='/profile?username="+res[i].CommentedBy+"'>"+res[i].CommentedBy+"</a>";
output += "<br />"
output += res[i].Comment;
output += "<hr />";
}

$('.modal-body').html(output)
}

</script>
</body>
</html>