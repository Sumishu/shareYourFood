<?php

//available date time is changed by current time!

 
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
	if($type!="volunteer" || $id==""){
		header('Location: myevent.php');
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
	//$sql= "SELECT * FROM event NATURAL JOIN food_packet WHERE event.status='available' or (event.status='requested' and event.event_id = (select event_id from volunteer_contribution where volunteer_contribution.event_id=event.event_id));"; //or requested.... complex query
	//$web->add($sql);
	//exit();
	
	$web->add('
		<div class="topnav">
			<a href="manageevent.php">Search New Events</a>
			<a class="active" href="volunteerevent.php">My Events</a>
		</div>'
	);

	$sql= "SELECT * FROM food_packet NATURAL JOIN event NATURAL JOIN volunteer_contribution WHERE vol_id='$id'";

	$fl=false;
	
	$web->add('<b>
		<form name=filter action=?>
			<br><input type="checkbox" name="requested" value="requested"');
	if(isset($_REQUEST['requested'])){
		$web->add(' checked');
		if($fl==false) $sql=$sql.' and ('; else $sql=$sql.' or ';
		$fl=true; $sql=$sql."status='requested'";
	}
	$web->add('> <font color="#00007f"> Requsted </font> </input>
			<input type="checkbox" name="given" value="given"');
	if(isset($_REQUEST['given'])){
		$web->add(' checked');
		if($fl==false) $sql=$sql.' and ('; else $sql=$sql.' or ';
		$fl=true; $sql=$sql."status='given'";
	}
	$web->add('> <font color="#cfcf00"> Given </font> </input>
			<input type="checkbox" name="served" value="served"');
	if(isset($_REQUEST['served'])){
		$web->add(' checked');
		if($fl==false) $sql=$sql.' and ('; else $sql=$sql.' or ';
		$fl=true; $sql=$sql."status='served'";
	}
	$web->add('> <font color="#007f00"> Served </font> </input>
			<input type="checkbox" name="expired" value="expired"');
	if(isset($_REQUEST['expired'])){
		$web->add(' checked');
		if($fl==false) $sql=$sql.' and ('; else $sql=$sql.' or ';
		$fl=true; $sql=$sql."status='expired'";
	}
	$web->add('> <font color="#7f0000"> Expired </font> </input>
			<br><br><input type="submit" value="Filter" name="filter"> Event Filer </input>
		</form></b><br>
	');

	if($fl==true) $sql=$sql.')';
	$sql=$sql.';';

	$result = $conn->query($sql);

	if ($result->num_rows > 0){
		while($info=$result->fetch_assoc()){
			
			$state=$info['status'];
			if($state=='given'){
				//check if expired
				$event_time=date($info['available_date_time']);
				$expire_time=date($info['expire_date_time']);
				//echo "evnt: ".$event_time." expir ".$expire_time;
				$cur_time= date("Y-m-d H:i:s");

				$tm=new DateTime($cur_time);
				$tm1=new DateTime($event_time);
				$tm2=new DateTime($expire_time);

				$web->add("<br>cur time ".$cur_time.'<br>event time '.$event_time.'<br>food expire '.$expire_time);
				if($tm>$tm1 || $tm>$tm2){
					$web->add("<br>Expired!!!");
					continue;
					$state='expired';
					$table="event";
					$evn=$info['event_id'];
					//$sql = "UPDATE $table SET status='expired' WHERE event_id='$evn';";

					//$web->add($sql);
					$web->add('<br><font color="red">Sorry! Your Event #'.$evn.' has expired!</font>');

					if ($conn->query($sql) === TRUE) {
						$web->add("<br>Event information updated!"); ///comment
					}
					else{
						$web->add("Error: " . $sql . "<br>" . $conn->error);///comment
					}
				}
			}

			//served button pressed
			$button="served".$info['event_id'];			
			if($state=='given' && isset($_REQUEST[$button])){
				///add it for him...
				//insert into volunteer contribution...
				$table="event";
				$evn=$info['event_id'];
				$ttm=$info['available_date_time'];
				$sql = "UPDATE $table SET status='served', available_date_time='$ttm' WHERE event_id='$evn';";
				//$web->changeBody("");
				//$web->add($sql);

				if ($conn->query($sql) === TRUE) {
					$web->add("<br>Event information updated!");
					$state='served';
				}
				else{
					//$web->add("Error: " . $sql . "<br>" . $conn->error);
					$web->add("<br>Some error occured at database!");
				}

				$web->add('<font color="green"><br>Congrats!<br>Served Event#'.$info['event_id'].'</font>');
				continue;
				//$conn->close();
				//echo $web->getFullContent();
				//exit();
			}

			//cancel button pressed
			$button='cancel'.$info['event_id'];
			if(($state=='given' || $state=='requested') && isset($_REQUEST[$button])){
				///add it for him...
				//insert into volunteer contribution...
				$table="event";
				$evn=$info['event_id'];
				$ttm=$info['available_date_time'];
				$sql = "UPDATE $table SET status='available', available_date_time='$ttm' WHERE event_id='$evn';";

				//$web->changeBody("");
				//$web->add($sql);

				if ($conn->query($sql) === TRUE) {
					$web->add("<br>Event information updated!");
					$state='served';
				}
				else{
					//$web->add("Error: " . $sql . "<br>" . $conn->error);
					$web->add("<br>Some error occured at database!");
				}

				//volunteer contribution, can't if taken or served!!!
				$table='volunteer_contribution';
				$col='event_id';
				$value=$info['event_id'];
				$sql = "DELETE FROM $table where $col='$value';";
				$web->add($sql);

				if ($conn->query($sql) === TRUE){
					$web->add("<br>vol contribution delete success");
				}
				else $web->add("<br>Error, $table delete");

				$web->add('<font color="black">Cancelled Event#'.$info['event_id'].'</font>');
				continue;
				//$conn->close();
				//echo $web->getFullContent();
				//exit();
			}
			else{
				$web->add('<div id='.$info['event_id'].'>');

				$web->add('
				<p>
				<h4>Event #'.$info['event_id'].'</h4>
				Event Location: '.$info['location'].'
				<br>
				Available upto: '.$info['available_date_time']); //already taken, just expired time will be enough

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
						<form name="show'.$info['event_id'].'" Method ="POST" Action ="volunteerevent.php#'.$info['event_id'].'">
						<br><input type="submit" value="See Contributor List " name="user'.$info['event_id'].'">
						</form>'
					);
				}
				$web->add('
					<br><b>Food Information</b><br>
					Type: '.$info['type'].'
					<br>Details: '.$info['details'].'
					<br>Number of Packet: '.$info['amount'].'
					<br>Food Expire date&time: '.$info['expire_date_time'].'
					<br>'
				);

				$col="black";
				if($state=='served') $col='green';
				if($state=='expired') $col='red';
				if($state=='given') $col='yellow';
				$web->add('<br><b>Status: <i><font color="'.$col.'">'.strtoupper($state).'</font></i></b>');

				//if not expired, mark as served
				if ($state=='given'){
					$web->add('<form name="add'.$indx.'" Method ="POST" Action =?>
						<br><b><input type="submit" value ="Mark as Served" name="served'.$info['event_id'].'">
						</form>
						</p>'
					);
				}
				$button='cancel'.$info['event_id'];
				if($state=='given' || $state=='requested'){
					$web->add('<form name="cancel'.$indx.'" Method ="POST" Action =?>
						<br><b><input type="submit" value ="Cancel Event" name="cancel'.$info['event_id'].'">
						</form>
						</p>'
					);
				}
			}

			$web->add('</div>');
			$web->add('<br><br>');
		}
	}
	else{
		$web->add("<br>No event found!");
	}
	$conn->close();
	echo $web->getFullContent();

?>
