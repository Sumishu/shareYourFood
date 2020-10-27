<?php 
//session_id('mySessionID');
session_start();
?>

<?php include("class_lib.php"); ?>

<?php
	//$web = new Webpage(false,"loggedin");
	//echo $web->getHead();
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "testdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userid= $_REQUEST['userid'];
$password=$_REQUEST['password'];
$type=$_REQUEST['type'];
//user or voulunteer?

if($type=="user")
	$sql = "SELECT user_id FROM user WHERE user_id = '$userid' && password = '$password'";
else
	$sql = "SELECT vol_id FROM volunteer WHERE vol_id = '$userid' && password = '$password'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
	$conn->close();
	$_SESSION["userid"]=$userid;
	$_SESSION["password"]=$password;
	$_SESSION["type"]=$type;

    //echo "Login Success!\n".$type;
	//echo "id=".$_SESSION['userid'].",password=".$_SESSION['password'].",type=".$_SESSION['type'];
	header('Location: index.php');
	//load, user or volunteer homepage
}
else {
	session_unset();
	session_destroy();
	$conn->close();
    echo "Username or password is not correct\n";
}

//echo $web->getFooter();
?>
