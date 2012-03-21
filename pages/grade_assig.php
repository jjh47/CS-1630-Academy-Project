<?
	require("../glue.php");
	init("page");
	//enqueue_script($filename)
	get_header();
 
if($_SESSION["usertype"] != "teacher" && $_SESSION["usertype"] != "admin"){
	//return_to(HOME_DIR);
}
if(isset($_GET['class_id']) && isset($_GET['assignment_id']) && isset($_GET['student_id'])){
	$class_id = $_GET['class_id'];
	$assignment_id = $_GET['assignment_id'];
	$student_id = $_GET['student_id'];
}
else{
	return_to(HOME_DIR);
}

echo "Class ID = $class_id <br/>";
echo "Assignment ID = $assignment_id <br/>";
echo "Student ID = $student_id <br/>";
echo "<br/>";

if(!is_dir(CLASS_PATH . "$class_id")){
	echo "$class_id not found<br/>";
}
elseif(!is_dir(CLASS_PATH . "$class_id/$assignment_id")){
	echo "$assignment_id not found<br/>";
}
elseif(!is_dir(CLASS_PATH . "$class_id/$assignment_id/$student_id")){
	echo "$student_id not found<br/>";
}
else{
	$list = scandir(CLASS_PATH . "$class_id/$assignment_id/$student_id");
	$count = count($list) - 2; // minus the "." and ".."
	$late = LATE_FILE_NAME;
	if(in_array($late, $list)){
		$count--;
	}
	if(in_array(RESULT_FILE_NAME, $list)){
		$count--;
	}
	echo "Total Files Submitted: $count <br/>";
	if($count > 0){
		echo "<ul>";
		foreach($list as $file){
			if($file != "." && $file != ".." && $file != $late && $file != RESULT_FILE_NAME){
				echo "<li>$file</li>";
			}
		}
		echo "</ul>";
		echo "Late Submission:";
		if(in_array($late, $list)){
			echo "<ul>";
			$handle = file(CLASS_PATH . "$class_id/$assignment_id/$student_id/$late");
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
			if($file != "." && $file != ".." && $file != $late && $file != RESULT_FILE_NAME){
				echo "$file";
				echo "<pre>";
				if(!file_exists(CLASS_PATH . "$class_id/$assignment_id/$student_id/$file")){
					echo "File does not exists<br/>";
				}
				else{
					$handle = file(CLASS_PATH . "$class_id/$assignment_id/$student_id/$file");
					foreach ($handle as $line_num => $line) {
						echo "Line # " . ($line_num + 1) ." : " . $line;
					}
				}
				echo "</pre>";
			}
		}
		echo "<b>Result:</b>";
		echo "<pre>";
		if(in_array(RESULT_FILE_NAME, $list)){
			
			$handle = file(CLASS_PATH . "$class_id/$assignment_id/$student_id/". RESULT_FILE_NAME);
			foreach ($handle as $line_num => $line) {
				echo "$line";
			}
			
		}
		else{
			echo RESULT_FILE_NAME . " not found <br/>";
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











