<?
	require("../glue.php");
	init("form_process");

	//This page processes the various actions requested by modify_users.php, including enrolling, deleting, and changing passwords
	
	if($_SESSION["usertype"] != "admin")
	{
		return_to(HOME_DIR);
	}
	
	$requested_action = $_POST['modifySubmit']; //check if this exits?
	$checked =  $_POST['check'];
	if(empty($checked))
	{
		//This should not happen due to client-side checking.
		$_SESSION["modify-message-error"] = "No users selected!";
		return_to(HOME_DIR."pages/modify_users.php");	
	}
	else
	{
		$count = count($checked);
		//Now let's go case by case
		if($requested_action == "Enroll")
		{
			//We want to enroll the selected students in the selected class
			$class_name = sqlite_escape_string(trim($_POST['class_name']));
			$query = "select class_id from Class where class_name = '$class_name'";
			$results = $db->arrayQuery($query);
			if(empty($results))
			{
				$_SESSION["modify-message-error"] = "Error inserting enrollment into database: class name not found";
				return_to(HOME_DIR."pages/modify_users.php");			
			}
			else
			{
				$class_id = $results[0]['class_id'];
				for($i=0; $i < $count; $i++)
				{
					//enroll this particular student in the class
					$user_id = $checked[$i];
					$query = "insert into Enrollment values('$class_id', '$user_id')";
					$result = $db->queryExec($query, $error);
					if (empty($result))
					{
						$_SESSION["modify-message-error"] = "Error inserting enrollment into database: $error";
						return_to(HOME_DIR."pages/modify_users.php");
					}
					else
					{
						//Great success! Let's continue!
					}
				}
				$_SESSION["modify-message"] = "Users successfully enrolled.";
				return_to(HOME_DIR."pages/modify_users.php");		
			}
		}
		elseif($requested_action == "Delete Users")
		{
			for($i=0; $i < $count; $i++)
			{
				//This is the man we wanted! Delete him!
				$user_id = $checked[$i];
				$query = "delete from User where user_id = $user_id";
				$result = $db->queryExec($query, $error);
				if (empty($result))
				{
					$_SESSION["modify-message-error"] = "Error deleting user: $error";
					return_to(HOME_DIR."pages/modify_users.php");
				}
				else
				{
					//Great success! Let's continue!
				}
			}
			$_SESSION["modify-message"] = "Users successfully deleted.";
			return_to(HOME_DIR."pages/modify_users.php");
		}
		elseif($requested_action == "Change Passwords")
		{
			//php seems to insist the blank boxes have values, so we have to check ourselves
			$all_password =  $_POST['password'];
			$num_all = count($all_password);
			for($i = 0; $i < $num_all; $i++)  
			{
				//here we iterate through all the passwords and eliminate the ones that are just whitespace
				if(strlen(trim($all_password[$i])) < 1)
					unset($all_password[$i]);
			}
			if(empty($all_password))
			{
				//This should not happen due to client-side checking.
				$_SESSION["modify-message-error"] = "No new passwords given.";
				return_to(HOME_DIR."pages/modify_users.php");
			}
			else
			{
				$count_passwords = count($all_password);
				if($count != $count_passwords)
				{
					$_SESSION["modify-message-error"] = "New passwords do not match up with checked boxes. count $count count_passwords $count_passwords";
					return_to(HOME_DIR."pages/modify_users.php");
				}
				else
				{
					//One of the tasks we must do in changing their passwords is creating a random salt for each one and storing this in the table.
					for($i=0; $i < $count; $i++)
					{
						$pass = $all_password[$i];
						$user_id = $checked[$i];
						//I'm using the same technique here as was used in glue.php to create the tokens
						$characters = "abcdefghijklmnopqrstuvwxyz0123456789";
						$length = strlen($characters);
						$salt = '$5$';
						for ($x=0; $x<16; $x++)
						{
							$salt .= $characters[rand(0,$length-1)];
						}
						//Now we salt the given password
						$pass = crypt($pass, $salt); //IMPORTANT: $5$ indicates that SHA-256 is to be used.  Salt MUST be in single quotes.
						//Check with Rafe that the above salting was done correctly.
						$query = "update User set password = '$pass', salt = '$salt' where user_id = '$user_id'";
						$result = $db->queryExec($query, $error);
						if (empty($result))
						{
							$_SESSION["modify-message-error"] = "Error updating password: $error";
							return_to(HOME_DIR."pages/modify_users.php");
							//as is, it aborts entire batch if one fails. Is this how it should behave?
						}
						else
						{
							//Great success! Let's continue!
						}
					}
				}
			}
			$_SESSION["modify-message"] = "Passwords successfully changed.";
			return_to(HOME_DIR."pages/modify_users.php");
		}
	}
?>