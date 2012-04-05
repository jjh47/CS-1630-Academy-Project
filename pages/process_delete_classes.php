<?
	require("../glue.php");
	init("form_process");

	//This page processes the deletes requested by view_classes.php
	
	if($_SESSION["usertype"] != "admin")
	{
		return_to(HOME_DIR);
	}
	
	$requested_action = $_POST['delete_classesSubmit']; //check if this exits?
	$checked =  $_POST['check'];
	if(empty($checked))
	{
		//This should not happen due to client-side checking, ie, we don't load this page if they didn't check any of the boxes.
		$_SESSION["delete-classses-message-error"] = "No classes selected!";
		return_to(HOME_DIR."pages/view_classes.php");	
	}
	else
	{
		$count = count($checked);
		$count_deleted = 0;
		if($requested_action == "Delete Classes")
		{
			for($i=0; $i < $count; $i++)
			{
				$class_id = $checked[$i];
				$query = "delete from Class where class_id = $class_id";
				$result = $db->queryExec($query, $error);
				if (empty($result))
				{
					$_SESSION["delete-classes-message-error"] = "Error deleting class_id $class_id: $error";
					return_to(HOME_DIR."pages/view_classes.php");
				}
				else
				{
					//Let's count the classes deleted so the admin can visually compare that to how many he meant to delete.
					$count_deleted++;
				}
			}
			if($count_deleted == 1)
			{
				$_SESSION["delete-classes-message"] = "1 class successfully deleted."; //so that class isn't incorrectly plural
			}
			else
			{
				$_SESSION["delete-classes-message"] = "$count_deleted classes successfully deleted.";
			}
			return_to(HOME_DIR."pages/view_classes.php");
		}
	}
?>