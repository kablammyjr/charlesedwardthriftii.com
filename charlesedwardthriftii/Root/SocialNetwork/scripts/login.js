
$("#login").click(function() {

    $.ajax({
        type: "POST",
        url: "Root/SocialNetwork/api/auth",
        processData: false,
        contentType: "application/json",
        data: '{ "username": "'+ $("#username").val() +'", "password": "'+ $("#password").val() +'" }',
        success: function(r) {
            console.log(r);
        },
        error: function(r) {
            console.log(r);
        }
    });
});