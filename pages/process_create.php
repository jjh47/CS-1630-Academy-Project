<?
	require("../glue.php");
	init("form_process");

	if($_SESSION["usertype"] != "teacher" && $_SESSION["usertype"] != "admin")
	{
		return_to(HOME_DIR);
	}

	$class_id = sqlite_escape_string(trim($_POST['class_id']));
	$date_assigned = sqlite_escape_string(trim($_POST['date_assigned']));
	$title = sqlite_escape_string(trim($_POST['assig_title']));
	$description = sqlite_escape_string(trim($_POST['description']));
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
	$is_open = sqlite_escape_string(trim($_POST['is_open']));
	$num_files_required = sqlite_escape_string(trim($_POST['num_files_required']));

	
	$due_date = $due_year.'-'.$due_month.'-'.$due_day.' '.$due_hour.':'.$due_minute.':00';
	$late_due_date = $late_year.'-'.$late_month.'-'.$late_day.' '.$late_hour.':'.$late_minute.':00';
	$query = "insert into Assignment values (NULL, '$class_id', '$title', '$date_assigned', '$description',
		'$due_date', '$late_due_date', $is_open, $num_files_required)";
	
	$result = $db->queryExec($query, $error);
	if (empty($result))
	{
		$_SESSION["creation-message-error"] = "Error inserting assignment into database: $error";
	}
	else
	{
		$_SESSION["creation-message"] = "Assignment successfully created.";
	}
	
	return_to("view_class.php?class_id=$class_id"); //don't forget to specify a page
?>