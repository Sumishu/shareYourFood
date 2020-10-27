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
	if($type!="user" || $id==""){
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
	$sql= "SELECT * FROM food_packet NATURAL JOIN event NATURAL JOIN user_contribution WHERE user_id='$id'";
	
	$web->add('
		<div class="topnav">
			<a href="addevent.php">Add a New Event</a>
			<a class="active" href="userevent.php">Past Events</a>
		</div>'
	);

	$fl=false;
	
	$web->add('<b>
		<form name=filter action=?>
			<br><input type="checkbox" name="available" value="avaiable"');
	if(isset($_REQUEST['available'])){
		$web->add(' checked');
		if($fl==false) $sql=$sql.' and ('; else $sql=$sql.' or ';
		$fl=true; $sql=$sql."status='available'";
	}
	$web->add('> Available </input>
			<input type="checkbox" name="requested" value="requested"');
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
	//$web->add($sql);

	$result = $conn->query($sql);

	if ($result->num_rows > 0){
		while($info=$result->fetch_assoc()){
			
			$state=$info['status'];
			$event_time=date($info['available_date_time']);
			$expire_time=date($info['expire_date_time']);

			$cur_time= date("Y-m-d H:i:s");

			$tm=new DateTime($cur_time);
			$tm1=new DateTime($event_time);
			$tm2=new DateTime($expire_time);

			$evn=$info['event_id'];
			$event_id=$evn;
			//$web->add("<br>cur time ".$cur_time.'<br>event time '.$event_time.'<br>food expire '.$expire_time);
			
			//delete
			$button='delete'.$info['event_id'];
			if(isset($_REQUEST[$button]) && ($state=='available' || $state=='requested')){
				$web->add("<br>Your Event #".$info['event_id'].' is deleted!');

				//user contribution
				$table='user_contribution';
				$col='event_id';
				$value=$info['event_id'];
				$sql = "DELETE FROM $table where $col='$value';";
				$web->add($sql);

				if ($conn->query($sql) === TRUE){
					$web->add("<br>$table delete success");
				}
				else $web->add("<br>Error, $table delete");

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

				//event
				$table='event';
				$col='event_id';
				$value=$info['event_id'];
				$sql = "DELETE FROM $table where $col='$value';";
				$web->add($sql);

				if ($conn->query($sql) === TRUE){
					$web->add("<br>$table delete success");
				}
				else $web->add("<br>Error, $table delete");

				//food_packet
				$table='food_packet';
				$col='packet_id';
				$value=$info['packet_id'];
				$sql = "DELETE FROM $table where $col='$value';";
				$web->add($sql);

				if ($conn->query($sql) === TRUE){
					$web->add("<br>$table delete success");
				}
				else $web->add("<br>Error, $table delete");
				continue;
			}

			if($state!='expired' && $state!='served'){
				//check if expired

				if($tm>$tm1 || $tm>$tm2){	
					$state='expired';
					$table="event";
					$sql = "UPDATE $table SET status='expired' WHERE event_id='$evn';";

					//$web->add($sql);
					$web->add('<br><font color="red">Sorry! Your Event #'.$evn.' has expired!</font>');
					if ($conn->query($sql) === TRUE) {
						$web->add("<br>Event information updated!");
					}
					else{
						$web->add("Error: " . $sql . "<br>" . $conn->error);
						//$web->add("<br>Some error occured!");
					}
				}
			}


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
				$res = $conn->query($sql);
				$web->add("<br><b>Contributing users:</b><br>");
				if ($res->num_rows > 0){
					while($inf=$res->fetch_assoc()){
						$web->add('<a href="profile.php?type=user&&id='.$inf['user_id'].'"><big>'.$inf['user_id'].'</big></a><br>');
					}
				}
				else{
					$web->add('<br><font color="red">Some error Occured!</font>');
				}
			}
			else{
				$url="$_SERVER[REQUEST_URI]"."#".$info['event_id'];
				$web->add($url);
				$web->add('
					<form name="show'.$info['event_id'].'" Method ="POST" Action ="'.$url.'">
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

			//edit.. done
			$button='edit'.$info['event_id'];
			if(isset($_REQUEST[$button]) && ($state=='available' || $state=='requested')){
				$web->add("edit");
				$conn->close();
				$_SESSION['event_id']=$info['event_id'];
				header('Location: addevent.php');
				exit();
			}
			
			if( ($state=='available' || $state=='requested')){
				$web->add('
				<form name="update_rem'.$info['event_id'].'" Method ="POST" Action ="userevent.php#'.$info['event_id'].'">
				<br><input type="submit" value="Edit" name="edit'.$info['event_id'].'">
				'
				);
			}
			
			$button='edit'.$info['event_id'];
			if($state=='available' || $state=='requested'){
				$web->add('
					<input type="submit" value="Delete" name="delete'.$info['event_id'].'">
					</form>'
				);
			}

			$col="black";
			if($state=='served') 	$col='#007f00';
			if($state=='expired') $col='#7f0000';
			if($state=='given') $col='#cfcf00';
			if($state=='requested') $col='#00007f';
			
			if($state=='requested'){
				$button='approve'.$info['event_id'];
				if(isset($_REQUEST[$button])){
					//event update
					$table='event';
					$col='event_id';
					$value=$event_id;
					$ttm=$info['available_date_time'];
					$sql = "UPDATE $table set status='given', available_date_time='$ttm' WHERE $col='$value';";
					$web->add($sql);
					$state='given';
					if ($conn->query($sql) === TRUE){
						$web->add("<br>$table update success");
					}
					else $web->add("<br>Error, $table update");
				}

				$button='reject'.$info['event_id']; 
				if(isset($_REQUEST[$button])){
					//volunteer contribution
					$table='volunteer_contribution';
					$col='event_id';
					$value=$info['event_id'];
					$sql = "DELETE FROM $table where $col='$value';";
					$web->add($sql);

					if ($conn->query($sql) === TRUE){
						$web->add("<br>vol contribution delete success");
					}
					else $web->add("<br>Error, $table delete");

					//event update
					$table='event';
					$col='event_id';
					$value=$event_id;
					$ttm=$info['available_date_time'];
					$sql = "UPDATE $table set status='available', available_date_time='$ttm' WHERE $col='$value';";
					$web->add($sql);
					$state='available';

					if ($conn->query($sql) === TRUE){
						$web->add("<br> event success");
					}
					else $web->add("<br>Error, $table update");

				}
			}

			$web->add('<br><b>Statas: <i><font color="'.$col.'">'.strtoupper($state).'</font></i></b>');

			if($state=='requested' || $state=='given' || $state=='served'){
				$sql="SELECT * FROM event NATURAL JOIN volunteer_contribution WHERE event_id='$evn';";
				$res=$conn->query($sql);
				if ($res->num_rows > 0){
					while($inf=$res->fetch_assoc()){
						$web->add('<br>Volunteer: <a href="profile.php?type=volunteer&&id='.$inf['vol_id'].'"><big>'.$inf['vol_id'].'</big></a><br>');
					}
				}
			}

			//Approve or decline request
			if($state=='requested'){
				$button='approve'.$info['event_id'];
				$web->add('
					<form name="accept_reject'.$info['event_id'].'" Method ="POST" Action ="userevent.php#'.$info['event_id'].'">
					<br><input type="submit" value="Approve" name="approve'.$info['event_id'].'">'
				);
				$button='reject'.$info['event_id']; 
			
				$web->add('
					<input type="submit" value="Decline" name="reject'.$info['event_id'].'">
					</form>'
				);
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
