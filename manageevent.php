<?php 
//session_id('mySessionID');
session_start();
$id=$_SESSION['userid'];
$pass=$_SESSION['password'];
$type=$_SESSION['type'];
date_default_timezone_set('Asia/Dhaka');

//echo 'id='.$id.'pass='.$pass.'type='.$type;
?>
<?php include("class_lib.php"); ?>
<?php
	$web = new Webpage($id!="","event");
	if($type=="user"){
		header('Location: addevent.php');
	}
	if($id==""){
		$web->changeBody("Sign in first as user to add event!");
		echo $web->getFullContent();
		exit();
	}
	else if($type!='volunteer'){
		$web->changeBody("You have to be a volunteer to manage event!");
		echo $web->getFullContent();
		exit();
	}
	else{
		//echo $web->getHead();
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
	
	$table="event";
	
	$sql= "SELECT * FROM event NATURAL JOIN food_packet WHERE event.status='available';"; //or requested.... complex query
			//and both time is greater than now!
	$result = $conn->query($sql);
	
	$web->add('
		<div class="topnav">
			<a class="active" href="manageevent.php">Search New Events</a>
			<a href="myevent.php">My Events</a>
		</div>'
	);

	if ($result->num_rows > 0){
		while($info=$result->fetch_assoc()){
			
			//check if expired
			$event_time=date($info['available_date_time']);
			$expire_time=date($info['expire_date_time']);

			$cur_time= date("Y-m-d H:i:s");

			$tm=new DateTime($cur_time);
			$tm1=new DateTime($event_time);
			$tm2=new DateTime($expire_time);

			$web->add("<br>cur time ".$cur_time.'<br>event time '.$event_time.'<br>food expire '.$expire_time);

			$state=$info['status'];
			if($tm>$tm1 || $tm>$tm2){
				$web->add('<br>'.$info['event_id']." is expired by time");
				$state='expired';
				continue;
				//don't delete it by volunteer, rather by user.
				$table="event";
				$evn=$info['event_id'];
				$sql = "UPDATE $table SET status='expired' WHERE event_id='$evn';";

				$web->add($sql);

				if ($conn->query($sql) === TRUE) {
					$web->add("<br>Event information updated!"); ///comment
				}
				else{
					$web->add("Error: " . $sql . "<br>" . $conn->error);///comment
					//$web->add("Some error occured in database!");
				}
				$state='expired';
				continue;
			}

			$button="take".$info['event_id'];
			
			if(isset($_REQUEST[$button]) && $state=='available'){
				///add it for him...

				//insert into user contribution...
				$table="volunteer_contribution";
				$evn=$info['event_id'];
				$sql = "INSERT INTO $table (vol_id, event_id) VALUES ('$id', '$evn');";
				$web->changeBody("");		
				//$web->add($sql);

				if ($conn->query($sql) === TRUE) {
					$web->add("<br>Contribution insert Success!");

					//update event state
					$table='event';//UPDATE t1 SET col1 = col1 + 1, col2 = col1;
					$ttd=$info['available_date_time'];
					$sql = "UPDATE $table SET status='requested', available_date_time='$ttd' where event_id='$evn';";
					//$web->add($sql);
					if ($conn->query($sql) === TRUE) {
						//$web->add("<br>Event information updated!");
					}
					else{
						//$web->add("Error: " . $sql . "<br>" . $conn->error);
						$web->add("Some error occured in database!");
					}
				}
				else {
					$web->add("Error: " . $sql . "<br>" . $conn->error);
				}

				$web->add('<font color="green">Taken Event#'.$info['event_id'].'</font>');
				$web->add('
				<p>
				Event Location: '.$info['location'].'
				<br>
				Available upto: '.$info['available_date_time']);
				$sql= "SELECT user_id FROM user_contribution WHERE event_id=".$info['event_id'].";";
					//and both time is greater than now!
				$res = $conn->query($sql);
				$web->add("<br><b>Contributing users:</b><br>");
				if ($res->num_rows > 0){
					while($inf=$res->fetch_assoc()){
						$web->add('<a href="profile.php?type=user&&id='.$inf['user_id'].'">'.$inf['user_id'].'</a><br>');
					}
				}
				else{
					$web->add('<br><font color="red">Some error Occured!</font>');
				}
				$web->add('
					<br><b>Food Information</b><br>
					Type: '.$info['type'].'
					<br>Details: '.$info['details'].'
					<br>Number of Packet: '.$info['amount'].'
					<br>Food Expire date&time: '.$info['expire_date_time'].'
					</p>'
				);

				$conn->close();
				echo $web->getFullContent();
				exit();
			}
			else{
				$web->add('<div id='.$info['event_id'].'>');

				$web->add('
				<p>
				<h4>Event #'.$info['event_id'].'</h4>
				Event Location: '.$info['location'].'
				<br>
				Available upto: '.$info['available_date_time']);

				$button1="user".$info['event_id'];
				//echo "<br>button is ".$button1;
				if(isset($_REQUEST[$button1])){
					//$web->add("Clicked ".$button1);
					$sql= "SELECT user_id FROM user_contribution WHERE event_id=".$info['event_id'].";";
					//and both time is greater than now!
					$res = $conn->query($sql);
					$web->add("<br><b>Contributing users:</b><br>");
					if ($res->num_rows > 0){
						while($inf=$res->fetch_assoc()){
							$web->add('<a href="profile.php?type=user&&id='.$inf['user_id'].'">'.$inf['user_id'].'</a><br>');
						}
					}
					else{
						$web->add('<br><font color="red">Some error Occured!</font>');
					}
				}
				else{
					$web->add('
					<form name="show'.$info['event_id'].'" Method ="POST" Action ="manageevent.php#'.$info['event_id'].'">
					<br><input type="submit" value="See Contributor List " name="user'.$info['event_id'].'">
					</form>');
				}
				$web->add('
					<br><b>Food Information</b><br>
					Type: '.$info['type'].'
					<br>Details: '.$info['details'].'
					<br>Number of Packet: '.$info['amount'].'
					<br>Food Expire date&time: '.$info['expire_date_time'].'
					<br>
					<form name="add'.$indx.'" Method ="POST" Action =?>
						<input type="submit" value ="Take" name="take'.$info['event_id'].'">
					</form>

					</p>');
			}

			$web->add('</div>');
			$web->add('<br><br>');
		}
	}
	else{
		$web->add("<br>No available Foods right now!");
	}
	$conn->close();
	echo $web->getFullContent();

?>
