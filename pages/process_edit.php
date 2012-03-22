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
	$due_year = sqlite_escape_string(trim($_POST['due_year']));
	$due_month = sqlite_escape_string(trim($_POST['due_month']));
	$due_day = sqlite_escape_string(trim($_POST['due_day']));
	$due_hour = sqlite_escape_string(trim($_POST['due_hour']));
	$due_minute = sqlite_escape_string(trim($_POST['due_minute']));
	$late_year = sqlite_escape_string(trim($_POST['late_year']));
	$late_month = sqlite_escape_string(trim($_POST['late_month']));
	$late_day = sqlite_escape_string(trim($_POST['late_day']));
	$late_hour = sqlite_escape_string(trim($_POST['late_hour']));
	$late_minute = sqlite_escape_string(trim($_POST['late_minute']));
	$is_open = sqlite_escape_string(trim($_POST["is_open"]));
	$num_files = sqlite_escape_string($_POST["num_files"]);
	
	$due_date = $due_year.'-'.$due_month.'-'.$due_day.' '.$due_hour.':'.$due_minute.':00';
	$late_due_date = $late_year.'-'.$late_month.'-'.$late_day.' '.$late_hour.':'.$late_minute.':00';

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

