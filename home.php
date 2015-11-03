<?php
//home.php
session_start();

?>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<script type="text/javascript">

$(document).ready(function(){



    $("hahahaha").click(function(){
     window.location="home.php";


    });

});
</script>

<div align='right'><a href='home.php'>Home</a> ||| <a href='logout.php'>Logout</a></div>

<?php
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);

$current_user = $_SESSION['username'];

$query = "SELECT id FROM students WHERE username = '$current_user'";
$result = $conn->query($query);
  if (!$result) die($conn->error);

$current_user_id = $result->fetch_object()->id;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST['drop_course_list'])) {
		echo "<p style='color:red'>You did not select any courses to drop! </p>";
	}

	if (!empty($_POST['drop_course_list'])) {
		foreach($_POST['drop_course_list'] as $drop){

			$query = "DELETE FROM studentcourses WHERE course_id = '$drop' AND student_id = '$current_user_id'";
			$result = $conn->query($query);
			  if (!$result) die($conn->error);

			$query = "SELECT title, course_number, slotstaken FROM courses WHERE id = '$drop'";
			$result = $conn->query($query);
			  if (!$result) die($conn->error);

			$row = $result->fetch_assoc();
			$new_slottaken = $row["slotstaken"];

			echo "<p style='color:red'>You've dropped " . $row["course_number"] . " " . $row["title"] . ". </p>";

			$query = "UPDATE courses SET slotstaken = slotstaken - 1 WHERE id = '$drop'";
			$result = $conn->query($query);
			  if (!$result) die($conn->error);

			$query = "UPDATE students SET credit_registered = credit_registered - 1 WHERE id = '$current_user_id'";
			$result = $conn->query($query);
			  if (!$result) die($conn->error);

		}
	}
}

if (isset($_SESSION['username'])) {


echo '<h2>User Data Page</h2>';
echo '<br>';
echo 'Welcome back ' . $_SESSION['name'];
echo '<br>';
echo '<h4>Add/Drop<h4>';
echo "<a href= 'adddrop.php'> Add Courses </a>";
echo '<h3>Student Schedule</h3>';
echo '<hr>';

echo '<form action="home.php" method="post">';
echo '<table border = 1 cellpadding = 15>';
echo '<thead><tr><td colspan="4">Your Schedule (Registered) Spring - 2015</td></tr></thead>';
echo '<tr><td>Drop</td><td>Course</td><td>Title</td><td>Meets</td></tr>';

$current_user = $_SESSION['username'];

$query = "SELECT id FROM students WHERE username = '$current_user'";
$result = $conn->query($query);
  if (!$result) die($conn->error);

$current_user_id = $result->fetch_object()->id;

$course_ids = array();
$query = "SELECT course_id FROM studentcourses WHERE student_id = '$current_user_id'";
$result = $conn->query($query);
  if (!$result) die($conn->error);

$rows = $result->num_rows;
for ($i = 0; $i < $rows ; $i++) {
	$result->data_seek($i);
	$current = $result->fetch_array(MYSQLI_ASSOC);
	$course_ids[$i] = $current['course_id'];
	//echo '<br> added new course id: ' . $current['course_id'] . '<br>';
}

$num_classes = count($course_ids);
for ($i = 0; $i < $num_classes; $i++) {
	$current_course = $course_ids[$i];
	$query = "SELECT * FROM courses WHERE id = '$current_course	'";
	$result = $conn->query($query);
  		if (!$result) die($conn->error);

  	$rows = $result->num_rows;
	for ($j = 0; $j < $rows ; $j++) {
		$result->data_seek($j);
		$current = $result->fetch_array(MYSQLI_ASSOC);

		echo '<tr>';
		echo "<td><input type='checkbox' name='drop_course_list[]' value=\"$current_course\"></td>";

		echo '<td>' . $current['course_number'] . '</td>';
		echo '<td>' . $current['title'] . '</td>';

		$time_id = $current['course_time'];
		$query_time = "SELECT time FROM course_times WHERE id = '$time_id'";
		$result_time = $conn->query($query_time);
  		if (!$result_time) die($conn->error);
  		$course_time = $result_time->fetch_object()->time;



		echo '<td>' . $course_time . '. ' . $current['location'] . '</td>';
		echo '</tr>';

		}
}
echo '</table>';
echo '<input type="submit" name="submit" id="submit" value="Drop Selected Courses"/>';
echo '</form>';


echo "<a href='logout.php'>Click here to log out </a>";


/*echo 'Current Student ID: ' . $current_user_id . "<br>";
echo 'Current course ids: ' . $course_ids[0] . ' and '. $course_ids[1] . ' and ' . $course_ids[2] . ' and ' . $course_ids[3];
echo '<br>there are ' . count($course_ids) . ' classes registered';
print_r(array_values($course_ids));*/


}

?>