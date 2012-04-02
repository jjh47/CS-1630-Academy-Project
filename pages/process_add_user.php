<?
	require("../glue.php");
	init("form_process");

	if(isset($_POST['adduser']))
	{
		add_user_form();
	}
	elseif(isset($_POST['uploadcsv']))
	{
		add_user_csv();
	}




	return_to(HOME_DIR."pages/add_user.php"); //don't forget to specify a page


	function add_user_form()
	{
		global $db;
		//get vars
		$username = sqlite_escape_string($_POST['username']);
		$email = sqlite_escape_string($_POST['email']);
		$salt = make_salt();
		$password = crypt(sqlite_escape_string($_POST['password']), "$5$" . $salt);
		
		if($_POST['usertype'] == "student")
		{
			$usertype = "student";
		}
		elseif($_POST['usertype'] == "teacher")
		{
			$usertype = "teacher";
		}
		elseif($_POST['usertype'] == "admin")
		{
			$usertype = "admin";
		}
		else
		{
			echo "Error finding user type";
			die;
		}
			

		$query = "INSERT INTO User VALUES (NULL,'$username','$email','$usertype','$password','$salt');";

		//echo $query;

		//add to db
		@$result = $db->queryExec($query, $error);
    	
    	if (empty($result) || $error)
   	 	{
        	$_SESSION["aur"]["success"] = false;
        	$_SESSION["aur"]["message"] = "Unable to add user $username.";
        	return false;
    	}
    	else
    	{
    		$_SESSION["aur"]["success"] = true;
        	$_SESSION["aur"]["message"] = "User $username successfully added.";
        	return true;
    	}
	}

	function add_user_csv()
	{

		$filename = basename($_FILES['uploadedfile']['name']);

		if (!is_dir(CLASS_PATH."CSVUploads"))
		{
			mkdir(CLASS_PATH."CSVUploads");
		}

		if(!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], CLASS_PATH . "CSVUploads/" . $filename))
		{
			$_SESSION["aur"]["success"] = false;
        	$_SESSION["aur"]["message"] = "Error uploading .csv file.";
        	return false;
		}
		
		$lines = file(CLASS_PATH . "CSVUploads/" . $filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		if (empty($lines))
		{
			$_SESSION["aur"]["success"] = false;
        	$_SESSION["aur"]["message"] = "No entries found in file.";
			return false;
		}

		if (count($lines[0]) != 4)
		{
			$_SESSION["aur"]["success"] = false;
        	$_SESSION["aur"]["message"] = "Data format in .csv file is invalid.";
			return false;
		}

		$success = true;

		foreach ($lines as $line)
		{
			$linesplit = explode(",",$line);	
			if (!insert_user($linesplit))
			{
				$_SESSION["aur"]["success"] = false;
				if (!isset($_SESSION["aur"]["message"])): $_SESSION["aur"]["message"] = ""; endif;
        		$_SESSION["aur"]["message"] .= "Error adding the following line: $line<br>";
				$success = false;
			}
		}

		if ($success)
		{
			$_SESSION["aur"]["success"] = true;
        	$_SESSION["aur"]["message"] = "All users successfully added.";
		}

		return $success;
	}

	function insert_user($linesplit)
	{
		global $db;
		$username = $linesplit[0];
		$email = $linesplit[1];
		$usertype = $linesplit[2];
		if (!($usertype == "teacher" || $usertype == "student" || $usertype == "admin"))
		{
			$_SESSION["aur"]["success"] = false;
        	$_SESSION["aur"]["message"] = "Data format in .csv file is invalid.";
			return false;
		}
		$salt = make_salt();
		$password = crypt($linesplit[3], "$5$" . $salt);

		$query = "INSERT INTO User VALUES (NULL,'$username','$email','$usertype','$password','$salt');";

		//add to db
		@$result = $db->queryExec($query, $error);
    	if (empty($result) || $error)
   	 	{
        	return false;
    	}
    	else
    	{
        	return true;
    	}
	}


	function make_salt()
	{
		$characters = "abcdefghijklmnopqrstuvwxyz0123456789";
		$length = strlen($characters);
		$salt = "";

		for ($x=0; $x<16; $x++)
		{

			$salt .= $characters[rand(0,$length-1)];
		}

		return $salt;
	}

?>