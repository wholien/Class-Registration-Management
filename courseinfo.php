<?php
//courseinfo.php


?>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">	
</head>

<?php
session_start();

require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);

$_SESSION["course_selected"] = $_GET["course_number"];
//echo $_SESSION["course_selected"];
//echo $_SESSION["student_id"];

$student_id = $_SESSION['student_id'];
$courseid = $_SESSION["course_selected"];

if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$query = "INSERT INTO studentcourses (student_id, course_id) VALUES ('$student_id', '$courseid') ";
	$result_time = $conn->query($query);
		if (!$result_time) die($conn->error);

	$query = "UPDATE courses SET slotstaken = slotstaken + 1 WHERE id = '$courseid'";
	$result = $conn->query($query);
		if (!$result) die($conn->error);

	$query = "UPDATE students SET credit_registered = credit_registered + 1 WHERE id = '$student_id'";
	$result = $conn->query($query);
		if (!$result) die($conn->error);

}
echo "<div align='right'><a href='home.php'>Home</a> ||| <a href='logout.php'>Logout</a></div>";
echo "<h2>Course Details</h2>";

$query = "SELECT * FROM studentcourses WHERE student_id = '$student_id'";
$result = $conn->query($query);
  	if (!$result) die($conn->error);

$enrolled = False;
$rows = $result->num_rows;
for ($i = 0; $i < $rows; $i++){
	$current = $result->fetch_array(MYSQLI_ASSOC);
	if ($current['course_id'] == $_SESSION["course_selected"]){
		$enrolled = True;
		break;
	}
}
if ($enrolled == True){
	//echo "enrolled!";
}

$query = "SELECT * FROM courses WHERE id = '$courseid'";
$result = $conn->query($query);
  	if (!$result) die($conn->error);

$current = $result->fetch_array(MYSQLI_ASSOC);
if ($current["slots"] > $current["slotstaken"]){
	$status = true;
}
else {
	$status = false;
}

echo "<h3><b>" . $current["title"] . " (" . $current["course_number"] . ")</b></h3>";
echo "Instructor(s): " . "<br>";
echo "<table>";
echo "<tr> <td>Fall 2015</td><td>1.00 Credit(s), Letter Grade (Grading Not Allowed: Pass/Fail Option)</td></tr>";
echo "<tr><td>Dept: " . $current["category"] . "</td><td>Clock Hours: 0.00</td></tr>";
echo "<tr><td>Status: ";
if ($status == true){
	echo "Open ";
}
else {
	echo "Closed ";
}
$open_seats = $current["slots"] - $current["slotstaken"];
echo "(" . $open_seats . " out of " . $current["slots"] . " seats)</td></tr>";
echo "</table>";

$seats_full = False;
if ($open_seats<=0){
	$seats_full = True;
}

$time_conflict = False;
$time_id = $current['course_time'];
$query_classes = "SELECT course_id FROM studentcourses WHERE student_id = '$student_id'";
$result_classes = $conn->query($query_classes);
  	if (!$result_classes) die($conn->error);

$rows = $result_classes->num_rows;
for ($i = 0; $i < $rows; $i++){
	$result_classes->data_seek($i);
	$course_time = $result_classes->fetch_array(MYSQLI_ASSOC);
	$current_course = $course_time['course_id'];
	//echo "$current_course ";
	//echo gettype($current_course);
	$query_time = "SELECT course_time FROM courses WHERE id = '$current_course'";
	$result_time = $conn->query($query_time);
		if (!$result_time) die($conn->error);
	$current_course_time = $result_time->fetch_object()->course_time;
	/*echo $current_course_time;
	echo " ";
	echo $time_id;
	echo gettype($current_course_time);
	echo gettype($time_id);
	echo "<br>";*/
	if ($current_course_time == $time_id){
		//echo "lol";
		$time_conflict = True;
		break;
	}
}

$credit_limit = False;
$query_credit = "SELECT credit_limit, credit_registered FROM students WHERE id = '$student_id'";
$result_credit = $conn->query($query_credit);
	if(!$result_credit) die($conn->error);
$student_credit = $result_credit->fetch_array(MYSQLI_ASSOC);
if ($student_credit['credit_registered'] >= $student_credit['credit_limit']){
	$credit_limit = True;
}





echo "<hr>";
echo "<b>Eligible to Register? </b>";


if ($time_conflict || $seats_full || $enrolled || $credit_limit){
	echo "No. Reason: <br><br>";
}
else {
	echo "Yes.";
	echo "<br><br>";
	echo "<form method='post'><b>
			<input type='submit' name='add' value='Add Course'/></b>
		  </form>";
}

if ($time_conflict== True){
	echo "--- You are already registered for a course at this time. <br>";
}

if ($seats_full){
	echo "--- There are no seats available for this class. <br>";
}

if ($enrolled){
	echo "--- You are already enrolled in this class. <br>";
}

if ($credit_limit){
	echo "--- You have reached your credit limit for how many courses you can take. <br>";
}










?>