<?php
// Julien Chien & Andrew Sheets
// userlogin.php
session_start(); 

?>

<!DOCTYPE html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">	
</head>

<?php

//echo $_SESSION['username'];
//echo "<br>";

$usernameErr = $passwordErr = $generalErr = "";
$username = $password = "";
$in = False;

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   $data = strip_tags($data);
   $data = htmlentities($data);
   return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	if (empty($_POST["username"])) {
		$usernameErr = "Username is required";
	}
	else {
		$username = test_input($_POST["username"]);
		
	}

	if (empty($_POST["password"])) {
     	$passwordErr = "password is required";
   	} 
   	else {
     	$password = test_input($_POST["password"]);
     	
   }

   if ($username != "" && $password != "") {
   		
   		require_once 'login.php';
		$conn = new mysqli($hn, $un, $pw, $db);
  		if ($conn->connect_error) die($conn->connect_error);

  		//get username/password combos from sql
		$query = "SELECT * FROM students";
		//echo "ok<br>";

		$result = $conn->query($query);

		if (!$result) die($conn->error);

		$rows = $result->num_rows;
		if (isset($_SESSION['username'])) {
			$in = True;
		}
		while ($row = $result->fetch_assoc()) {
			if ($row["username"] == $username && $row["password"] == $password) {
				if (!isset($_SESSION['username'])) {
					$_SESSION['username'] = $username;
					$_SESSION['name'] = $row['name'];
					$_SESSION['student_id'] = $row['id'];
					//$_SESSION['role'] = $row['role'];
				}
				
			}
		}

		if (!isset($_SESSION['username'])) {
			$generalErr = "Invalid Username or Password; Try Again!";
		}
		else if ($in){
			echo "You are logged in already.";
			echo "<br><br>";
			echo "<br><br><a href='logout.php'> Click here to log out </a><br>";
			/*if ($_SESSION['role'] == 'admin') {
				die("You are logged in as an admin. Please <a href='manager.php'>Click Here</a> to continue. <br><br>");
				

			}
			if ($_SESSION['role'] == 'user') {
				die("You are logged in as a user. Please <a href='index4.php'>Click Here</a> to continue. <br><br>");
			}*/
		}

		else  {
			/*if ($_SESSION['role'] == 'admin') {
				die("You are logged in as an admin. Please <a href='manager.php'>Click Here</a> to continue. <br><br>");
				

			}
			if ($_SESSION['role'] == 'student') { */
				die("You are logged in as a student. Please <a href='home.php'>Click Here</a> to continue. <br><br>");
			}
		

    	


   	}
}


?>

<html lang="en">
<head>
	<title>Checkout</title>

	<style>
	.error {color: #FF0000;}
	</style>
</head>

<body>

	<h2> Portal: Student Login </h2>

	<br>
	<br>


	<p><span class="error">* required field.</span></p>
	<span class="error"> <?php echo $generalErr;?></span>
	<form method = "post" >
		Username: <input type="text" name="username" value="<?php echo $username;?>" required>
		<span class="error">* <?php echo $usernameErr;?></span>
		<br><br>
		Password: <input type="password" name="password" value="<?php echo $password;?>" required>
		<span class="error">* <?php echo $passwordErr;?></span>
		<br><br>
		<input type="submit" name="submit" value="Submit"> 
	</form>
</body>
</html>
