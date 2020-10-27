<?php
//session_id('mySessionID');
session_start();
?>

<?php
class Webpage
{
	var $head;
	var $body;
	var $footer;
	
	function __construct($logged_in, $page)
	{
		$this->head="<!DOCTYPE HTML>
<html>

<head>
  <title>Share Your Food</title>
  <style>
	html
{ height: 100%;}

*
{ margin: 0;
  padding: 0;}

body
{ font: normal .80em 'trebuchet ms', arial, sans-serif;
  background: #FFF;
  color: #555;}

p
{ padding: 0 0 20px 0;
  line-height: 1.7em;}

img
{ border: 0;}

h1, h2, h3, h4, h5, h6 
{ color: #362C20;
  letter-spacing: 0em;
  padding: 0 0 5px 0;}

h1, h2, h3
{ font: normal 170% 'century gothic', arial;
  margin: 0 0 15px 0;
  padding: 15px 0 5px 0;
  color: #000;}

h2
{ font-size: 160%;
  padding: 9px 0 5px 0;
  color: #B48A7C;}

h3
{ font-size: 140%;
  padding: 5px 0 0 0;}

h4, h6
{ color: #B48A7C;
  padding: 0 0 5px 0;
  font: normal 110% arial;
  text-transform: uppercase;}

h5, h6
{ color: #888;
  font: normal 95% arial;
  letter-spacing: normal;
  padding: 0 0 15px 0;}

a, a:hover
{ outline: none;
  text-decoration: underline;
  color: #B48A7C;}

a:hover
{ text-decoration: none;}

blockquote
{ margin: 20px 0; 
  padding: 10px 20px 0 20px;
  border: 1px solid #E5E5DB;
  background: #FFF;}

ul
{ margin: 2px 0 22px 17px;}

ul li
{ list-style-type: circle;
  margin: 0 0 6px 0; 
  padding: 0 0 4px 5px;
  line-height: 1.5em;}

ol
{ margin: 8px 0 22px 20px;}

ol li
{ margin: 0 0 11px 0;}

.left
{ float: left;
  width: auto;
  margin-right: 10px;}

.right
{ float: right; 
  width: auto;
  margin-left: 10px;}

.center
{ display: block;
  text-align: center;
  margin: 20px auto;}

#main, #logo, #menubar, #site_content, #footer
{ margin-left: auto; 
  margin-right: auto;}

#header
{ background: #000;
  border-bottom: 1px solid #3d3d3d;
  height: 186px;}
  

#logo
{ width: 880px;
  position: relative;
  height: 140px;
  background: #1d1d1d;}

#logo #logo_text 
{ position: absolute; 
  top: 10px;
  left: 0;}

#logo h1, #logo h2
{ font: normal 300% 'century gothic', arial, sans-serif;
  border-bottom: 0;
  text-transform: none;
  margin: 0 0 0 9px;}

#logo_text h1, #logo_text h1 a, #logo_text h1 a:hover 
{ padding: 22px 0 0 0;
  color: #FFF;
  letter-spacing: 0.1em;
  text-decoration: none;}

#logo_text h1 a .logo_colour
{ color: #5E4238;}

#logo_text a:hover .logo_colour
{ color: #5E4238;}

#logo_text h2
{ font-size: 120%;
  padding: 4px 0 0 0;
  color: #B48A7C;}

#menubar
{ width: 872px;
  height: 45px;
  padding-right: 8px;
  background: #2D2D2D;
  border-top: 1px solid #3D3D3D;} 

ul#menu
{ float: right;
  margin: 0;}

ul#menu li
{ float: left;
  padding: 0 0 0 9px;
  list-style: none;
  margin: 8px 4px 0 4px;}

ul#menu li a
{ font: normal 100% 'trebuchet ms', sans-serif;
  display: block; 
  float: left; 
  height: 20px;
  padding: 6px 20px 5px 20px;
  text-align: center;
  color: #FFF;
  text-decoration: none;
  background: #5E4238;} 

ul#menu li.selected a
{ height: 20px;
  padding: 6px 20px 5px 11px;}

ul#menu li.selected
{ margin: 8px 4px 0 13px;
  background: #B48A7C;}

ul#menu li.selected a, ul#menu li.selected a:hover
{ background: #B48A7C;
  color: #FFF;}

ul#menu li a:hover
{ color: #B48A7C;}

#site_content
{ width: 880px;
  overflow: hidden;
  margin: 20px auto 0 auto;
  padding: 0 0 10px 0;} 

#sidebar_container
{ float: left;
  width: 224px;}

.sidebar_top
{ width: 222px;
  height: 14px;
  background: transparent url(https://i.imgur.com/RzXekW9.png) no-repeat;}

.sidebar_base
{ width: 222px;
  height: 14px;
  background: url(https://i.imgur.com/YL4oW2Z.png) no-repeat;}

.sidebar
{ float: right;
  width: 222px;
  padding: 0;
  margin: 0 0 16px 0;}

.sidebar_item
{ background: url(https://i.imgur.com/MIfuRLQ.png) repeat-y;
  padding: 0 15px;
  width: 192px;}

.sidebar li a.selected
{ color: #444;} 

.sidebar ul
{ margin: 0;} 

#content
{ text-align: left;
  width: 620px;
  padding: 0 0 0 5px;
  float: right;}
  
#content ul
{ margin: 2px 0 22px 0px;}

#content ul li, .sidebar ul li
{ list-style-type: none;
  background: url(https://i.imgur.com/CBrRZGt.png) no-repeat;
  margin: 0 0 0 0; 
  padding: 0 0 4px 25px;
  line-height: 1.5em;}

#footer
{ width: 100%;
  font-family: 'trebuchet ms', sans-serif;
  font-size: 100%;
  height: 80px;
  padding: 28px 0 5px 0;
  text-align: center; 
  background: #000;
  border-top: 2px solid #2D2D2D;
  color: #FFF;}

#footer p
{ line-height: 1.7em;
  padding: 0 0 10px 0;}

#footer a
{ color: #FFF;
  text-decoration: none;}

#footer a:hover
{ color: #B48A7C;
  text-decoration: none;}

.search
{ color: #5D5D5D; 
  border: 1px solid #BBB; 
  width: 134px; 
  padding: 4px; 
  font: 100% arial, sans-serif;}

.form_settings
{ margin: 15px 0 0 0;}

.form_settings p
{ padding: 0 0 4px 0;}

.form_settings span
{ float: left; 
  width: 200px; 
  text-align: left;}
  
.form_settings input, .form_settings textarea
{ padding: 5px; 
  width: 299px; 
  font: 100% arial; 
  border: 1px solid #E5E5DB; 
  background: #FFF; 
  color: #47433F;}
  
.form_settings .submit
{ font: 100% arial; 
  border: 0; 
  width: 99px; 
  margin: 0 0 0 212px; 
  height: 33px;
  padding: 2px 0 3px 0;
  cursor: pointer; 
  background: #3B3B3B; 
  color: #FFF;}

.form_settings textarea, .form_settings select
{ font: 100% arial; 
  width: 299px;}

.form_settings select
{ width: 310px;}

.form_settings .checkbox
{ margin: 4px 0; 
  padding: 0; 
  width: 14px;
  border: 0;
  background: none;}

.separator
{ width: 100%;
  height: 0;
  border-top: 1px solid #D9D5CF;
  border-bottom: 1px solid #FFF;
  margin: 0 0 20px 0;}
  
table
{ margin: 10px 0 30px 0;}

table tr th, table tr td
{ background: #3B3B3B;
  color: #FFF;
  padding: 7px 4px;
  text-align: right;}
  
table tr td
{ background: #E5E5DB;
  color: #47433F;
  border-top: 1px solid #FFF;}

.topnav {
  overflow: hidden;
  background-color: #B48A7C;;
}

.topnav a {
  float: left;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 107px;
  text-decoration: none;
  font-size: 14px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
  background-color: #4CAF50;
  color: white;
}

  </style>
</head>

".'<body>
  <div id="main">
    
	<div id="header">
      <div id="logo">
        <div id="logo_text">
          <h1><a href="index.html">Share<span class="logo_colour">Your Food</span></a></h1>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">';

/*____UPDATE HERE____*/
		if($page=="home")
			$this->head=$this->head.'<li class="selected"><a href="index.php">Home</a></li>';
		else
			$this->head=$this->head.'<li><a href="index.php">Home</a></li>'; 
		if($page=='about')
			$this->head=$this->head.'<li class="selected"><a href="about.php">About</a></li>';
		else 
			$this->head=$this->head.'<li><a href="about.php">About</a></li>';          
		//$this->head=$this->head.'<li><a href="index.php">Contribution</a></li>';
        if($page=='event')
			$this->head=$this->head.'<li class="selected"><a href="addevent.php">Events</a></li>';
		else 
			$this->head=$this->head.'<li><a href="addevent.php">Events</a></li>';
		if($page=='profile'){
			$this->head=$this->head.'<li class="selected"><a href="profile.php">Profile</a></li>';
		}
		else{
			$this->head=$this->head.'<li><a href="profile.php">Profile</a></li>';
		}
		if($page=="login")
			$this->head=$this->head.'<li class="selected"><a href="login.php">Login</a></li>';
		else if($_SESSION["userid"]!="")
			$this->head=$this->head.'<li><a href="logout.php">Logout'.'</a></li>';
		else
			$this->head=$this->head.'<li><a href="login.php">Login</a></li>';
		$this->head=$this->head.'
  </ul>
      </div>
    </div>
    
	<div id="content_header"></div>
    
	<div id="site_content">
	  <div id="sidebar_container">
        
		<div class="sidebar">
		  <div class="sidebar_top"></div>
          <div class="sidebar_item">
            <!-- insert your sidebar items here -->
			<p> <pre> 

















			</pre></p>
          </div>
		  <div class="sidebar_base"></div>
        </div>

        <div class="sidebar">
          <div class="sidebar_top"></div>
          <div class="sidebar_item">
          </div>
          <div class="sidebar_base"></div>
        </div>
      </div>
      <div id="content">';
		$this->body="";
		$this->footer='</div>
    </div>

    <div id="content_footer"></div>
    <div id="footer">';
	/*___upate here as well___*/

		$this->footer=$this->footer.'<p><a href="index.php">Home</a> | <a href="about.php">About</a> | <a href="addevent.php">Events</a> | <a href="profile.php">Profile</a> | <a href="login.php">Log in</a></p>';
		
		$this->footer=$this->footer.'    </div>
  </div>
</body>
</html>';
	}

	function getHead()
	{
		return $this->head;
	}

	function getBody()
	{
		//echo $_SESSON["userid"].$_SESSON["password"].$_SESSON["type"];
		echo ".<br>";
		return $this->body;
	}

	function getFooter()
	{
		return $this->footer;
	}

	function changeBody($new_body)
	{
		$this->body=$new_body;
	}
	function addBody($new_body)
	{
		$this->body=$this->body.$new_body;
	}
	function add($new_body)
	{
		$this->body=$this->body.$new_body;
	}
	function changeHead($new_head)
	{
		$this->head=$new_head;
	}
	function changeFooter($new_footer)
	{
		$this->footer=$new_footer;
	}

	function getFullContent()
	{
		return $this->head.$this->body.$this->footer;
	}
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

}


/* 
<p>bullet<img src="https://i.imgur.com/CBrRZGt.png" height="50" width="50"></p>
<p>Side top <img src="https://i.imgur.com/RzXekW9.png "height="20" width="200"></p> 
<p>Side base<img src="https://i.imgur.com/YL4oW2Z.png" height="20" width="200"></p>
<p>Side back<img src="https://i.imgur.com/MIfuRLQ.png"></p>	
*/
?>

<?php
/*
class Database
{
	var $servername = "localhost";
	var $username = "root";
	var $password = "password";
	var $dbname = "testdb";
	var $conn;//=new mysqli($servername, $username, $password, $dbname);
	
	function __construct(){
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
	}

	function query($sql){
		if ($conn=="" || $conn->connect_error)
			return null;
		else 
			return $conn->query($sql);
	}

	function close(){
		$conn->close();	
	}
}
*/
?>

