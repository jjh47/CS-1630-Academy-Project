<?
	require("glue.php");
	init("page");
	//enqueue_script($filename)
	get_header();



/* PLEASE CAREFULLY READ THESE COMMENTS!
 *
 * FIRST: specify any $_GET variables you need to have when getting to this page.  For example, if it is the page for viewing an assignment, assume that $_GET will have a variable representing the assignment ID.  That way, you know which assignment to query from the database.  TELL ME HERE WHAT YOU WANT THE VARIABLE TO BE NAMED.  This ensures that the pages will link together correctly.
 * use $_SESSION["username"] and $_SESSION["usertype"] to segregate the components of the page (i.e. if ($username != "admin") etc.)
 * use $db to make database calls
 * make calls to get ALL relevant information on the page loaded into PHP variables
 * CAREFULLY DOCUMENT the contents of these variables (e.g. $assignments is an array and each element is an array representing an assignment.  In this array, "id" => the ID of the course, "name" => the name of the course, etc)
 * Do not worry about having too much information loaded - it is easy to show only parts of it or show it in chunks with HTML/JavaScript.  Just worry about getting it on the page.
 *
 * FORMS: If this page is a data page and requires a form, please either 1. specify the fields the form needs to have (i.e. inputs: text "name", text "email", password "password").  This includes what type of input it is and WHAT THE NAME IS.  This is critical to making sure it lines up with get/post on the next page.  If you are comfortable writing HTML, simply write the form.  If any information from your PHP variables needs to be included, please either included it or leave careful instructions.
 * MAKE ABSOLUTELY SURE you use the add_token() method in every form or your form will not work
 *
 * FINALLY: don't forget to check if things exist?  Use the (bool ? A : B) notation to accomplish this.  For example.  $result = ((isset($var) && !empty($var)) ? $var : "" )
 *
 */

 
//TODO - the usertype hasn't been set yet after the login
if($_SESSION["usertype"] != "teacher" && $_SESSION["usertype"] != "admin"){
	//return_to("index.php");
}
if(isset($_GET['class_id']) && isset($_GET['assignment_id']) && isset($_GET['student_id'])){
	$class_id = $_GET['class_id'];
	$assignment_id = $_GET['assignment_id'];
	$student_id = $_GET['student_id'];
}
else{
	//TODO
	return_to("index.php");
}

echo "Class ID = $class_id <br/>";
echo "Assignment ID = $assignment_id <br/>";
echo "Student ID = $student_id <br/>";
echo "<br/>";

if(!is_dir($class_id)){
	echo "$class_id not found<br/>";
}
elseif(!is_dir("$class_id/$assignment_id")){
	echo "$assignment_id not found<br/>";
}
elseif(!is_dir("$class_id/$assignment_id/$student_id")){
	echo "$student_id not found<br/>";
}
else{
	$list = scandir("$class_id/$assignment_id/$student_id");
	$count = count($list) - 2; // minus the "." and ".."
	$late = LATE_FILE_NAME;
	if(in_array($late, $list)){
		$count--;
	}
	if(in_array("Results.txt", $list)){
		$count--;
	}
	echo "Total Files Submitted: $count <br/>";
	if($count > 0){
		echo "<ul>";
		foreach($list as $file){
			if($file != "." && $file != ".." && $file != $late && $file != "Results.txt"){
				echo "<li>$file</li>";
			}
		}
		echo "</ul>";
		echo "Late Submission:";
		if(in_array($late, $list)){
			echo "<ul>";
			$handle = file("$class_id/$assignment_id/$student_id/$late");
			foreach ($handle as $line_num => $line) {
				echo "<li>$line</li>";
			}
			echo "</ul>";
		}
		else{
			echo "-- <br/>";
		}
		echo "<b>Source Code<br/></b>";
		foreach($list as $file){
			if($file != "." && $file != ".." && $file != $late && $file != "Results.txt"){
				echo "$file";
				echo "<pre>";
				if(!file_exists("$class_id/$assignment_id/$student_id/$file")){
					echo "File does not exists<br/>";
				}
				else{
					$handle = file("$class_id/$assignment_id/$student_id/$file");
					foreach ($handle as $line_num => $line) {
						echo "Line # " . ($line_num + 1) ." : " . $line;
					}
				}
				echo "</pre>";
			}
		}
		echo "<b>Result:</b>";
		echo "<pre>";
		if(in_array("Results.txt", $list)){
			
			$handle = file("$class_id/$assignment_id/$student_id/Results.txt");
			foreach ($handle as $line_num => $line) {
				echo "$line";
			}
			
		}
		else{
			echo "Results.txt not found <br/>";
		}
		echo "</pre>";
	}
}

?>

<? get_footer(); ?>