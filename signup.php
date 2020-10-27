<?php include("class_lib.php"); ?>
<?php
	function check($field)
	{
		$words=array('insert','update','delete','remove','select');

		$nn=strtolower($field);
		$len=sizeof($words);
		for($i=0;$i<$len;$i++){
			if (strpos($nn, $words[$i]) !== false)
				return false;
		}
		return true;
	}
	
	function checkmobile($mobile)
	{
		if(check($mobile)===false) return false;
		$len=strlen($mobile);
		if($len!=11)
			return false;
		if($mobile[0]!='0') return false;
		if($mobile[1]!='1') return false;
		for($i=2;$i<$len;$i++){
			if($mobile[$i]>='0' && $mobile[$i]<='9') continue;
			else 
				return false;
		}
		return true;
	}
	function checkemail($email)
	{
		if(check($email)===false) return false;
		if (strpos($email, '.') === false)
				return false;
		
		if (strpos($email, '@') === false)
				return false;

		return true;
	}
?>

<?php
	$web = new Webpage(false,"login");
	
	//echo $web->getHead();
//mobile no, lenth, first digits, all are digit
//email a@b.c


$userid= $_REQUEST['userid'];
$password=$_REQUEST['password'];
$confirm=$_REQUEST['confirm'];
$name=$_REQUEST['name'];
$mobile=$_REQUEST['mobile'];
$email=$_REQUEST['email'];
$location=$_REQUEST['location'];
$type=$_REQUEST['type'];

$error="";	
//sql injection
if(isset($_REQUEST['submit1'])){
	//$web->add('submitted');
	if(check($userid)===false || $userid=="")
		$error="Invalid user_id!";
	else if(check($password)===false || $password=="")
		$error="Invalid/Weak password!";
	else if(check($confirm)===false)
		$error="Invalid confirm!";
	else if(check($name)===false ||$name=="")
		$error="Invalid name!";
	else if($mobile=="" || checkmobile($mobile)===false)
		$error="Invalid mobile!";
	else if($email=="" || checkemail($email)===false)
		$error="Invalid email!";
	else if($location!="" && check($location)===false)
		$error="Invalid location!";
	else if($userid=="" || !ctype_alnum($userid)){
		$error="Wrong username!";
	}
	else if($password!=$confirm){
		$error="Password doesn't match!";
	}
	else if($type!='user' && $type!='volunteer')
		$error='Choose a type!';
	else if($type=='volunteer' && $location=="")
		$error="To be a volunteer, you must have a location!";
	//else if(ctype_alnum($userid)){
	//	$error="username has to be alpha-neumeric!";
	
	else{
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
	//user name
		if($error==""){
			if($type=="user")
				$sql = "SELECT * FROM user WHERE user_id = '$userid';";
			else
				$sql = "SELECT * FROM volunteer WHERE vol_id = '$userid';";

			$result = $conn->query($sql);

			if ($result->num_rows > 0){
				$error="Username already exits!";
			}
		}

	//email
		if($error==""){
			$sql = "SELECT * FROM $type WHERE email = '$email';";

			$result = $conn->query($sql);

			if ($result->num_rows > 0){
				$error="email addresss already exits!";
			}
		}
	
		//mobile
		if($error==""){
			$sql = "SELECT * FROM $type WHERE mobile_no = '$mobile';";

			$result = $conn->query($sql);

			if ($result->num_rows > 0){
				$error="Mobile number already exits!";
			}
		}
	
		if($error==""){
				if($type=="user")
					$sql = "INSERT INTO $type (user_id, password, user_name, mobile_no, email) VALUES ('$userid', '$password', '$name', '$mobile', '$email')";
				else
					$sql = "INSERT INTO $type (vol_id, password, vol_name, mobile_no, email,location) VALUES ('$userid', '$password','$name', '$mobile', '$email','$location')";
				//type has database name

				if ($conn->query($sql) === TRUE) {
					$web->changeBody("Registration Success!");
					$conn->close();
					echo $web->getFullContent();
					exit();
				}
				else {
					$error='Sorry! Some error occured at database!';
					//$error="Error: " . $sql . "<br>" . $conn->error; ///noooope
				}
		}
		$conn->close();
	}
	//$web->add('added');
}
?>

<?php
	$web->add('
	<!center>
	<h2><pre> <a href="login.php">Login</a>			 Register</pre> </h2>
	<Form name ="form1" action =?>
	<!volunter or user???>
	userid: <INPUT TYPE = "TEXT" VALUE ="'.$userid.'" Name ="userid"> 
	<br> password: <input type = "password" value ="'.$password.'" name ="password"> 
	<br> confirm password: <input type = "password" value ="'.$confirm.'" name ="confirm"> 
	<br> Full Name: <INPUT TYPE = "TEXT" VALUE ="'.$name.'" Name ="name"> 
	<br> Mobile No: <INPUT TYPE = "TEXT" VALUE ="'.$mobile.'" Name ="mobile"> 
	<br> Email: <INPUT TYPE = "TEXT" VALUE ="'.$email.'" Name ="email"> 
	<br> Location: <INPUT TYPE = "TEXT" VALUE ="'.$location.'" Name ="location">
	<br><input type="radio" name ="type" value="user"'); 

if($type=="user") $web->add("checked"); 
$web->add('> User
		<br><input type="radio" name ="type" value="volunteer"');

if($type=="volunteer") $web->add("checked");
$web->add('> Volunteer
	<br><input type = "submit" name = "submit1" value = "signup">
	</form>
	<!/center>');
$web->add('<font color="red">'.$error.'</font>');
echo $web->getFullContent();
//$web->getFooter();
?>
