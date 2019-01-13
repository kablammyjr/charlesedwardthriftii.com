<?
$con = mysqli_connect('examplehostaddress', 'exampledatabase', 'examplepassword');
mysqli_select_db($con, 'exampledatabase');

if($con->connect_errno){
    alert("Connect failed: %s\n", $con->connect_error);
    exit();
}

?>