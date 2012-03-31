<?
	require("../glue.php");
	init("form_process");

	if($_SESSION["usertype"] != "teacher" && $_SESSION["usertype"] != "admin")
	{
		return_to(HOME_DIR);
	}

	$assignment_id = sqlite_escape_string($_POST["assignment_id"]);
	$class_id = sqlite_escape_string($_POST["class_id"]);
	$title = sqlite_escape_string($_POST["assig_title"]);
	$date_assigned = sqlite_escape_string($_POST["date_assigned"]);
	$description = sqlite_escape_string($_POST["description"]);
	$due_date = sqlite_escape_string($_POST["due-date"]);
	$late_due_date = sqlite_escape_string($_POST["late-due-date"]);
	$is_open = sqlite_escape_string(trim($_POST["is_open"]));
	$num_files = sqlite_escape_string($_POST["num_files"]);
	$due_date = date("Y-m-d H:i:s",strtotime($due_date));
	$late_due_date = date("Y-m-d H:i:s",strtotime($late_due_date));

	$results = $db->queryExec("update assignment set 
								title='$title', 
								date_assigned ='$date_assigned', 
								description = '$description', 
								due_date = '$due_date',
								late_due_date = '$late_due_date', 
								is_open = '$is_open',
								num_files_required = '$num_files'
								where assignment_id = '$assignment_id';");

	$success = $db->changes();

	if ($success)
	{
		$_SESSION["edit_assignment_success"] = "Assignment successfully edited.";
	}
	else
	{
		$_SESSION["edit_assignment_error"] = "Assignment was not edited.";
	}

	return_to(HOME_DIR."/pages/view_assig.php?class_id=$class_id&assignment_id=$assignment_id");


	?>

