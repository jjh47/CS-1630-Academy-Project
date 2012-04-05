<?
	require("../glue.php");
	init("script");

	$assignment_id = $_POST["assignment_id"];
	$class_id = $_POST["class_id"];
	$user_id = $_POST["user_id"];
	$username = $_SESSION["username"];
	$submission_time = date("Y-m-d H:i:s",$_POST["submission_time"]/1000);
	$successful = $_POST["successful"];
	$comment = $_POST["comment"];

	$results = $db->queryExec("insert into Log values (NULL, $assignment_id, $class_id, $user_id, '$username', '$submission_time', $successful, '$comment');");