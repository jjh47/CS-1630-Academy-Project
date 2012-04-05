<?
	require("../glue.php");
<<<<<<< HEAD
	init("page");

/* PLEASE CAREFULLY READ THESE COMMENTS!
 * 
 * On form_process pages, you will need to work with $_POST variables.  PLEASE SPECIFY WHAT THESE ARE NAMED AND WHAT YOU REQUIRE so we can make sure they are delivered by the form.  Do not worry about the token, that is taken care of automatically
 * 
 * use $_SESSION["username"] and $_SESSION["usertype"] to segregate the components of the page (i.e. if ($username != "admin") etc.)
 * use $db to make database calls
 *
 * CAREFULLY use the defines.php file (includes/definies.php) to define any important information like file paths - specifically anything that may chance from one person's machine to another or on the production server.  This makes sure we can just change things here and they won't break elsewhere.  Please name your defines carefully.
 *
 * Make sure to capture any errors or failure so the user doesn't get stuck on a blank page.
 * 
 */
=======
	init("form_process");

>>>>>>> afbcc726e6b69e129803f2d4f723b6fee3b742e4
	if(isset($_POST['adduser']))
	{
		add_user_form();
	}
	elseif(isset($_POST['uploadcsv']))
	{
		add_user_csv();
	}




<<<<<<< HEAD
	//return_to("add_students.php"); //don't forget to specify a page
=======
	return_to(HOME_DIR."pages/add_user.php"); //don't forget to specify a page
>>>>>>> afbcc726e6b69e129803f2d4f723b6fee3b742e4


	function add_user_form()
	{
		global $db;
		//get vars
<<<<<<< HEAD
		$username = $_POST['username'];
		$email = $_POST['email'];
		$salt = make_salt();
		$password = $salt . crypt($_POST['password'], "$5$" . $salt);
		if($_POST['usertype'] == "student")
			$usertype="student";
		elseif($_POST['usertype'] == "teacher")
			$usertype = "teacher";
		else
			echo "Error finding user type";

		$query = "INSERT INTO User VALUES (NULL,'$username','$email','$usertype','$password','$salt');";

		echo $query;

		//add to db
		$result = $db->queryExec($query, $error);
    	if (!$result || $error)
   	 	{
        	return false;
        	die("Query error: $error");
    	}
    	else
    	{
        	return true;
    	}
    	




=======
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
>>>>>>> afbcc726e6b69e129803f2d4f723b6fee3b742e4
	}

	function add_user_csv()
	{

		$filename = basename($_FILES['uploadedfile']['name']);

<<<<<<< HEAD
		if(!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], CLASS_PATH . "CSVUploads/" . $filename))
		{
			echo "Error uploading CSV file: ";
		}
		
		$file = fopen(CLASS_PATH . "CSVUploads/" . $filename, "r") or exit("Unable to open file!");
		//Output a line of the file until the end is reached
		while(!feof($file))
  		{
  			$line = fgets($file);
  			$linesplit = explode(",", $line);
  			insert_student($linesplit);
  		}
		fclose($file);
		


	}

	function insert_student($linesplit)
=======
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
>>>>>>> afbcc726e6b69e129803f2d4f723b6fee3b742e4
	{
		global $db;
		$username = $linesplit[0];
		$email = $linesplit[1];
		$usertype = $linesplit[2];
<<<<<<< HEAD
=======
		if (!($usertype == "teacher" || $usertype == "student" || $usertype == "admin"))
		{
			$_SESSION["aur"]["success"] = false;
        	$_SESSION["aur"]["message"] = "Data format in .csv file is invalid.";
			return false;
		}
>>>>>>> afbcc726e6b69e129803f2d4f723b6fee3b742e4
		$salt = make_salt();
		$password = crypt($linesplit[3], "$5$" . $salt);

		$query = "INSERT INTO User VALUES (NULL,'$username','$email','$usertype','$password','$salt');";

<<<<<<< HEAD
		echo $query . "<br>";

		//add to db
		$result = $db->queryExec($query, $error);
    	if (!$result || $error)
   	 	{
        	return false;
        	die("Query error: $error");
=======
		//add to db
		@$result = $db->queryExec($query, $error);
    	if (empty($result) || $error)
   	 	{
        	return false;
>>>>>>> afbcc726e6b69e129803f2d4f723b6fee3b742e4
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