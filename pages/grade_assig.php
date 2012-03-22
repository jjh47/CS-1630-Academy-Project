<?
	require("../glue.php");
	init("page");
	enqueue_script("jquery.flexipage.min.js");
	get_header();
	 
	if($_SESSION["usertype"] != "teacher" && $_SESSION["usertype"] != "admin")
	{
		error_message("Invalid permissions...");
		get_footer();
		die;
	}

	if (isset($_GET["class_id"]) && isset($_GET["assignment_id"]))
	{
		$class_id = sqlite_escape_string($_GET['class_id']);
		$assignment_id = sqlite_escape_string($_GET['assignment_id']);
	}
	else
	{
		$assignment_id = 0;
		$class_id = 0;
		/**
			show drop downs for selecting classes
		*/
	}

	$results = $db->arrayQuery("select * from class where class_id='$class_id'");
	if (empty($results))
	{
		error_message("Invalid course selection...");
		get_footer();
		die;
	}
	$course = $results[0];
	$course_title = $course["class_name"];
	$class_path = preg_replace("/\ /","_",$course_title)."-".$course["class_id"];

	$results = $db->arrayQuery("select * from assignment where assignment_id='$assignment_id'");
	if (empty($results))
	{
		error_message("Invalid assignment selection...");
		get_footer();
		die;
	}
	$assignment = $results[0];
	$assignment_title = $assignment['title'];
	$assig_path = preg_replace("/\ /", '_', $assignment_title)."-".$assignment["assignment_id"];	

	echo "<h1>Grading $assignment_title</h1>";
	echo "<div id='success-message' class='info message' style='display: none;'>Grade Successfully Submitted</div>";
	echo "<div id='failure-message' class='warning message' style='display: none;'>Error Submitting Grade</div>";
	echo "<div id='caution-message' class='caution message' style='display: none;'>Grade May Not Have Been Submitted</div>";


	if(!is_dir(BASE_PATH.$class_path))
	{
		error_message("Folder for class not found.");
		get_footer();
		die;
	}
	if (!is_dir(BASE_PATH.$class_path."/".$assig_path))
	{
		error_message("Folder for assignment not found.");
		get_footer();
		die;	
	}


	$full_assig_path = BASE_PATH.$class_path."/".$assig_path;



	$folder_list = scandir($full_assig_path);
	array_shift($folder_list);
	array_shift($folder_list);

	foreach ($folder_list as $key => $value)
	{
		if (!is_dir($full_assig_path."/".$value))
		{
			unset($folder_list[$key]);
		}
	}


	if (empty($folder_list))
	{
		error_message("No student submissions available.");
		get_footer();
		die;
	}

	echo "<ul id='student-files'>";
	foreach ($folder_list as $student_folder)
	{
		echo "<li><div class='grade-sheet'>";

		$tokens = preg_split("/-/",$student_folder);
		$student_name = preg_replace("/_/", " ", $tokens[0]);
		$user_id = $tokens[1];

		echo "<h2>$student_name</h2>";

		$student_path = $full_assig_path."/".$student_folder;
		$file_list = scandir($student_path);
		array_shift($file_list);
		array_shift($file_list);

		echo "Select a file:<br>";
		echo "<select>\n";
		echo "<option value='none'>--</option>\n";
		
		$count = 0;
		foreach ($file_list as $file)
		{
			echo "<option value='$file' id='file$count-$user_id'>$file</option>\n";
			$count++;
		}
		echo "</select>";

		$count = 0;
		foreach ($file_list as $file)
		{
			$file_path = $student_path."/".$file;
			echo "<div class='code-block' style='display: none;' id='file$count-$user_id-code'>";
			echo "<pre>";
			$lines = file($file_path);
			$linenum = 0;
			foreach ($lines as $line)
			{
				echo "$linenum: &nbsp;&nbsp;&nbsp;$line";
				$linenum++;
			}

			echo "</pre>";
			echo "</div>";

			$count++;
		}
		?>
		<br><br>
		<b>Grading Rubric: </b>
		<form id="grading_rubric<?= $user_id ?>" method="post" action="process_grade.php" onsubmit='return submit_grading_form(<?= $user_id ?>)'>
			<table>
				<tr><td>Program Execution:</td><td><input type="text" name="gr1" id="gr1" onkeyup="calculateTotal(<?= $user_id ?>)" size="2" maxlength="2" /></td></tr>
				<tr><td>Program Specification:</td><td><input type="text" name="gr2" id="gr2" onkeyup="calculateTotal(<?= $user_id ?>)" size="2" maxlength="2" /></td></tr>
				<tr><td>Readability:</td><td><input type="text" name="gr3" id="gr3" onkeyup="calculateTotal(<?= $user_id ?>)" size="2" maxlength="2" /><br/></td></tr>
				<tr><td>Reusability:</td><td><input type="text" name="gr4" id="gr4" onkeyup="calculateTotal(<?= $user_id ?>)" size="2" maxlength="2" /></td></tr>
				<tr><td>Documentation:</td><td><input type="text" name="gr5" id="gr5" onkeyup="calculateTotal(<?= $user_id ?>)" size="2" maxlength="1" /></td></tr>
				<tr><td>Accountable Use of Class Time</td><td><input type="text" name="gr6" id="gr6" onkeyup="calculateTotal(<?= $user_id ?>)" size="2" maxlength="1" /></td></tr>
				<tr><td>Lab Report:</td><td><input type="text" name="gr7" id="gr7" onkeyup="calculateTotal(<?= $user_id ?>)" size="2" maxlength="2" /></td></tr>
				<tr><td>Timeliness:</td><td><input type="text" name="gr8" id="gr8" onkeyup="calculateTotal(<?= $user_id ?>)" size="2" maxlength="1" /></td></tr>
				<tr><td>Total:</td><td><input type='text' id='total<?= $user_id ?>' value = '0' disabled='disabled' size='2' /></td><td><input type='hidden' name='user_id' value='<?= $user_id ?>'><input type='hidden' name='assignment_id' value='<?= $assignment_id ?>'></td></tr>
			</table>
			<input type="submit" class="button" value="Submit" id ="submit_grading_rubric"/>&nbsp;
			<input type="reset" class="button" value="Reset" id="reset_grading_rubric" onclick='reset_grading_form(<?= $user_id ?>)'/><br/>
			<? add_token(); ?>
		</form>
	<?


		echo "</div></li>";
	}
	echo "</ul>";

	?>
		<script>
		function calculateTotal(user_id){
			var test = 0;
			for(i = 1; i <= 8; i++){
				var form = $('#grading_rubric' + user_id);

				if(isNaN(parseInt($(form).find('#gr'+i).val()))) continue;
				test += parseInt($(form).find('#gr'+i).val());
			}
			var id = "#total" + user_id;
			$(id).attr("value",test);
		}
		function reset_grading_form(user_id){
			var id = "#total" + user_id;
			$(id).attr("value",0);
		}
		function submit_grading_form(user_id){

			var amt = $('#total').html();
			var total = parseInt(amt);

			var allFilled = true;
			var id = "#grading_rubric" + user_id;
			var lists = "#grading_rubric" + user_id + ": input";

			$("form#" + id + " :input").each(function(){
				if ($(this).val() == ""){
					allFilled = false;
				}
			});

			if (!allFilled){
				alert("All fields must be filled out.");
				return false;
			}

			if (total > 70){
				alert("Invalid score.  Maximum score is 70.");
				return false;
			}
			else{
				$data = $(id).serialize();
				post("process_grade.php",$data,function(data){
					if (data.indexOf("error") != -1){
						$('#failure-message').show("slow");
						setTimeout(function(){
							$('#failure-message').hide("slow");
						},2500);
					}
					else if (data.indexOf("success") != -1){
						$('#success-message').show("slow");
						setTimeout(function(){
							$('#success-message').hide("slow");
						},2500);
					}
					else{
						$('#caution-message').show("slow");
						setTimeout(function(){
							$('#caution-message').hide("slow");
						},2500);	
					}
				});
				return false;
			}
		}

		var current_file = "";

		$(document).ready(function(){
			$("select").each(function(){
				$(this).change(function(){
					var id = $(this).find("option:selected").attr("id");
					var code = "#" + id + "-code";
					if (current_file != ""){
						$(current_file).hide("slow");
					}
					$(code).show("slow");
					current_file = code;
				});
			});

			$("#student-files").flexipage({
				element: 	'li',
				perpage: 	1
			});
		});

		</script>
	<?
		

?>

<? get_footer(); ?>











