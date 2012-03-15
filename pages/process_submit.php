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
 	
 	//Parse the names of the folders to access/create
 	//These functions could cause problems but are the only way 
 	//I see to deal with naming contraints we agreed too.
 	$assig_folder_name = parse_assig_name();
 	$class_folder_name = parse_class_name();
 	
 	//Check to see if class folder exists
 	if(chdir(CLASS_PATH . $class_folder_name))
 	{
		if(is_dir($assig_folder_name))
		{
			//Copy submitted files to location
			copy_files(CLASS_PATH . $class_folder_name . "/" . $assig_folder_name);
		
		}
		else
		{
			//create folder
			if(mkdir(CLASS_PATH . $class_folder_name . "/" . $assig_folder_name))
			{
				//copy grading script
				if(!copy(GSCRIPT_PATH, CLASS_PATH . $class_folder_name . "/" . $assig_folder_name))
					addsum("Error: Could not copy grading script to new assignmenr folder");
				
				//copy submitted files into location
				copy_files(CLASS_PATH . $class_folder_name . "/" . $assig_folder_name);
			}
			else
			{
				addsum("Error: Could not create folder assignment folder");
			}
			
		}
	}
	else
	{
		addsum("Error: Class does not exist in directory");
	}
 	
 	//Put the summary int oa session variable to display 
 	//upon return to the assignment page
 	$_SESSION['upload_summary'] = $summary;
 
 	//Print summary for debugging purposes.
 	for($i = 0; $i < $sumline;$i++)
 	{
 		echo $summary[$i] . "<br>";
 	}
 	
 	//Function to copy files to directory specified
 	//It also places a line in the late.txt file if necessary
 	function copy_files($dirpath)
 	{
 	 	global $late;
 		$date = date_create();
 		foreach ($_FILES["userfile"]["error"] as $key => $error) 
 		{
    		if ($error == UPLOAD_ERR_OK) 
    		{
        		$tmp_name = $_FILES["userfile"]["tmp_name"][$key];
        		$name = $_FILES["userfile"]["name"][$key];
        		if(move_uploaded_file($tmp_name, $dirpath . "/$name"))
        		{
        			addsum("$name was uploaded successfuly.");
        			if($late == "true")
        			{
						$fh = fopen("late.txt", 'a');
						fwrite($fh, "$name was submited late on \n");
						fclose($fh);
        			}
        		}
        		else
        		{
        			addsum("Error: $name was not moved to directory successfully");
        		}
        	}
    		else
    		{
    			addsum("Error: File not uploaded successfully");
    		}
		}
 		
 	}
 	
 	//Function to maintain the Summary array
 	function addsum($toadd)
 	{
 		global $sumline, $summary;
 		$summary[$sumline] = $toadd;
 		$sumline++;
 	}
 	
 	//Parses the name of the folder that goes with the assignment
 	//Grabs the Assig title from the DB, explodes it then
 	//concats it with the proper characters according to
 	//the naming conventions we agreed upon
 	//Finally iy attaches the assigid with a "-" this should
 	//Be the name of the folder 
 	function parse_assig_name()
 	{
 		global $assigid, $classid, $db;
 		$toret = "";
 		$results = $db->arrayQuery("select * from Assignment where assignment_id = '$assigid' and class_id = '$classid';");
 		if(empty($results))
 		{
 			addsum("Error: Could not retrieve assignment information to parse folder name");
 			return "";
 		}
 		else
 		{
 			$assignment = $results[0];
 			$title = $assignment['title'];
 			$title_explode = explode(" ", $title);
 			for($i = 0; $i < count($title_explode); $i++)
 			{
 				$toret = $toret . $title_explode[$i];
 				if($i != count($title_explode)-1)
 					$toret = $toret . "_";
 			}
 			
 			$toret = $toret . "-";
 			$toret = $toret . $assigid;
 			addsum("Assignment folder name : " . $toret);
 			return $toret;
 		}
 	}
 	
 	//Similar function to parse_assig_name() but does it for the class folder
 	//to access according to our naming conventions
 	function parse_class_name()
 	{
 		global $assigid, $classid, $db;
 		$toret = "";
 		$results = $db->arrayQuery("select * from Class where class_id = '$classid';");
 		if(empty($results))
 		{
 			addsum("Error: Could not retrieve class information to parse folder name");
 			return "";
 		}
 		else
 		{
 			$class = $results[0];
 			$name = $class['class_name'];
 			$name_explode = explode(" ", $name);
 			for($i = 0; $i < count($name_explode); $i++)
 			{
 				$toret = $toret . $name_explode[$i];
 				if($i != count($name_explode)-1)
 					$toret = $toret . "_";
 			}
 			
 			$toret = $toret . "-";
 			$toret = $toret . $classid;
 			addsum("Class folder name : " . $toret);
 			return $toret;
 		}
 	}

	//return_to("submit_assig.php?class_id=0&assignment_id=0"); //don't forget to specify a page
?>