<?php
// Julien Chien & Andrew Sheets
// logout.php
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
	session_unset();  // remove all session variables
	session_destroy();
	echo "You have logged out! <br> <br>";
	echo "<a href='userlogin.php'>Click here to login again!</a>";
}
?>



<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">	
</head>
<body>
	<h2>Log Out! </h2>
	<form method = "post" >
		
		<input type="submit" name="submit" value="Click to Log Out"> 
	</form>
</body>
</html>
