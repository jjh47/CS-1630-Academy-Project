<?
	require("../glue.php");
	init("form_process");

	if($_SESSION["usertype"] != "admin")
	{
		return_to(HOME_DIR);
	}
	
	$class_name = sqlite_escape_string(trim($_POST['class_name']));
	$instructor_email = sqlite_escape_string(trim($_POST['instructor_email']));
	$room = sqlite_escape_string(trim($_POST['room']));
	$description = sqlite_escape_string(trim($_POST['description']));
	
	$query = "select user_id from User where email = '$instructor_email'";
	$results = $db->arrayQuery($query);
	if(empty($results))
	{
		$_SESSION["creation-message-error"] = "Error inserting class into database: instructor email not found";
		return_to(HOME_DIR."pages/create_class.php");			
	}
	else
	{
		$instructor_id = $results[0]['user_id'];
		$query = "insert into Class values(NULL, '$class_name', '$instructor_id', '$instructor_email', '$room', '$description')";
		$result = $db->queryExec($query, $error);
		if (empty($result))
		{
			$_SESSION["creation-message-error"] = "Error inserting class into database: $error";
		}
		else
		{
			$class_id = $db->lastInsertRowid();
			$results = $db->queryExec("insert into Enrollment values ('$class_id','$instructor_id')", $error);
			if (empty($results))
			{
				$_SESSION["creation-message-error"] = "Error enrolling instructor in course: $error";
			}
			else
			{
				$_SESSION["creation-message"] = "Class successfully created.";
			}
		}
		return_to(HOME_DIR."pages/create_class.php");		
	}
?>