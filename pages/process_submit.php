<?
	require("../glue.php");
	init("form_process");

 ///////////////
 ///Variables///
 /////////////////////////////////////////////////////////////////////////////////////////////////

 	//Get variables from post and create variables needed
 	$classid = $_POST['course_id'];
 	$assigid = $_POST['assignment_id'];
 	$late = $_POST['late'];
 	$sumline = 0;
 	$summary;
 	
 	
////////////////////
///Main/////////////
///////////////////////////////////////////////////////////////////////////////////////////////////
 	
 	//Parse the names of the folders to access/create
 	//These functions could cause problems but are the only way 
 	//I see to deal with naming contraints we agreed too.
 	$assig_folder_name = parse_assig_name();
 	$class_folder_name = parse_class_name();
 	$student_folder_name = parse_student_name();
 	
 	//Check to see if class folder exists

	if(!is_dir(CLASS_PATH . $class_folder_name)) 
	{
		mkdir(CLASS_PATH . $class_folder_name);	
	}

 	if(is_dir(CLASS_PATH . $class_folder_name))
 	{
 		chdir(CLASS_PATH . $class_folder_name);
		if(is_dir($assig_folder_name))
		{
			if(is_dir($assig_folder_name . "/" . $student_folder_name))
			{

				//copy grading script
				if(!copy(GSCRIPT_PATH, CLASS_PATH . $class_folder_name . "/" . $assig_folder_name . "/script_grade.php"))
				update_log(0, "Error: Could not copy grading script to new assignment folder");

				//Copy submitted files to location
				copy_files(CLASS_PATH . $class_folder_name . "/" . $assig_folder_name . "/" . $student_folder_name);
			}
			else
			{
				//create student folder
				if(mkdir(CLASS_PATH . $class_folder_name . "/" . $assig_folder_name . "/" . $student_folder_name))
				{
					//copy grading script
					if(!copy(GSCRIPT_PATH, CLASS_PATH . $class_folder_name . "/" . $assig_folder_name. "/script_grade.php"))
					update_log(0, "Error: Could not copy grading script to new assignment folder");
				
					//copy submitted files into location
					copy_files(CLASS_PATH . $class_folder_name . "/" . $assig_folder_name . "/" . $student_folder_name);
				}
				else
				{
					addsum("WARNING: NO FILES UPLOADED. ERROR: Could not create student directory");
				}
			}
		}
		else
		{
			//create folder
			if(mkdir(CLASS_PATH . $class_folder_name . "/" . $assig_folder_name))
			{
				
				if(is_dir($assig_folder_name . "/" . $student_folder_name))
				{

					//copy grading script
					if(!copy(GSCRIPT_PATH, CLASS_PATH . $class_folder_name . "/" . $assig_folder_name. "/script_grade.php"))
						update_log(0, "Error: Could not copy grading script to new assignment folder");

					//Copy submitted files to location
					copy_files(CLASS_PATH . $class_folder_name . "/" . $assig_folder_name . "/" . $student_folder_name);
				}
				else
				{
					//create student folder
					if(mkdir(CLASS_PATH . $class_folder_name . "/" . $assig_folder_name . "/" . $student_folder_name))
					{
						//copy grading script
						if(!copy(GSCRIPT_PATH, CLASS_PATH . $class_folder_name . "/" . $assig_folder_name. "/script_grade.php"))
						update_log(0, "Error: Could not copy grading script to new assignment folder");
				
						//copy submitted files into location
						copy_files(CLASS_PATH . $class_folder_name . "/" . $assig_folder_name . "/" . $student_folder_name);
					}
					else
					{
						addsum("WARNING: NO FILES UPLOADED. ERROR: Could not create student directory");
					}
				}
			}
			else
			{
				addsum("WARNING: NO FILES UPLOADED. ERROR: Could not create assignment directory");
			}
			
		}
	}
	else
	{
		addsum("WARNING: NO FILES UPLOADED. ERROR: Could not create class directory.");
	}
 	
 	//Put the summary int oa session variable to display 
 	//upon return to the assignment page
 	$_SESSION['upload_summary'] = $summary;
 
 	//Print summary for debugging purposes.
 	/*for($i = 0; $i < $sumline;$i++)
 	{
 		echo $summary[$i] . "<br>";
 	}*/
 	
 	return_to("view_assig.php?class_id=$classid&assignment_id=$assigid"); //don't forget to specify a page
 	
 	
 	
 	
 ///////////////
 ///Functions///
 /////////////////////////////////////////////////////////////////////////////////////////////////
 	
 	//Function to copy files to directory specified
 	//It also places a line in the late.txt file if necessary
 	function copy_files($dirpath)
 	{
 	 	global $late;
 	 	
 	 	//CHECK FOR FILES NAMED "results.txt or late.txt"
 	 	if(file_name_error())
 	 	{
 	 		addsum("WARNING: NO FILES WERE UPLOADED DUE TO A FILE NAMED 'results.txt' OR 'late.txt',
 	 			   THESE FILENAMES ARE NOT ACCEPTED");
 	 		return;
 	 	}
 	 	
 	 	//Iterate through files
 		foreach ($_FILES["userfile"]["error"] as $key => $error) 
 		{
 			//Check error
    		if ($error == UPLOAD_ERR_OK) 
    		{
        		$tmp_name = $_FILES["userfile"]["tmp_name"][$key];
        		$name = $_FILES["userfile"]["name"][$key];
        		
        		//Check move uploadd error
        		if(move_uploaded_file($tmp_name, $dirpath . "/$name"))
        		{
        			//Add to summary and update log
        			addsum("$name was uploaded successfuly.");
        			update_log(1, "$name was uploaded successfully.");
        			
        			//Append to late text file if late and confirm on time if not
        			if($late == "true")
        				append_late("$name was uploaded late.");
        			else
        			{
        				if(!confirm_on_time())
        				{
        					append_late("$name was not uploaded late but is SUSPECT as timestamps infer it actually is LATE. ");
        				}
        			}
        				
        		}
        		else
        		{
        			addsum("Error: $name was not moved to directory successfully");
        			update_log(0, "Error: $name was not moved to directory successfully");
        		}
        	}
        	elseif ($error == UPLOAD_ERR_NO_FILE)
        	{
        		
        	}
    		else
    		{
    			addsum("Error: File not uploaded successfully");
    			update_log(0,"Error: File not uploaded successfully");
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
 			return $toret;
 		}
 	}

 	function parse_student_name()
 	{
 		global $db;
 		$user_id = $_SESSION['user_id'];
 		$toret = "";
 		$results = $db->arrayQuery("select * from User where user_id = '$user_id';");
 		if(empty($results))
 		{
 			addsum("Error: Could not retrieve student information to parse folder name");
 			return "";
 		}
 		else
 		{
 			$user = $results[0];
 			$name = $user['username'];
 			$name_explode = explode(" ", $name);
 			for($i = 0; $i < count($name_explode); $i++)
 			{
 				$toret = $toret . $name_explode[$i];
 				if($i != count($name_explode)-1)
 					$toret = $toret . "_";
 			}
 			
 			$toret = $toret . "-";
 			$toret = $toret . $user_id;
 			return $toret;
 		}
 	}
 	
 	
 	
 	
 	//Function to update the log table with information about each file upload.
 	function update_log($success, $comment)
 	{
 		global $db, $assigid, $classid;
 		$user_id = $_SESSION['user_id'];
 		$username = $_SESSION['username'];
 		$submission_time = date("Y-m-d H:i:s");
 		
 		if(!$db->queryExec("insert into Log values (NULL, $assigid, $classid, $user_id, 
 		                    '$username', '$submission_time', $success, '$comment');", $err))
		{
			addsum("Error: Could not write to log for commented log entry: $comment. ERRMESS: $err");
		}
 	}
 	
 	
 	
 	//This function is purely for testing. Shows the contents of the log table.
 	function show_log()
 	{
 		global $db;
 		$res = $db->arrayQuery("select * from Log");
 		if(empty($res))
 		{
 			addsum("Error displaying table information");
 		}
 		else
 		{
 			addsum("Log Table");
 			addsum("---------------------------------------");
 			for($i = 0; $i < count($res); $i++)
 			{
 				$curr = $res[$i];
 				addsum("$curr[submission_id] $curr[assignment_id] $curr[course_id] $curr[user_id] 
 				        $curr[username] $curr[submission_time] $curr[successful] $curr[comment]");
 			}
 		}
 	}
 	
 	
 	//Function to examine all files to be uploaded and check to see if one is named
 	//results.txt or late.txt
 	function file_name_error()
 	{
 		foreach ($_FILES["userfile"]["error"] as $key => $error) 
 		{
    		if ($error == UPLOAD_ERR_OK) 
    		{
        		$name = $_FILES["userfile"]["name"][$key];
        		if($name == "results.txt" || $name == "late.txt")
        			return true;
    		}
		}
		return false;
 	}

	//Function to confirm that the assignment is actually on time
	function confirm_on_time()
	{
		global $assigid, $db;
		$results = $db->arrayQuery("select * from Assignment where assignment_id = '$assigid';");
		$assig = $results[0];
		$duedate = $assig['due_date'];
		$now = time();
		
		if($now < $duedate)
		{
			return false;
		}
			
		return true;
	}
	
	//Function to append to the late.txt file
	function append_late($towrite)
	{
		global $class_folder_name, $assig_folder_name, $student_folder_name;
		$fh = fopen(CLASS_PATH . $class_folder_name . "/" . $assig_folder_name . "/" . $student_folder_name .'/late.txt', 'a+');
		$d = date("Y-m-d H:i:s");;
		fwrite($fh, "$towrite. TIMESTAMP: $d\n");
		fclose($fh);
	}

?>