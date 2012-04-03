<?
	require("../glue.php");
	init("form_process");

	$filename = isset($_POST["filename"]) ? $_POST["filename"] : "";
	$user_id = isset($_POST["user_id"]) ? sqlite_escape_string($_POST["user_id"]) : "-1";
	$assignment_id = isset($_POST["assignment_id"]) ? sqlite_escape_string($_POST["assignment_id"]) : "-1";
	$class_id = isset($_POST["class_id"]) ? sqlite_escape_string($_POST["class_id"]) : "-1";

	$class = $db->arrayQuery("select class_name from class where class_id='$class_id'");
	$assignment = $db->arrayQuery("select title from assignment where assignment_id='$assignment_id'");
	$user = $db->arrayQuery("select username from user where user_id='$user_id'");

	if (empty($class) || empty($assignment) || empty($user))
	{
		echo "error";
		die;
	}

	$path = BASE_PATH.preg_replace("/ /", "_", $class[0]["class_name"])."-".$class_id."/".preg_replace("/ /", "_", $assignment[0]["title"])."-".$assignment_id."/".preg_replace("/ /", "_", $user[0]["username"])."-".$user_id."/".$filename;
	$result = unlink($path);
	if ($result)
	{
		echo "success";
	}
	else
	{
		echo "error";
	}