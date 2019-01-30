<?php

include_once('DB.php');
include_once('Login.php');


class Navbar {

    public static function displayNavbar() {

        $loggedInId = Login::isLoggedIn();

        $loggedInUsername = DB::query('SELECT username FROM users WHERE id=:id', array(":id"=>$loggedInId))[0]['username'];

        echo "

        <nav class='navbar navbar-expand-lg navbar-dark bg-dark border border-success'>
        <a class='navbar-brand' href='/'>Ed's Hub</a>
        <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
            <span class='navbar-toggler-icon'></span>
        </button>

        <div class='collapse navbar-collapse' id='navbarSupportedContent'>
            <ul class='navbar-nav mr-auto'>
            <li class='nav-item active'>
                <a class='nav-link' href='/'>Home <span class='sr-only'>(current)</span></a>
            </li>
            <li class='nav-item active'>";
            if (!$loggedInId) {
                echo "<a class='nav-link' href='/login'>Profile <span class='sr-only'>(current)</span></a>";
            } else {
                echo "<a class='nav-link' href='/profile?username=".$loggedInUsername."'>Profile <span class='sr-only'>(current)</span></a>";
            }
            echo "
            </li>
            <li class='nav-item active'>";
            if (!$loggedInId) {
                echo "<a class='nav-link' href='/login'>Messages <span class='sr-only'>(current)</span></a>";
            } else {
                echo "<a class='nav-link' href='/messages'>Messages <span class='sr-only'>(current)</span></a>";
            }
            // echo "
            // </li>
            // <li class='nav-item active'>";
            // if (!$loggedInId) {
            //     echo "<a class='nav-link' href='/login'>Notifications <span class='sr-only'>(current)</span></a>";
            // } else {
            //     echo "<a class='nav-link' href='/notifications'>Notifications <span class='sr-only'>(current)</span></a>";
            // }
            echo "
            <li class='nav-item active'>
            <a class='nav-link' href='/colorgame'>RGB <span class='sr-only'>(current)</span></a>
            </li>
            <li class='nav-item'>
            ";

                if (!$loggedInId) {
                    echo "<a class='nav-link' href='/login'>Login</a>";
                } else {
                    echo "<a class='nav-link' href='/logout'>Logout</a>";
                }
        echo "
            <li class='nav-item active'>
            <a class='nav-link' href='/createaccount'>Create Account <span class='sr-only'>(current)</span></a>
            </li>
            <li class='nav-item'>
            </li>
            </ul>
            
            <form class='navbar-form my-2 my-lg-0' autocomplete='off' action='/search' method='GET'>
                <div style='display: inline-block'>
                    <input class='form-control mr-sm-2 sbox' style='padding-bottom: 12px; text-align: left;' type='search' name='result' placeholder='Search' aria-label='Search'>
                </div>";
                //<ul class='list-group autocomplete' style='position:absolute; z-index: 100'>
                echo "
                </ul>
                <button class='btn btn-outline-success my-2 my-sm-0' type='submit'>Search</button>
            </form>
            
            



            
        </div>
        </nav>

        ";
?>

<script src='https://code.jquery.com/jquery-3.1.1.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js'></script>
<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js'></script>

<script type='text/javascript'>

    $('.sbox').keyup(function() {
        $('.autocomplete').html("")
        $.ajax({
                type: "GET",
                url: "Root/SocialNetwork/api/search?query=" + $(this).val(),
                processData: false,
                contentType: "application/json",
                data: '',
                success: function(r) {
                        r = JSON.parse(r);
                        for (var i = 0; i < r.length; i++) {
                                console.log(r[i].body)
                                $('.autocomplete').html(
                                        $('.autocomplete').html() +
                                        '<a href="/profile?username='+r[i].username+'#'+r[i].id+'"><li class="list-group-item"><span>'+r[i].body+'</span></li></a>'
                                )
                        }
                },
                error: function(r) {
                    console.log(r)
                }
        })
    })
    
</script>


<?php

    }
}

?>