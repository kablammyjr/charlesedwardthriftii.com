<?php
include('classes/DB.php');
include('classes/Navbar.php');
include('classes/HeaderFooter.php');

HeaderFooter::getHeader("Messages");

Navbar::displayNavbar();


?>

<div class="container" style="margin: 5% auto; margin-right: 40%;">
    <div class="row">
        <div class="col-4">
            <div class="list-group border border-success" id="users" style="height: 500px; max-height: 500px; overflow:scroll;">
    
            </div>
        </div>
        <div class="col bg-light" id="messagelist" style="height: 500px; max-height: 500px; overflow:scroll;">
            
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            
        </div>
        <div class="col" style="margin-top: 10px; padding: 0;">
            <form>
                <textarea name="messagebody" id="messagebody" cols="95" rows="3" placeholder="Send A Message ..." style="margin-top: 5px;"></textarea>
                <button class='btn btn-outline-success my-2 my-sm-0 float-right' id="sendmessage" type='button'>Send</button>
            </form>
        </div>
    </div>
</div>

<script src='https://code.jquery.com/jquery-3.1.1.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js'></script>
<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js'></script>
<script type="text/javascript">

var SENDER = window.location.hash.split('#')[1];
var USERNAME = "N/A";
$(document).ready(function() {

    $(window).on('hashchange', function() {
        location.reload();
    })

    $('#sendmessage').click(function() {
            $.ajax({
            type: "POST",
            url: "Root/SocialNetwork/api/message",
            processData: false,
            contentType: "application/json",
            data: '{ "body": "'+ $("#messagebody").val() +'", "receiver": "'+SENDER+'" }',
            success: function(r) {
                location.reload();
            },
            error: function(r) {
                console.log(r);
            }
        });
    })

    $.ajax({
        type: "GET",
        url: "Root/SocialNetwork/api/musers",
        processData: false,
        contentType: "application/json",
        data: '',
        success: function(r) {
            r = JSON.parse(r);

            for (var i = 0; i < r.length; i++) {
                $('#users').append('<a href="" id="user'+i+'" data-id="'+r[i].id+'" class="list-group-item list-group-item-action bg-dark text-white"><img class="img-circle" src="https://i.imgur.com/MaB4ztN.jpg" style="width:32px;margin-right:10px;">'+r[i].username+'<span class="badge badge-danger badge-pill float-right">14</span></a>')
                $('#user'+i).click(function() {
                    window.location = 'messages#' + $(this).attr('data-id');
                })
            }

        }, error: function(r) {
            console.log(r);
        }
    })

    $.ajax({
        type: "GET",
        url: "Root/SocialNetwork/api/messages?sender="+SENDER,
        processData: false,
        contentType: "application/json",
        data: '',
        success: function(r) {
            r = JSON.parse(r);

                $.ajax({
                type: "GET",
                url: "Root/SocialNetwork/api/users",
                processData: false,
                contentType: "application/json",
                data: '',
                success: function(u) {

                    USERNAME = u;
                    for (var i = 0; i < r.length; i++) {
                        if (r[i].Sender == USERNAME) {
                            $('#messagelist').append('<div class="row" style="margin: 5px auto;"><div class="col bg-light text-white float-left rounded"></div><div class="col-1"></div><div class="col bg-danger text-white float-right rounded"><h5>'+r[i].body+'</h5></div></div>');
                        } else {
                            $('#messagelist').append('<div class="row" style="margin: 5px auto;"><div class="col bg-warning text-white float-left rounded"><h5>'+r[i].body+'</h5></div><div class="col-1"></div><div class="col bg-light text-white float-right rounded"></div></div>');
                        }
                    }
                }, error: function(r) {
                    console.log(r);
                }
            })

        }, error: function(r) {
            console.log(r);
        }
    })

})









</script>
</body>
</html>