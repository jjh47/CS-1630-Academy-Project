<?
	require("../glue.php");
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
	if(isset($_POST['adduser']))
	{
		add_user_form();
	}
	elseif(isset($_POST['uploadcsv']))
	{
		add_user_csv();
	}




	//return_to("add_students.php"); //don't forget to specify a page


	function add_user_form()
	{
		global $db;
		//get vars
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
    	




	}

	function add_user_csv()
	{

		$filename = basename($_FILES['uploadedfile']['name']);

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
	{
		global $db;
		$username = $linesplit[0];
		$email = $linesplit[1];
		$usertype = $linesplit[2];
		$salt = make_salt();
		$password = crypt($linesplit[3], "$5$" . $salt);

		$query = "INSERT INTO User VALUES (NULL,'$username','$email','$usertype','$password','$salt');";

		echo $query . "<br>";

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