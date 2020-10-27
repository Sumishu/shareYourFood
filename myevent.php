<?php 
//session_id('mySessionID');
session_start();
$id=$_SESSION['userid'];
$pass=$_SESSION['password'];
$type=$_SESSION['type'];

//echo 'id='.$id.'pass='.$pass.'type='.$type;
?>
<?php include("class_lib.php"); ?>
<?php
	$web = new Webpage($id!="","event");
	if($id==""){
		$web->changeBody("Log in first!");
		echo $web->getFullContent();
		exit();
	}
	if($type=="user"){ //nope, for both
		header('Location: userevent.php');
		exit();
	}
	if($type=="volunteer"){
		header('Location: volunteerevent.php');
		exit();
	}
	else{
		$web->changeBody("You have to be a volunteer to manage event!");
		echo $web->getFullContent();
		exit();
	}
?>
