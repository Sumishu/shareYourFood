<?php include("class_lib.php"); ?>
<?php
	$web = new Webpage(false,"login");
	
	echo $web->getHead();

$userid= $_REQUEST['userid'];
$password=$_REQUEST['password'];
$confirm=$_REQUEST['confirm'];
$name=$_REQUEST['name'];
$mobile=$_REQUEST['mobile'];
$email=$_REQUEST['email'];
$location=$_REQUEST['location'];
$type=$_REQUEST['type'];
if($type=="") $type="user";
$iderror="";
$passerror="";
if($userid=="" && $password=="" && $confirm=="");
else if($userid=="" || !ctype_alnum($userid)){
	$iderror="Wrong username!";
}
if($password!=$confirm){
	$passerror="Password doesn't match!";
}
else if($userid!="" && ctype_alnum($userid)) {
	$servername = "localhost";

	$username = "root";
	$pass = "password";
	$dbname = "testdb";
	// Create connection
	$conn = new mysqli($servername, $username, $pass, $dbname);
	// Check connection
	if ($conn->connect_error){
		die("Connection failed: " . $conn->connect_error);
	}

	$userid= $_REQUEST['userid'];
	$password=$_REQUEST['password'];
	$type=$_REQUEST['type'];
	//user or voulunteer?
	//echo $userid.$password.$type;
	//echo "<br>";
	if($type=="user") 
		$sql = "SELECT user_id FROM user WHERE user_id = '$userid';";
	else
		$sql = "SELECT user_id FROM volunteer WHERE vol_id = '$userid';";

	$result = $conn->query($sql);

	if ($result->num_rows > 0){
		$iderror="This Already exits!";
	}
	else{
		if($type=="user")
			$sql = "INSERT INTO $type (user_id, password, user_name, mobile_no, email) VALUES ('$userid', '$password', '$name', '$mobile', '$email')";
		else
			$sql = "INSERT INTO $type (vol_id, password, vol_name, mobile_no, email,location) VALUES ('$userid', '$password','$name', '$mobile', '$email','$location')";
		//type has database name

		if ($conn->query($sql) === TRUE) {
			echo "Registration Success!";
			//redirect to some home page
		}
		else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
	$conn->close();
}


echo
('<!center>
<h2><pre> <a href="login.php">Login</a>			 Register</pre> </h2>
<Form name ="form1" Method ="POST" Action =?>
<!volunter or user???>
userid: <INPUT TYPE = "TEXT" VALUE ="'.$userid.'" Name ="userid"> 
<i>'.$iderror.'</i>
<br> password: <input type = "password" value ="'.$password.'" name ="password"> 
<br> confirm password: <input type = "password" value ="'.$confirm.'" name ="confirm"> 
<i>'.$passerror.'</i>
<br> Full Name: <INPUT TYPE = "TEXT" VALUE ="'.$name.'" Name ="name"> 
<br> Mobile No: <INPUT TYPE = "TEXT" VALUE ="'.$mobile.'" Name ="mobile"> 
<br> Email: <INPUT TYPE = "TEXT" VALUE ="'.$email.'" Name ="email"> 
<br> Location: <INPUT TYPE = "TEXT" VALUE ="'.$location.'" Name ="location">
<br><input type="radio" name ="type" value="user"'); 

if($type=="user") echo ("checked"); 
echo ('> User

<br><input type="radio" name ="type" value="volunteer");');

if($type=="volunteer") echo ("checked");
echo ('> Volunteer
<br><INPUT TYPE = "Submit" Name = "Submit1" VALUE = "signup">
</FORM>
<!/center>');

$web->getFooter();
?>
