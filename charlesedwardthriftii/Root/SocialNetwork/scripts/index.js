
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
                $('.timelineposts').html(
                    $('.timelineposts').html() + "<div id='postSenderName'><strong><a href='/profile?username="+posts[index].PostedBy+"'>"+posts[index].PostedBy+"</a></strong><span style='font-size: 10px;'> on "+posts[index].PostDate+"</span></div><div id='postBodyText'>"+posts[index].PostBody+"</div><button type='button' data-id='"+posts[index].PostId+"' name='like' class='btn btn-primary btn-sm'><span>"+posts[index].Likes+" likes</span></button> <br />"
            
                )

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
                            $("[data-id='"+buttonid+"']").html("<span>"+res.Likes+" likes</span> <br />")
                            console.log(r);
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