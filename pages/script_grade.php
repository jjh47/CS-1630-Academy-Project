<?
//check to make sure this is being run from teh command line
if (!empty($_SERVER['REMOTE_ADDR']) && isset($_SERVER['HTTP_USER_AGENT']) && !count($_SERVER['argv']))
{
	include("../glue.php");
	header("Location: ".HOME_DIR);
	die;
}
$args = $argv;
$dir = getcwd();
$python = false;
//if the user wants to compile all student projects
if($args[1] == "-c"){
	//as long as the java file name that should be compiled exists
	if($args[2] != null){
		$javaFile = $args[2];
		if(substr($javaFile, -3)==".py"){$python = true;}
		$folders = scandir($dir);
		//for each student folder in the assignment folder
		foreach($folders as $folder){
			if($folder != ".." && $folder != "." && $folder != "script_grade.php" && substr($folder, -4) != ".txt" && substr($folder, -4) != ".dat" && substr($folder, -1) != "~"){
				//change directory to a student's submission folder
				$return = chdir($folder);
				if($return == false){
					print("unable to change directory into ".$folder."\n");
				}

				//compile the java program and record output
				if($python == false){
					//The suffix on this command is so that the output of each of these compilations are not displayed on the console
					$output = shell_exec("javac ".$javaFile." 2>&1 1> /dev/null");
				}
				//took this out because if it compiles correctly the variable is null.
				//if($output == null){
				//	print("an error occured while compiling ".$folder."'s project");
				//}

				//open/create the results file and write the output to it.
				$fp = fopen('Results.txt', 'a+');
				if($fp == false){
					print("error occured while opening/creating ".$folder."'s Results.txt\n");
				}
				$return = fwrite($fp, "\nCompilation of ".$javaFile."\n--------------------------------------------------\n\n");
				if($return == false){	
					print("Unable to write to ".$folder."'s Results.txt");	
				}
				$return = fwrite($fp, $output."\n\n");

				if($return == false){	
					print("Unable to write to ".$folder."'s Results.txt");	
				}
				fclose($fp);
				chdir("..");
			}
		}
	}
	else{print("please specify the name of the java file that should be compiled\n");}
}

if($args[1] == "-d"){
	if($args[2] == "all"){
		$folders = scandir($dir);
		foreach($folders as $folder){
			if($folder != ".." && $folder != "." && $folder != "script_grade.php" && substr($folder, -4) != ".txt" && substr($folder, -4) != ".dat" && substr($folder, -1) != "~"){
				//navigate to student's submission folder
				$return = chdir($folder);
				if($return == false){print("unable to change directory into ".$folder."\n");}
				//delete the Results.txt file
				$return = unlink("Results.txt");
				if($return == false){print("unable to delete ".$folder."'s Results.txt\n");}
				//change directory back to assignment folder
				chdir("..");		
			}	
		}
		
	}
	//delete a single student's results.txt file
	else if($args[2] != null){
		$return = chdir($args[2]);
		if($return == false){print("unable to change directory into ".$args[2]."\n");}
		$return = unlink("Results.txt");
		if($return == false){print("unable to delete ".$args[2]."'s Results.txt\n");}
	}
	else{print("please specify which results files you would like to delete, 'all' or a specific user name\n");}
}	

if($args[1] == "-t"){
	if($args[2] != null){
		if(isset($args[3]) && $args[3] == "-f"){
			//if the teacher wants to run the files with command line arguments she would do this:
			//php grade.php -t "test 1 2 3" -f test1.txt
			//this will run test with 1 2 3 as command line arguments and then use test1.txt as the input for the program
			if($args[4] != null){
				$testFile = $args[4];
				$className = $args[2];
				$folders = scandir($dir);
				if(substr($className, -3)==".py"){$python = true;}
				//The only way to communicate run-time inputs is to run the program as an external process.

				foreach($folders as $folder){
					if($folder != ".." && $folder != "." && $folder != "script_grade.php" && substr($folder, -4) != ".txt" && substr($folder, -4) != ".dat" && substr($folder, -1) != "~"){
						//get the lines of the test file and put them into an array.
						$inputLines = File($testFile);
						
						//move to the student's submission directory					
						chdir($folder);
						//write title for the output results
						$fp = fopen("Results.txt", 'a+');
						fwrite($fp, "\nOutput of running \n--------------------------------------------------\n\n");
						fclose($fp);
						//this is a discriptor for the proc_open command that specifies how stdin, stdout, and stderr should be handled
						$Spec = array (
  						0 => array('pipe','r'),     
  						1 => array('file','./Results.txt','a'), 
  						2 => array('file','./Results.txt','a'),
						); 
						$handles = array(); 

						//start the process	
						if($python == false){
							$process = proc_open('java '.$className, $Spec, $handles, getcwd());
						}
						else{
							$process = proc_open('python '.$className, $Spec, $handles, getcwd());
						}


						stream_set_blocking($handles[0], 0);
						if (is_resource($process)) {
							foreach ($inputLines as $line){
								
								$return = fwrite($handles[0], $line);
								if($return == false){	
									print("Unable to write to ".$folder."'s Results.txt");	
								}		
								fflush($handles[0]);
							}

						}
						fclose($handles[0]);
						proc_close($process);
						chdir("..");


					}
				}

			}
			//if the teacher wants to run the projects without a test file and record the output.
			else{print("When using the -f flag you must specify a test file to use");}
			
		}
		else{
		$className = $args[2];
				$folders = scandir($dir);
				if(substr($className, -3)==".py"){$python = true;}

				foreach($folders as $folder){
					if($folder != ".." && $folder != "." && $folder != "script_grade.php" && substr($folder, -4) != ".txt" && substr($folder, -4) != ".dat" && substr($folder, -1) != "~"){
						chdir($folder);
						$fp = fopen("Results.txt", 'a+');
						$return = fwrite($fp, "\nOutput of running \n--------------------------------------------------\n\n");
						if($return == false){	
							print("Unable to write to ".$folder."'s Results.txt");	
						}
						fclose($fp);
						if($python == false){
							$output = shell_exec("java ".$className." 2>&1 ");
						}
						else{
							$output = shell_exec("python ".$className." 2>&1 ");
						}
						$fp = fopen('Results.txt', 'a+');
						
						$return = fwrite($fp, $output);
						if($return == false){	
							print("Unable to write to ".$folder."'s Results.txt");	
						}

						$return = fclose($fp);
						chdir("..");
					}
				}
			}
	}
	else{print("please indicate the name of the compiled project file to be run");}
	

}

	
?>
