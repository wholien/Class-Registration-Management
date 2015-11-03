<?php
//adddrop.php
session_start();

require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);


?>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">	
</head>

<script type="text/javascript">

$(document).ready(function(){



    $(".refresh_link").click(function(){
     window.location="home.php";


    });

});
</script>
<div align='right'><a href='home.php'>Home</a> ||| <a href='logout.php'>Logout</a></div>

<h2>Search for Class</h2>
<form method="post">
<table>
  <tr>
  	<td>
  		Course Area:
  	</td>
  	<td>
  	<select name="category" value="Course Department" action>
  		<option value="All">All</option>
	  	<option value="Computer Science">Computer Science</option>
	  	<option value="Government">Government</option>
	  	<option value="Economics">Economics</option>
	</select>
	</td>
	<td><input type="submit" value="Search"/></td>
</table>
</form>
<hr>

<?php 
if ($_POST["category"]) {
	echo '<table border = 1 cellpadding = 15>';
	echo '<thead><tr><td colspan="3">Courses - Spring 2015</td></tr></thead>';
	echo '<tr><td>Course Code</td><td>Name</td><td>Factuly</td><td>Seats Open</td><td>Schedule</td></tr>';

	$category = $_POST["category"];
	if ($category == "All"){
		$query = "SELECT * FROM courses";
		echo "this ran";
	}
	else{
		$query = "SELECT * FROM courses WHERE category = '$category'";
	}	

	$result = $conn->query($query);
	  if (!$result) die($conn->error);

	$rows = $result->num_rows;
	for ($i = 0; $i < $rows ; $i++) {
		$result->data_seek($i);
		$current = $result->fetch_array(MYSQLI_ASSOC);

		$cnumber = $current["id"];
		echo "<tr>";
		echo "<td><a href='courseinfo.php?course_number=$cnumber'>" . $current["course_number"] . "</a></td>";
		echo "<td>" . $current["title"] . "</td>";
		echo "<td></td>"; #faculty
		$seats_open = $current["slots"] - $current["slotstaken"];
		echo "<td>" . $seats_open . "/" . $current["slots"] . "</td>";

		$time_id = $current["course_time"];
		$query_time = "SELECT time FROM course_times WHERE id = '$time_id'";
		$result_time = $conn->query($query_time);
  			if (!$result_time) die($conn->error);

  		$course_time = $result_time->fetch_object()->time;

		echo "<td>" . $course_time . "; " . $current["location"] . "</td>";
		echo "</tr>";
	}

	echo '</table>';
}
?>







