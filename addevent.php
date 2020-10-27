<?php 
//session_id('mySessionID');
session_start();
$id=$_SESSION['userid'];
$pass=$_SESSION['password'];
$type=$_SESSION['type'];

//echo 'id='.$id.'pass='.$pass.'type='.$type;<?php 
?>

<?php
$location="";
$datetime1="";
$datetime2="";
$cout="";
$ftype="";
if(isset($_REQUEST['submit'])){
	//echo ('<br>submitted<br>');
	$location=$_REQUEST['location'];
	$datetime1=$_REQUEST['datetime1'];
	$datetime2=$_REQUEST['datetime2'];
	$count=$_REQUEST['count'];
	$ftype=$_REQUEST['type'];
}
else{
	$ftype="snack";
	$count=1;
}
?>

<?php include("class_lib.php"); ?>

<?php
	$web = new Webpage($id!="","event");
	if($id==""){
		$web->changeBody("Sign in first as user to add event!");
		echo $web->getFullContent();
		exit();
	}
	else if($type!='user'){
		$web->changeBody("You have to be a user to add event!");
		echo $web->getFullContent();
		exit();
	}
	else{
		echo $web->getHead();

		echo ('
		<div>
		<form name="addevent" method="POST" action=?>
			<h3> Event Information </h3>
			Location<br>
			<input type="text" name="location" value="'.$location.'"><br>
			Event end Date and Time<br>
			<input type="datetime-local" name="datetime1" value="'.$datetime1.'"><br><br>

			<h3> Food Packet Information </h3>
			Food Type<br>
			<input type="radio" name ="type" value="snack"'); if($ftype=="snack") echo "checked"; echo '>snacks <br>
			<input type="radio" name ="type" value="lunch_dinner"'; if($ftype=="lunch_dinner") echo "checked"; echo ('> lunch/dinner
			<br>

			Details(food items)</br>
			<input type="text" name="details"><br>
			Packet count<br>
			<input type="number" name="count" value="'.$count.'"><br>
			Expired Date and Time<br>
			<input type="datetime-local" name=datetime2 value="'.$datetime2.'"><br>

');
?>

<?php
if(isset($_REQUEST['submit'])){
	if($location=="")
		echo "*Location is required!";
	else if($datetime1=="")
		echo "*Event end Date and Time is required!";
    else if(!isset($count))
		echo "*Packet count is required!";
	else if(!is_numeric($count))
		echo "*Packet count should be an integer";
	else if($datetime2=="")
		echo "*Food Expired date and time is required!";
	else if($ftype=="" || ($ftype!="snack" && $ftype!="lunch_dinner"))
		echo "*Type is required!";
	else{
		echo "success!";
		
		//connecting to database
		$servername = "localhost";
		$username = "root";
		$pass = "password";
		$dbname = "testdb";

		$conn = new mysqli($servername, $username, $pass, $dbname);

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		///insert into table ...
	}
}
?>


<?php
	echo('
			<br>
			<input type="submit" name="submit" value="Submit">
			
		</form>
		</div>');
	}
	echo $web->getFooter();
?>
