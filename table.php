<?php
$servername = "localhost";
$username = "root";
$pass = "password";
$dbname = "testdb";
// Create connection
$link = new mysqli($servername, $username, $pass, $dbname);
// Check connection
if ($link->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Check connection

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

/*
$sql="DROP TABLE user;";
if(mysqli_query($link, $sql)){
	echo "table user delelted";
}
else{
	echo "error in deleting user".mysqli_error($link);
}*/

/*
//table user, user_id VARCHAR(30) not null primary key, password text, user_name text, mobile_no VARCHAR(30) not null unique, email VARCHAR(30) not null unique
$sql = "CREATE TABLE user(
    user_id VARCHAR(30) NOT NULL PRIMARY KEY,
    password TEXT,
	user_name TEXT,
	mobile_no VARCHAR(30) NOT NULL UNIQUE,
	email VARCHAR(30) NOT NULL UNIQUE);";

if(mysqli_query($link, $sql)){
    echo "Table user created successfully.";
	//optional	
	$sql= "INSERT INTO user(user_id, password,user_name, mobile_no, email) VALUES ('user', 'password', 'user_name','mobile_no','email');";
	if(mysqli_query($link, $sql)){
		echo "data inserted!";
	}
	else
    echo "ERROR: insert data in user $sql. " . mysqli_error($link);
} else{
    echo "ERROR: tabe user $sql. " . mysqli_error($link);
}

*/
 
/*
$name="volunteer";
$sql="DROP TABLE $name;";
if(mysqli_query($link, $sql)){
	echo "table ".$name." delelted";
}
else{
	echo "error in deleting ".$name.mysqli_error($link);
}


//table  volunteer, vol_id VARCHAR(30) primary key, password text, vol_name, mobile_no varchar(30) not null unique, email varchar(30) not null unique, location text
$name="volunteer";
$sql = "CREATE TABLE $name(
    vol_id VARCHAR(30) NOT NULL PRIMARY KEY,
    password TEXT,
	vol_name TEXT,
	mobile_no VARCHAR(30) NOT NULL UNIQUE,
	email VARCHAR(30) NOT NULL UNIQUE,
	location TEXT);";

if(mysqli_query($link, $sql)){
    echo "Table user created successfully.";
	//optional	
	$sql= "INSERT INTO $name(vol_id, password,vol_name, mobile_no, email, location) VALUES ('user', 'password', 'vol_name','mobile_no','email','location');";
	if(mysqli_query($link, $sql)){
		echo "data inserted!";
	}
	else
    echo "ERROR: insert data in $name, $sql. " . mysqli_error($link);
} else{
    echo "ERROR: tabe $name, $sql. " . mysqli_error($link);
}
*/

/*
$name="food_packet";
$sql="DROP TABLE $name;";
if(mysqli_query($link, $sql)){
	echo "table ".$name." delelted";
}
else{
	echo "error in deleting ".$name.mysqli_error($link);
}


//table food_packet, packet_id integer primary key AUTO_INCREMENT, type text in 'snack','lunch_dinner', details text (items), amount (number of packet) integer not null, expire_date_time TIMESTAMP (YYYYMMDDHHMMSS)

$name="food_packet";
$sql = "CREATE TABLE $name(
    packet_id INTEGER PRIMARY KEY AUTO_INCREMENT,
	type TEXT,
	details TEXT,
	amount INTEGER NOT NULL,
	expire_date_time TIMESTAMP);";

if(mysqli_query($link, $sql)){
    echo "Table $name created successfully.";
	//optional	
	$sql= "INSERT INTO $name(packet_id, type, details, amount, expire_date_time) VALUES (NULL,'snacks', '1 apple, 1 orage, 1 juice, 1 sandwitch','10','20180404235959');";
	if(mysqli_query($link, $sql)){
		echo "data inserted! into $name";
	}
	else
    echo "ERROR: insert data in $name, $sql. " . mysqli_error($link);
} else{
    echo "ERROR: tabe $name, $sql. " . mysqli_error($link);
}
*/


/*
$name="event";
$sql="DROP TABLE $name;";
if(mysqli_query($link, $sql)){
	echo "table ".$name." delelted";
}
else{
	echo "error in deleting ".$name.mysqli_error($link);
}


//table event, event_id integer primary key not null, user_id text foreign key user.user_id, location text, available_date_time TIMESTAMP, packet_id integer foreign key food_packet.packet_id, status text - 'available', 'expired', 'given', 'served',


$name="event";
$sql = "CREATE TABLE $name(
    event_id INTEGER PRIMARY KEY AUTO_INCREMENT,
	location TEXT,
	available_date_time TIMESTAMP,
	packet_id INTEGER,
	status TEXT,

	CONSTRAINT fk_packet_id FOREIGN KEY(packet_id)
		REFERENCES food_packet(packet_id)
	);";

if(mysqli_query($link, $sql)){
    echo "Table $name created successfully.";
	//optional	
	$sql= "INSERT INTO $name(event_id, location, available_date_time, packet_id, status) VALUES (NULL, 'Science Complex Building, University of Dhaka','20180404235959','1','available');";
	if(mysqli_query($link, $sql)){
		echo "data inserted! into $name";
	}
	else
    echo "ERROR: insert data in $name, $sql. " . mysqli_error($link);
} else{
    echo "ERROR: tabe $name, $sql. " . mysqli_error($link);
}

*/

/*
$name="volunteer_contribution";
$sql="DROP TABLE $name;";
if(mysqli_query($link, $sql)){
	echo "table ".$name." delelted";
}
else{
	echo "error in deleting ".$name.mysqli_error($link);
}

*/

/*
//table volunteer_contribution vol_id text foreign key, volunteer.vol_id, event_id integer foreign key event.event_id,

$name="volunteer_contribution";
$sql = "CREATE TABLE $name(
	vol_id VARCHAR(30),
    event_id INTEGER,
	PRIMARY KEY(vol_id,event_id),
	CONSTRAINT fk_vol_id FOREIGN KEY(vol_id)
		REFERENCES volunteer(vol_id),
	CONSTRAINT fk_event_id FOREIGN KEY(event_id)
		REFERENCES event(event_id)
	);";

if(mysqli_query($link, $sql)){
    echo "Table $name created successfully.";
	//optional	
	$sql= "INSERT INTO $name(vol_id, event_id) VALUES ('user','1');";
	if(mysqli_query($link, $sql)){
		echo "data inserted! into $name";
	}
	else
    echo "ERROR: insert data in $name, $sql. " . mysqli_error($link);
} else{
    echo "ERROR: tabe $name, $sql. " . mysqli_error($link);
}
*/


//table user_contribution user_id text foreign key, user.user_id, event_id integer foreign key event.event_id,

$name="user_contribution";
$sql = "CREATE TABLE $name(
	user_id VARCHAR(30),
    event_id INTEGER,
	PRIMARY KEY(user_id,event_id),
	CONSTRAINT fk_UCuser_id FOREIGN KEY(user_id)
		REFERENCES user(user_id),
	CONSTRAINT fk_UCevent_id FOREIGN KEY(event_id)
		REFERENCES event(event_id)
	);";

if(mysqli_query($link, $sql)){
    echo "Table $name created successfully.";
	//optional	
	$sql= "INSERT INTO $name(user_id, event_id) VALUES ('user','1');";
	if(mysqli_query($link, $sql)){
		echo "data inserted! into $name";
	}
	else
    echo "ERROR: insert data in $name, $sql. " . mysqli_error($link);
} else{
    echo "ERROR: tabe $name, $sql. " . mysqli_error($link);
}


mysqli_close ($link);

?>
