<?php 
	//session_id('mySessionID');
	session_start();

	$id=$_REQUEST['id'];
	$type=$_REQUEST['type'];
	$ok=true;
	if(!isset($_REQUEST['id']) || !isset($_REQUEST['id'])){
		$id=$_SESSION['userid'];
		$type=$_SESSION['type'];
		if($id=="" || $type==""){
			$ok=false;
		}
	}
	//echo 'id='.$id.'type='.$type;
?>
<?php include("class_lib.php"); ?>
<?php
	$web = new Webpage($id!="","profile");
	echo $web->getHead();

	if(!$ok){
		echo "You have to login First";
		echo $web->getFooter();
		exit();
	}
?>

<?php
$totalevent=-1;
$totalpacket=-1;

$givenevent=-1;
$givenpacket=-1;

$servedevent=-1;
$servedpacket=-1;

$availableevent=-1;
$availablepacket=-1;

$expiredevent=0;
$expiredpacket=0;

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

	$table=$type;
	if($type=="user"){
		$sql= "SELECT * FROM user WHERE user_id='$id';";
		$result = $conn->query($sql);
		if ($result->num_rows > 0){
			while($info=$result->fetch_assoc()){
				echo "<br>User<br>";
				echo ' ID: '.$info['user_id'].'
					<br> Name: '.$info['user_name'].'
					<br> Mobile No: '.$info['mobile_no'].'
					<br> Email Address: '.$info['email'];
				echo '<br>';

				//total
				$sql= "SELECT user_id, count(user_id) AS totalevent, sum(amount) as totalpacket  FROM user_contribution NATURAL JOIN event JOIN food_packet on event.packet_id=food_packet.packet_id group by user_id having user_id='$id';";
				//echo $sql;
				$res = $conn->query($sql);
				if($res->num_rows > 0){
					while($inf=$res->fetch_assoc()){
						$totalevent=$inf['totalevent'];
						$totalpacket=$inf['totalpacket'];
					}
				} else{ $totalevent=0; $totalpacket=0;}
				//echo "<br>total event= ".$totalevent;
				//echo "<br>total packet= ".$totalpacket;

				///served
				$sql= "SELECT user_id, count(user_id) AS totalevent, sum(amount) as totalpacket  FROM user_contribution NATURAL JOIN event JOIN food_packet on event.packet_id=food_packet.packet_id and status='served' group by user_id having user_id='$id';";
				//echo $sql;
				$res = $conn->query($sql);
				if($res->num_rows > 0){
					while($inf=$res->fetch_assoc()){
						$servedevent=$inf['totalevent'];
						$servedpacket=$inf['totalpacket'];
					}
				} else{ $servedevent=0; $servedpacket=0;}
				//echo "<br>served event= ".$servedevent;
				//echo "<br>served packet= ".$servedpacket;

				//given
				$sql= "SELECT user_id, count(user_id) AS totalevent, sum(amount) as totalpacket  FROM user_contribution NATURAL JOIN event JOIN food_packet on event.packet_id=food_packet.packet_id and status='given' group by user_id having user_id='$id';";
				//echo $sql;
				$res = $conn->query($sql);
				if($res->num_rows > 0){
					while($inf=$res->fetch_assoc()){
						$givenevent=$inf['totalevent'];
						$givenpacket=$inf['totalpacket'];
					}
				}else{ $givenevent=0; $givenpacket=0;}
				//echo "<br>given event= ".$givenevent;
				//echo "<br>given packet= ".$givenpacket;

				//available
				$sql= "SELECT user_id, count(user_id) AS totalevent, sum(amount) as totalpacket  FROM user_contribution NATURAL JOIN event JOIN food_packet on event.packet_id=food_packet.packet_id and (status='available' or status='requested') group by user_id having user_id='$id';";
				//echo $sql;
				$res = $conn->query($sql);
				if($res->num_rows > 0){
					while($inf=$res->fetch_assoc()){
						$availableevent=$inf['totalevent'];
						$availablepacket=$inf['totalpacket'];
					}
				}else{ $availableevent=0; $availablepacket=0;}
				//echo "<br>available event= ".$availableevent;
				//echo "<br>available packet= ".$availablepacket;

				//expired
				$sql= "SELECT user_id, count(user_id) AS totalevent, sum(amount) as totalpacket  FROM user_contribution NATURAL JOIN event JOIN food_packet on event.packet_id=food_packet.packet_id and status='expired' group by user_id having user_id='$id';";
				//echo $sql;
				$res = $conn->query($sql);
				if($res->num_rows > 0){
					while($inf=$res->fetch_assoc()){
						$expiredevent=$inf['totalevent'];
						$expiredpacket=$inf['totalpacket'];
					}
				}else{ $expiredevent=0; $expiredpacket=0;}
				//echo "<br>expired event= ".$expiredevent;
				//echo "<br>expired packet= ".$expiredpacket;
			}
			echo "<br>User contribution for ".$id;
			echo ('
				<table align="center">
					<tr>
						<td></td>
						<td>Served</td>
						<td>Given</td>
						<td>Available</td>
						<td>Expired</td>
						<td>Total</td>
					</tr>
					<tr>
						<td>Total Event</td>
						<td>'.$servedevent.'</td>
						<td>'.$givenevent.'</td>
						<td>'.$availableevent.'</td>
						<td>'.$expiredevent.'</td>
						<td>'.$totalevent.'</td>
					</tr>
					<tr>
						<td>Total Packet</td>
						<td>'.$servedpacket.'</td>
						<td>'.$givenpacket.'</td>
						<td>'.$availablepacket.'</td>
						<td>'.$expiredpacket.'</td>
						<td>'.$totalpacket.'</td>
					</tr>
				</table>
			');

		}
		else{
			echo '<font color="red">No User fonud with User Id"'.$id.'"</font>';
		}

	}
	else{
		$sql= "SELECT * FROM volunteer WHERE vol_id='$id';";
		$result = $conn->query($sql);

		if ($result->num_rows > 0){
			while($info=$result->fetch_assoc()){
				echo "<br>Volunteer<br>";
				echo ' ID: '.$info['vol_id'].'
					<br> Name: '.$info['vol_name'].'
					<br> Mobile No: '.$info['mobile_no'].'
					<br> Email Address: '.$info['email'].'
					<br> Location: '.$info['location'];
				echo '<br>';

				//total
				$sql= "SELECT vol_id, count(vol_id) AS totalevent, sum(amount) as totalpacket  FROM volunteer_contribution NATURAL JOIN event JOIN food_packet on event.packet_id=food_packet.packet_id and status<>'requested' group by vol_id having vol_id='$id';";
				//echo $sql;
				$res = $conn->query($sql);
				if($res->num_rows > 0){
					while($inf=$res->fetch_assoc()){
						$totalevent=$inf['totalevent'];
						$totalpacket=$inf['totalpacket'];
					}
				} else{ $totalevent=0; $totalpacket=0;}
				//echo "<br>total event= ".$totalevent;
				//echo "<br>total packet= ".$totalpacket;

				///served
				$sql= "SELECT vol_id, count(vol_id) AS totalevent, sum(amount) as totalpacket  FROM volunteer_contribution NATURAL JOIN event JOIN food_packet on event.packet_id=food_packet.packet_id and status='served' group by vol_id having vol_id='$id';";
				//echo $sql;
				$res = $conn->query($sql);
				if($res->num_rows > 0){
					while($inf=$res->fetch_assoc()){
						$servedevent=$inf['totalevent'];
						$servedpacket=$inf['totalpacket'];
					}
				} else{ $servedevent=0; $servedpacket=0;}
				//echo "<br>served event= ".$servedevent;
				//echo "<br>served packet= ".$servedpacket;

				//available
				$sql= "SELECT vol_id, count(vol_id) AS totalevent, sum(amount) as totalpacket  FROM volunteer_contribution NATURAL JOIN event JOIN food_packet on event.packet_id=food_packet.packet_id and status='given' group by vol_id having vol_id='$id';";
				//echo $sql;
				$res = $conn->query($sql);
				if($res->num_rows > 0){
					while($inf=$res->fetch_assoc()){
						$availableevent=$inf['totalevent'];
						$availablepacket=$inf['totalpacket'];
					}
				}else{ $availableevent=0; $availablepacket=0;}
				//echo "<br>available event= ".$availableevent;
				//echo "<br>available packet= ".$availablepacket;


				//expired
				$sql= "SELECT vol_id, count(vol_id) AS totalevent, sum(amount) as totalpacket  FROM volunteer_contribution NATURAL JOIN event JOIN food_packet on event.packet_id=food_packet.packet_id and status='expired' group by vol_id having vol_id='$id';";
				//echo $sql;
				$res = $conn->query($sql);
				if($res->num_rows > 0){
					while($inf=$res->fetch_assoc()){
						$expiredevent=$inf['totalevent'];
						$expiredpacket=$inf['totalpacket'];
					}
				}else{ $expiredevent=0; $expiredpacket=0;}
				//echo "<br>expired event= ".$expiredevent;
				//echo "<br>expired packet= ".$expiredpacket;
			}
			echo "<br>volunteer contribution of <b><i>".$id.'</i></b>';
			echo ('
				<table align="center">
					<tr>
						<td></td>
						<td>Served</td>
						<td>Available</td>
						<td>Expired</td>
						<td>Total</td>
					</tr>
					<tr>
						<td>Total Event</td>
						<td>'.$servedevent.'</td>
						<td>'.$availableevent.'</td>
						<td>'.$expiredevent.'</td>
						<td>'.$totalevent.'</td>
					</tr>
					<tr>
						<td>Total Packet</td>
						<td>'.$servedpacket.'</td>
						<td>'.$availablepacket.'</td>
						<td>'.$expiredpacket.'</td>
						<td>'.$totalpacket.'</td>
					</tr>
				</table>
			');

		}
		else{
			echo '<font color="red">No User fonud with User Id"'.$id.'"</font>';
		}
	}
	$conn->close();
	echo $web->getFooter();
	//and both time is greater than now
?>
