<?php


class HeaderFooter {

    public static function getHeader($title, $stylesheet = false, $stylesheet2 = false) {

        echo "

        <!DOCTYPE html>
        <html lang='en' xmlns='http://www.w3.org/1999/xhtml'>
        <head>
        <meta charset='utf-8' />
        <title>".$title."</title>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css' integrity='sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS' crossorigin='anonymous'>
        "; 
        
        if ($stylesheet) {
            echo "<link rel='stylesheet' href=".$stylesheet.">";
        }
        if ($stylesheet2) {
            echo "<link rel='stylesheet' href=".$stylesheet2.">";
        }

        echo "
        </head>
        <body class='bg-dark'>

        ";


    }

    public static function getFooter($jsfile = false, $js = false) {

        echo "
        <script src='https://code.jquery.com/jquery-3.1.1.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js'></script>
        <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js'></script>
        ";
        if ($jsfile) {
        echo "<script type='text/javascript' src='".$jsfile."'></script>";
        }
        if ($js) {
        echo "<script type='text/javascript'>".$js."</script>";
        }
        echo "
        </body>
        </html>
        ";

    }



}