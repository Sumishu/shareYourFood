<?php include("class_lib.php"); ?>
<?php
	$web = new Webpage(false,"login");
	$web->changeBody('
<h2><pre> Login			<a href="signup.php">Register</a> </pre></h2>
<Form name ="form1" Method ="POST" Action ="submitForm.php">
userid: <INPUT TYPE = "TEXT" VALUE ="" Name ="userid">
<br> password: <input type = "password" value ="" name ="password">
<br><input type="radio" name ="type" value="user" checked> User
<br><input type="radio" name ="type" value="volunteer"> Volunteer
<br><INPUT TYPE = "Submit" Name = "Submit1" VALUE = "Login">
</FORM>
');
	echo $web->getFullContent();
?>
