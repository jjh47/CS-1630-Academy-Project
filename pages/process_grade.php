<?
	require("../glue.php");
	init("form_process");
	get_header();

	if($_SESSION["usertype"] != "teacher" && $_SESSION["usertype"] != "admin")
	{
		error_message("Invalid permissions...");
		get_footer();
		die;
	}

	else{
		if (isset($_POST["assignment_id"]) && isset($_POST["user_id"]) && isset($_POST["gr1"]) 
			&& isset($_POST["gr2"]) && isset($_POST["gr3"]) && isset($_POST["gr4"])
			&& isset($_POST["gr5"]) && isset($_POST["gr6"]) && isset($_POST["gr7"])
			&& isset($_POST["gr8"]))
		{
			$user_id = sqlite_escape_string($_POST['user_id']);
			$assignment_id = sqlite_escape_string($_POST['assignment_id']);
			$program_Execution = sqlite_escape_string($_POST['gr1']);
			$program_Specification = sqlite_escape_string($_POST['gr2']);
			$readability = sqlite_escape_string($_POST['gr3']);
			$reusability = sqlite_escape_string($_POST['gr4']);
			$documentation = sqlite_escape_string($_POST['gr5']);
			$accountable = sqlite_escape_string($_POST['gr6']);
			$lab_Report = sqlite_escape_string($_POST['gr7']);
			$timeliness = sqlite_escape_string($_POST['gr8']);
			$Total = $program_Execution+$program_Specification+$radability+$reusability+$documentation+$accountable+$lab_Report+$timeliness;
			
			$results = $db->array_query("select * from User where user_id = '".$user_id."'");
			if(empty($results)){
				echo("error");
				die;
			}
			$username = $results["username"];
			$email = $results["email"];

			$results = $db->array_query("select * from Assignment where assignment_id = '".$assignment_id."'");
			if(empty($results)){
				echo("error");
				die;
			}
			$title = $results["title"];
			

			$subject = "Grade for ".$title;
			$message = $username.","."\n\nYour submission for ".$title."has been graded. \n\n Breakdown of Score:\n\n\n
						Program Execution: ".$program_Execution."\nProgram Specification: ".$program_Specification."\n
						Readability: ".$readability."\nReusability: ".$reusability."\nDocumentation: ".$documentation."\n
						Accountable Use of Class Time: ".$accountable."\nLab Report: ".$lab_Report."\ntimeliness: ".$timeliness."\n
						Total: ".$Total."\n\n\nIf you have an questions/concerns about your grade, feel free to contact your teacher";
			$return = mail($email , $subject , $message );
			if($return == false){
				echo("error");
				die;
			}

			$results = $db->array_query("select * from grade where user_id = '".$user_id."' and assignment_id = '".$assignment_id."'");
			//grade has not been submitted yet so insert
			if(empty($results)){
				$result = queryExec("insert into grade values(".$user_id.", ".$assignment_id.", ".$Total.")");
				if(empty($result)){
					echo("error");
					die;
				}
				else{
					echo("success");
				}
			}
			//grade has been inserted before so update
			else{

				$result = queryExec("Update grade set grade = ".$Total." where user_id = ".$user_id." and assignment_id = ".$assignment_id);
				
			}

		}	

	}










	get_footer();

	?>