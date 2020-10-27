<?php
//session_id('mySessionID');
session_start();
session_unset();
session_destroy();
header('Location: index.php');  
?>
