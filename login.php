<?php
	session_start();
?>
<?php include("class_lib.php"); ?>
<?php
	function check($field)
	{
		$words=array('insert','update','delete','remove','select', '=',',');

		$nn=strtolower($field);
		$len=sizeof($words);
		for($i=0;$i<$len;$i++){
			if (strpos($nn, $words[$i]) !== false)
				return false;
		}
		return true;
	}
?>

<?php

	$web = new Webpage(false,"login");

	$servername = "localhost";
	$username = "root";
	$pass = "password";
	$dbname = "testdb";

	$conn = new mysqli($servername, $username, $pass, $dbname);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$userid= $_REQUEST['userid'];
	$password=$_REQUEST['password'];
	$type=$_REQUEST['type'];

	$error="";
if(isset($_REQUEST['submit2'])){
	if($userid=="" || check($userid)===false)
		$error="Invalid user_id!";
	else if($password=="" || check($password)===false)
		$error="Invalid password!";
	else if($type!="user" && $type!='volunteer')
		$error="User or Volunteer?";
	else if($error==""){
		if($type=="user")
			$sql = "SELECT user_id FROM user WHERE user_id = '$userid' && password = '$password';";
		else if($type=='volunteer')
			$sql = "SELECT vol_id FROM volunteer WHERE vol_id = '$userid' && password = '$password';";
		else $error="You must choose type user/volunteer!";
		if($errror==""){
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				$conn->close();
				$_SESSION["userid"]=$userid;
				$_SESSION["password"]=$password;
				$_SESSION["type"]=$type;

				$web->changeBody("");
				$web->add("Login Success!<br>".$type);
				$web->add("id=".$_SESSION['userid'].",password=".$_SESSION['password'].",type=".$_SESSION['type']);
				$conn->close();
				//echo $web->getFullContent();
				header('Location: index.php');
				exit();
				//load, user or volunteer homepage
			}
			else{
				$error="<br>Username or password is not correct";
			}
		}
	}
	}
?>

<?php

	session_unset();
	session_destroy();
	$conn->close();

	$web->changeBody('
	<h2><pre> Login			<a href="signup.php">Register</a> </pre></h2>
	<Form name ="form1" Method ="POST" Action=?>
	userid: <INPUT TYPE = "TEXT" VALUE ="'.$userid.'" Name ="userid">
	<br> password: <input type = "password" value ="'.$password.'" name ="password">
	<br><input type="radio" name ="type" value="user"'); if($type=='user') $web->add('checked'); 
	$web->add('> User
	<br><input type="radio" name ="type" value="volunteer"'); if($type=='volunteer') $web->add('checked'); $web->add('> Volunteer
	<br><INPUT TYPE = "Submit" Name = "submit2" VALUE = "Login">
	</FORM>
	');
	$web->add('<font color="red">'.$error.'</font>');
	echo $web->getFullContent();
?>
