<?php include("class_lib.php"); ?>
<?php
	$web = new Webpage($id!="","about");
	echo $web->getHead();
?>
<p>
<h3>Credentials:</h3> <b>
<a href="https://facebook.com/a.n.s.shohan">Al Nasirullah Siddiky</a>
<br><a href="https://facbook.com/safayatmishu">Safayat Ullah</a></b>
<br>Department of Computer science and Engineering
<br>University of Dhaka
</p>

<p>
<h3> How to use</h3>
There are two types of persons here:
<ol>
<li>User</li>
<li>Volunteer</li>
</ol>

<h4> User </h4>
Who offers available food
<ul>
	<li> Can add event about new available food by submitting a form</li>
	<li> Can add co-contributor users </li>
	<li> Can Edit or remove previously added event</li>
	<li> If a volunteer request for taking any food to serve, user can accept or reject that request </li>
</ul>

<h4> Volunteer </h4>
Who serves the food to the needy people.
<ul>
	<li> Can request for any available food event </li>
	<li> If the contributing user approves the request he/she can serve the food</li>
	<li> After servring he/she can mark that event as served</li>
</ul>

<strong> Both user and volunteer have their profile contaning basic contract information and their contribution history. </strong>
</p>
<?php
	echo $web->getFooter();
?>
