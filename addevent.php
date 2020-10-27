<?php 
	//session_id('mySessionID');
	session_start();
	$id=$_SESSION['userid'];
	$pass=$_SESSION['password'];
	$type=$_SESSION['type'];

	$event_id=$_SESSION['event_id'];
	//echo 'id='.$id.'pass='.$pass.'type='.$type;
	date_default_timezone_set('Asia/Dhaka');
?>

<?php
$err="";
	function check($field)
	{
		//echo $field;
		//return true;
		$words=array('insert','update','delete','remove','select');

		$nn=strtolower($field);
		$len=sizeof($words);
		for($i=0;$i<$len;$i++){
			if (strpos($nn, $words[$i]) !== false){ //echo 'match'.$words[$i];
				 $err=$field;
				return false;
			}
		}
		return true;
	}
	
	function checkmobile($mobile)
	{
		if(check($mobile)===false) return false;
		//echo $mobile;
		return true;
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
	$servername = "localhost";
	$username = "root";
	$password = "password";
	$dbname = "testdb";

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
?>
<?php
	$location="";
	$ftype="snack";
	$count=1;
	$datetime1= date("Y-m-d\TH:i");
	$datetime2= date("Y-m-d\TH:i");
	//echo $datetime1;echo $datetime2;
	//$datetime1="2018-04-19T18:30";
	//$datetime2="2018-04-19T08:30";
	//$event_id=34;
	if($event_id!=""){
		$sql="select * from event where event_id='".$event_id."';";
		$res=$conn->query($sql);
		$packet_id=-1;
		if ($res->num_rows > 0){
			while($inf=$res->fetch_assoc()){
				$location=$inf['location'];
				$datetime1=$inf['available_date_time'];
				$packet_id=$inf['packet_id'];
				//echo 'packet_id'.$packet_id;
			}
		}
		//echo "packet id ".$packet_id;
		$sql="select * from food_packet where packet_id='".$packet_id."';";
		//echo $sql;
		$res=$conn->query($sql);
		if ($res->num_rows > 0){
			while($inf=$res->fetch_assoc()){
				$datetime2=$inf['expire_date_time'];
				$details=$inf['details'];
				$count=$inf['amount'];
				$ftype=$inf['type'];
			}
		}
	}
	if(isset($_REQUEST['submit'])){
		//echo ('<br>submitted<br>');
		$location=$_REQUEST['location'];
		$datetime1=$_REQUEST['datetime1'];
		$datetime2=$_REQUEST['datetime2'];
		$details=$_REQUEST['details'];
		$count=$_REQUEST['count'];
		$ftype=$_REQUEST['type'];
	}


	$tm1=new DateTime($datetime1);
	$datetime1= $tm1->format('Y-m-d\TH:i');
	$tm2=new DateTime($datetime2);
	$datetime2= $tm2->format('Y-m-d\TH:i');
	//echo $datetime1.$datetime2;
?>

<?php include("class_lib.php"); ?>

<?php
	$web = new Webpage($id!="","event");
	if($type=="volunteer"){
		header('Location: manageevent.php');
	}
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
		$web->add('
		<div class="topnav">
			<a class="active" href="addevent.php">Add a New Event</a>
			<a href="userevent.php">Past Events</a>
		</div>'
		);
		$web->add('
			<form name="addevent" method="POST" action=?>
			<h3> Event Information </h3>
			Location<br>
			<input type="text" name="location" value="'.$location.'"><br>
			Event end Date and Time<br>
			<input type="datetime-local" name="datetime1" value="'.$datetime1.'"><br><br>

			<h3> Food Packet Information </h3>
			Food Type<br>
			<input type="radio" name ="type" value="snack"'); if($ftype=="snack") $web->add("checked"); $web->add('>snacks <br>
			<input type="radio" name ="type" value="lunch_dinner"'); if($ftype=="lunch_dinner") $web->add("checked"); $web->add('> lunch/dinner
			<br>

			Details(food items)</br>
			<input type="text" name="details" value='.$details.'><br>
			Packet count<br>
			<input type="number" name="count" value="'.$count.'"><br>
			Expired Date and Time<br>
			<input type="datetime-local" name=datetime2 value="'.$datetime2.'"><br>

		');
	}
?>

<?php
if(isset($_REQUEST['submit'])){
	$web->add('<h3><font color="red">');
	if($location=="")
		$web->add("Location is required!");
	else if(check($location)===false)
		$web->add("Invalid locaiton!");
	else if($datetime1=="")
		$web->add("Event end Date and Time is required!");
    else if(!isset($count))
		$web->add("Packet count is required!");
	else if($datetime2=="")
		$web->add("Food Expired date and time is required!");
	else if($ftype=="" || ($ftype!="snack" && $ftype!="lunch_dinner"))
		$web->add( "Type is required!");
	else if($count=="" || is_numeric($count)===false)
		$web->add("Packet count should be an integer");
	else if($details!="" && check($details)===false)
		$web->add("Forbidden word in Details!");
	else{
		$web->add("success!");
		

		$userid= $_REQUEST['userid'];
		$password=$_REQUEST['password'];
		$type=$_REQUEST['type'];
		$table="food_packet";
		
		//synchronize

		
		$xx=$tm2->format('YmdHis');


		//add event
		if($event_id==""){ 
			//insert in food_packet
			$sql = "INSERT INTO $table (type, details, amount, expire_date_time) VALUES ('$ftype', '$details', '$count', '$xx')";

			if ($conn->query($sql) === TRUE) {
				$web->add("Packet insert Success!");
			}
			else {
				$web->add("Error: " . $sql . "<br>" . $conn->error); ///finally remove
			}

			//get packet id, done
			$packet_id=-1;
			$sql= "SELECT MAX(packet_id) AS val FROM $table;";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				while($info=$result->fetch_assoc()){
					$packet_id=$info['val'];
				}
			}
			else{
				$web->add("<br>no packet is found!!<br>");
			}
		
			$web->add("</br>packet id is: ".$packet_id.'<br>');

			//insert into event...
			$table='event';

			$xx=$tm1->format('YmdHis');
		
			$sql = "INSERT INTO $table (location, available_date_time, packet_id, status) VALUES ('$location', '$xx', '$packet_id', 'available')";

			if ($conn->query($sql) === TRUE) {
				$web->add("<br>Event insert Success!");
			}
			else {
				$web->add("<br>Error: " . $sql . "<br>" . $conn->error);
				$web->add("<br>Some Error Occured!");
			}
			$event_id=-1;
			$sql= "SELECT MAX(event_id) AS val FROM $table;";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				while($info=$result->fetch_assoc()){
					$event_id=$info['val'];
					//echo '<br>max is :'.$packet_id.'<br>';
				}
			}
			else{
				$web->add("<br>no event is found!!<br>");
			}
	
			$web->add("</br>event id is: ".$event_id.'<br>');
	
			//insert into user contribution...
			$table="user_contribution";
			//Handle multiple user in user contribution
			$sql = "INSERT INTO $table (user_id,event_id) VALUES ('$id', '$event_id');";		

			if ($conn->query($sql) === TRUE) {
				$web->add("<br>Contribution insert Success!....");
				$_SESSION['event_id']=$event_id;
				$web->add("<br><a href=addcontributor.php> Add Contributor?</a>");
				$conn->close();
				//echo $web->getFullContent();///
				header("Location: addcontributor.php");
				exit();
			}
			else {
				$web->add("Error: " . $sql . "<br>" . $conn->error);
			}
		}
		else{  ///update event
				//delete volunteer_contribution
			$table='volunteer_contribution';
			$col='event_id';
			$value=$event_id;
			$sql = "DELETE FROM $table where $col='$value';";
			$web->add($sql);

			if ($conn->query($sql) === TRUE){
				$web->add("<br>vol contribution delete success");
			}
			else $web->add("<br>Error, $table delete");

			$web->add("</br>event id is: ".$event_id.'<br>');
			$web->add("</br>packet id is: ".$packet_id.'<br>');
			$table='event';//(location, available_date_time, packet_id, status) VALUES ('$location', '$xx', '$packet_id', 'available')
			$sql = "UPDATE $table set location='$location', available_date_time='$xx', packet_id='$packet_id', status='available' where packet_id='$packet_id';";
			$web->add($sql);

			if ($conn->query($sql) === TRUE){
				$web->add("event update succes");
				if($packet_id<0){
					echo "Impossible, event id but no packet id";
					$conn->close();
					exit();
				}

				$table='food_packet';
				//(type, details, amount, expire_date_time)('$ftype', '$details', '$count', '$xx')";
				$sql = "UPDATE $table set type='$ftype', details='$details', amount='$count', expire_date_time='$xx' where packet_id='$packet_id';";
				$web->add($sql);

				if ($conn->query($sql) === TRUE){
					$web->add("<br>food packet update Success!");
					$web->add("<br><a href=addcontributor.php> Add Contributor?</a>");
					$_SESSION['event_id']=$event_id;
					$conn->close();
					//echo $web->getFullContent();///
					header("Location: addcontributor.php");
					exit();
				}
				else{
					$web->add("<br>Error: " . $sql . "<br>" . $conn->error);
					$web->add("<br>Some Error Occured!");
				}
			}
			else {
				$web->add("<br>Error: " . $sql . "<br>" . $conn->error);
				$web->add("<br>Some Error Occured!");
			}
		}
		//$conn->close();		
	}
	$web->add('</font></h3>');
}
?>

<?php
	$conn->close();	
	$web->add('
			<br>
			<input type="submit" name="submit" value="Submit">
			
		</form>
		</div>');
	//echo $web->getFooter();
	echo $web->getFullContent();
?>
