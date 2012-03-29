<?
	require("../glue.php");
	init("form_process");

	$course_id = sqlite_escape_string($_POST["course_id"]);
	$results = $db->arrayQuery("select assignment_id, title from assignment where class_id='$course_id'");
	if (empty($results))
	{
		echo "error";
	}
	else
	{
		$ret = json_encode($results);
		echo $ret;
	}