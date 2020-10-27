<?php
//done 
//session_id('mySessionID');
session_start();
$id=$_SESSION['userid'];
$pass=$_SESSION['password'];
$type=$_SESSION['type'];
$event_id=$_SESSION['event_id'];
$error="";
//echo 'id='.$id.'pass='.$pass.'type='.$type.'event_id = '.$event_id; 
?>
<?php include("class_lib.php"); ?>
<?php 
	//header
	$web = new Webpage($id!="","event");
	//echo $web-> getHead();
	
	if($id=="" || $type!="user" || $event_id==""){
		$web->add("You Don't have the permission to do anything here!");
		echo $web->getFullContent();
		exit();
	}
	else if(isset($_REQUEST['done'])){
		
		$web->add("Event uploded Successfully!");
		echo $web->getFullContent();
		unset($_SESSION['event_id']);
		exit();
	}
	else{
		$web->add("Add Co-Contributors:<br>"); ///not actual output!	
	}
?>

<?php

	$contributor=array();

	$servername = "localhost";
	$username = "root";
	$password = "password";
	$dbname = "testdb";
	
	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$table="user_contribution";
	
	$sql= "SELECT user_id FROM user_contribution WHERE event_id ='$event_id' and user_id <>'$id';";

	$result=$conn->query($sql);

	if ($result->num_rows > 0) {
		while($info=$result->fetch_assoc()){
			array_push($contributor, $info['user_id']);
		}
	}	
	//$conn->close();
?>

<?php
	//remove from database
	$len = count($contributor);
	for($i = 0; $i < $len; $i++) {
		$button="rm_".$contributor[$i];
		//echo '<br>'.$button;
		if(isset($_REQUEST[$button])){
			$sql= "DELETE FROM user_contribution WHERE user_id='$contributor[$i]' and event_id='$event_id';";
			//echo $sql;
			if (($conn->query($sql) === TRUE)) {
				$web->add("user removed successfully!");
				$contribotor[$i]="";
				$conn->close();
				header('Location: addcontributor.php');
				die();
			}
			else{
				$error="<br>Error: " . $sql . "<br>" . $conn->error; ///finally remove
			}
		}
	}
	
?>

<?php
    if(isset($_REQUEST['add'])){

		$cid = $_REQUEST['cid'];
		$len=count($contributor);
		$ok=true;
		if($cid==$id){
			 $error='<br>You are a contributor by default!';
			$ok=false;
		}
		for($i=0;$i<$len;$i++){
			if($contributor[$i]==$cid){
				$ok=false;
				$error='<br><font color="red">User: "'.$cid.'" already in the list!</font>';
				break;
			}
		}
		if($ok===true){
			
			$table="user_contribution";

			$user=$_REQUEST['cid'];
			
			//first find, if cid is valid... or in error message, just say it :p

			$sql= "SELECT * FROM user WHERE user_id='$user';";
			
			$result= $conn->query($sql);

			if ($result->num_rows ==0){
				$error='<br><font color="red">Unknow user_id!</font>';
			}
			else{

				$sql= "INSERT INTO user_contribution (user_id, event_id) VALUES ('$user', '$event_id')";
				if (($conn->query($sql) === TRUE)) {
					//$web->add("user added successfully!");
					array_push($contributor,$cid);
					//$conn->close();
					
					//header('Location: '.$_SERVER['REQUEST_URI']);
					//header('Location: addcontributor.php');
					//die();
				}
				else{
					$error="<br>Error: " . $sql . "<br>" . $conn->error; ///finally remove
				}
			}
		}
	}

?>

<?php
	//remove
	$len = count($contributor);
	for($i = 0; $i < $len; $i++) {
		if($contributor[$i]=="")
			continue;
		//<a hfref="profile.php?user_id='.$contributor[$i].'&&type=user"'.$contributor[$i].'</a>
		$web->add('
			<form name="remove'.$contributor[$i].'" action=?>
				<br><a href="profile.php?user_id='.$contributor[$i].'&&type=user">'.$contributor[$i].'</a> <input type=submit name="rm_'.$contributor[$i].'" value="remove">
			</form>');
	}
	
?>

<?php
	$web->add('
	<br>
	<form name="addcon" action=?>
	User Id <input type="text" name="cid">
	<input type="submit" name="add" value="Add User">
	</form>


	<br>
	<form name="done" action=?>
	<input type="submit" value="Done" name="done">
	</form>');
?>

<?php
	$web->add($error);
	$conn->close();
	echo $web->getFullContent();
?>

