<?
	require("../glue.php");
	init("form_process");

/* PLEASE CAREFULLY READ THESE COMMENTS!
 * 
 * On form_process pages, you will need to work with $_POST variables.  PLEASE SPECIFY WHAT THESE ARE NAMED AND WHAT YOU REQUIRE so we can make 
 * sure they are delivered by the form.  Do not worry about the token, that is taken care of automatically
 * 
 * use $_SESSION["username"] and $_SESSION["usertype"] to segregate the components of the page (i.e. if ($username != "admin") etc.)
 * use $db to make database calls
 *
 * CAREFULLY use the defines.php file (includes/definies.php) to define any important information like file paths - 
 * specifically anything that may chance from one person's machine to another or on the production server.  This makes 
 * sure we can just change things here and they won't break elsewhere.  Please name your defines carefully.
 *
 * Make sure to capture any errors or failure so the user doesn't get stuck on a blank page.
 * 
 */
 
 	//Get variables from post and create variables needed
 	$classid = $_POST['course_id'];
 	$assigid = $_POST['assignment_id'];
 	$late = $_POST['late'];
 	$sumline = 0;
 	$summary;
 	
 	//Check to see if class folder exists
 	chdir(CLASS_PATH . $classid);
 	if(is_dir($assigid))
 	{
 		//Copy submitted files to location
 		copy_files(CLASS_PATH . $classid . "/" . $assigid);
 	
 	}
 	else
 	{
 		//create folder
 		mkdir(CLASS_PATH . $classid . "/" . $assigid);
 		//copy grading script
 		
 		
 		//copy submitted files into location
 		copy_files(CLASS_PATH . $classid . "/" . $assigid);
 	
 	}
 	
 	$_SESSION['upload_summary'] = $summary;
 	
 	for($i = 0; $i < $sumline;$i++)
 	{
 		echo $summary[$i] . "<br>";
 	}
 	
 	
 	function copy_files($dirpath)
 	{
 	 	
 		$date = date_create();
 		foreach ($_FILES["userfile"]["error"] as $key => $error) 
 		{
    		if ($error == UPLOAD_ERR_OK) 
    		{
        		$tmp_name = $_FILES["userfile"]["tmp_name"][$key];
        		$name = $_FILES["userfile"]["name"][$key];
        		move_uploaded_file($tmp_name, $dirpath . "/$name");
        		addsum("$name was uploaded successfuly.");
        	}
    		else
    		{
    			addsum("File not uploaded successfully");
    		}
		}
 		
 	}
 	
 	function addsum($toadd)
 	{
 		global $sumline, $summary;
 		$summary[$sumline] = $toadd;
 		$sumline++;
 	}

	return_to("submit_assig.php?class_id=0&assignment_id=0"); //don't forget to specify a page
?>