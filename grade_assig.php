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
	?>
	<script>
	function calculateTotal(){
		var test = 0;
		for(i = 1; i <= 8; i++){
			if(isNaN(parseInt($('#gr'+i).val()))) continue;
			test += parseInt($('#gr'+i).val());
		}
		var oChild = document.getElementById("total");
		var oNewChild = document.createElement('a');
		oNewChild.id = oChild.id;
		oNewChild.innerHTML = test;
		oChild.parentNode.replaceChild(oNewChild,oChild)
	}
	function reset_grading_form(){
		for(i = 1; i <= 8; i++){
			$('#gr'+i).val("");
		}
		var oChild = document.getElementById("total");
		var oNewChild = document.createElement('a');
		oNewChild.id = oChild.id;
		oNewChild.innerHTML = 0;
		oChild.parentNode.replaceChild(oNewChild,oChild)
	}
	function submit_grading_form(){
		//TODO
		alert('not yet implement');
	}
	</script>
	<b>Grading Rubric: </b>
	<form id="grading_rubric" method="post">
		<table>
		<tr>
		<td>
		Program Execution:<br/>
		Program Specification:<br/>
		Readability:<br/>
		Reusability:<br/>
		Documentation:<br/>
		Accountable use of class time:<br/>
		Lab Report:<br/>
		Timeliness:<br/>
		Total:<br/>
		</td>
		<td>
		<input type="text" name="gr1" id="gr1" onkeyup="calculateTotal()" size="2" maxlength="2" /><br/>
		<input type="text" name="gr2" id="gr2" onkeyup="calculateTotal()" size="2" maxlength="2" /><br/>
		<input type="text" name="gr3" id="gr3" onkeyup="calculateTotal()" size="2" maxlength="2" /><br/>
		<input type="text" name="gr4" id="gr4" onkeyup="calculateTotal()" size="2" maxlength="2" /><br/>
		<input type="text" name="gr5" id="gr5" onkeyup="calculateTotal()" size="2" maxlength="2" /><br/>
		<input type="text" name="gr6" id="gr6" onkeyup="calculateTotal()" size="2" maxlength="2" /><br/>
		<input type="text" name="gr7" id="gr7" onkeyup="calculateTotal()" size="2" maxlength="2" /><br/>
		<input type="text" name="gr8" id="gr8" onkeyup="calculateTotal()" size="2" maxlength="2" /><br/>
		<a id="total">0</a><br/>
		</td>
		<tr>
		<td>
		<input type="button" class="button" value="Submit" id ="submit_grading_rubric" onclick = "submit_grading_form()" />&nbsp
		<input type="button" class="button" value="Reset" id="reset_grading_rubric" onclick="reset_grading_form()" /><br/>
		</td>
		</tr>
		</table>
		<? add_token(); ?>
	</form>
	<?
	
}

?>

<? get_footer(); ?>











