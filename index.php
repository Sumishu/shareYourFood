<?php 
session_start();
?>

<?php include("class_lib.php");
	//session_id('mySessionID');
	$id=$_SESSION['userid'];
	$pass=$_SESSION['password'];
	$type=$_SESSION['type'];

	//echo 'id='.$id.'pass='.$pass.'type='.$type;

	$web = new Webpage(false,"home");
	if($id!="")
		$web = new Webpage(true,"home");
	
	//<p>'.'id='.$id.'pass='.$pass.'type='.$type."OK".'</p>
	$web->changeBody('
<p>
<h3>
This is a non profitable site. </br>Our aim is to provide connection between people with volunteers. </br>Sometimes we have some surplus food in different event. And most of the time those foods are wasted. But there are a lot of needy people in this city who can hardly afford a descent meal. And it is tough for a individual to give the foods to those needy people. But there are some volunteer organization. Who are eagerly waiting to help. That\'s our plan - giving a media for the communication between volunteers and individuals.
</h3>
</p>
');
	echo $web->getFullContent();
?>
