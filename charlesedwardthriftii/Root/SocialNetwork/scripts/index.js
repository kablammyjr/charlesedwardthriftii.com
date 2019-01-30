
$(document).ready(function() {

    $.ajax({
        type: "GET",
        url: "Root/SocialNetwork/api/posts",
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

                    $('.timelineposts').html(
                        $('.timelineposts').html() + "<div id='"+posts[index].PostId+"'><div id='postSenderName'><strong><a href='/profile?username="+posts[index].PostedBy+"'>"+posts[index].PostedBy+"</a></strong><span style='font-size: 11px;'> on "+posts[index].PostDate+"</span></div><div id='postBodyText'>"+posts[index].PostBody+"</div><div><button type='button' name='comments' "+anycommentscolor+" data-postid='"+posts[index].PostId+"' id='commentsButton' class='btn btn-warning btn-sm' "+anycomments+">Comments</button><button type='button' name='like' data-id='"+posts[index].PostId+"' id='likeButton' style='margin: auto 10px' class='btn btn-primary btn-sm'><span>"+likeButtonText+"</span></button><span class='likes' likes-id='"+posts[index].PostId+"'>"+posts[index].Likes+" likes</span><br /><span><form action='/profile?username="+posts[index].PostedBy+"&commentid="+posts[index].PostId+"' method='POST' enctype='multipart/form-data'><textarea placeholder='Comment...' style='margin-top: 10px;' name='commentbody' id='commentbody' cols='40' rows='1'></textarea><button type='submit' name='submitcomment' class='btn btn-outline-success my-2 my-sm-0 bg-dark' style='margin-left: 10px; position: relative; bottom: 12px'>Send</button></form></span></div></div> <br />"
                    )
                } else {
                    $('.timelineposts').html(
                        $('.timelineposts').html() + "<div id='"+posts[index].PostId+"'><div id='postSenderName'><strong><a href='/profile?username="+posts[index].PostedBy+"'>"+posts[index].PostedBy+"</a></strong><span style='font-size: 11px;'> on "+posts[index].PostDate+"</span></div><div id='postBodyText'>"+posts[index].PostBody+"</div><a href='"+posts[index].PostImage+"'><img src='' data-tempsrc='"+posts[index].PostImage+"' class='postimg' id='img"+posts[index].PostId+"'></a><div><button type='button' name='comments' "+anycommentscolor+" data-postid='"+posts[index].PostId+"' id='commentsButton' class='btn btn-warning btn-sm' "+anycomments+">Comments</button><button type='button' name='like' data-id='"+posts[index].PostId+"' id='likeButton' style='margin: auto 10px' class='btn btn-primary btn-sm'><span>"+likeButtonText+"</span></button><span class='likes' likes-id='"+posts[index].PostId+"'>"+posts[index].Likes+" likes</span><br /><span><form action='/profile?username="+posts[index].PostedBy+"&commentid="+posts[index].PostId+"' method='POST'><textarea placeholder='Comment...' style='margin-top: 10px;' name='commentbody' id='commentbody' cols='40' rows='1'></textarea><button type='submit' name='submitcomment' class='btn btn-outline-success my-2 my-sm-0 bg-dark' style='margin-left: 10px; position: relative; bottom: 12px'>Send</button></form></span></div></div> <br />"
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
        },
        error: function(r) {
            console.log(r);
        }
    });
});

function showCommentsModal(res) {
    $('.modal').modal('show')

    var output = "";
    for (var i = 0; i < res.length; i++) {
        output += "<a href='/profile?username="+res[i].CommentedBy+"'>"+res[i].CommentedBy+"</a>";
        output += "<br />"
        output += res[i].Comment;
        output += "<hr />";
    }

    $('.modal-body').html(output)
}