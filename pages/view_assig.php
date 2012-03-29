<?
	require("../glue.php");
	init("page");
	get_header();

	$user_id = $_SESSION["user_id"];

	if(isset($_GET['class_id']) && isset($_GET['assignment_id']))
	{
		$class_id = sqlite_escape_string($_GET['class_id']);
		$assignment_id = sqlite_escape_string($_GET['assignment_id']);
	}
	else
	{
		error_message("Invalid information provided...");
		get_footer();
		die;
	}

	$results = $db->arrayQuery("select * from assignment where class_id = '$class_id' and assignment_id = '$assignment_id'");

	if (empty($results))
	{
		error_message("The assignment you have selected does not exist...");
		get_footer();
		die;
	}
	else
	{
		$result = $results[0];
	}

	$assignment_id = $result["assignment_id"];
	$title = $result["title"];
	$date_assigned = $result["date_assigned"];
	$description = $result["description"];
	$due_date = $result["due_date"];
	$late_due_date = $result["late_due_date"];
	$is_open = $result["is_open"];
	$num_files = $result["num_files_required"];

	if (!$is_open && $_SESSION["usertype"] == "student")
	{
		error_message("This assignment is currently closed...");
		get_footer();
		die;
	}

	$results = $db->arrayQuery("select * from enrollment where class_id='$class_id' and user_id='$user_id';");
	if (empty($results))
	{
		error_message("You do not have permissions to view the assignment you have selected.");
		get_footer();
		die;
	}

	if (isset($_SESSION['upload_summary']))
	{
		echo "<div id='upload-summary' class='info message'><ol>";
		foreach ($_SESSION['upload_summary'] as $item)
		{
			echo "<li>$item</li>";
		}
		echo "</ol></div>";
		?>
		<script>
			setTimeout(function(){
				$('#upload-summary').hide("slow");
			}, 3000);
		</script>
		<?
		unset($_SESSION["upload_summary"]);
	}


	if (isset($_SESSION["edit_assignment_success"]))
	{
		echo "<div id='assignment-creation-message' class='info message'>".$_SESSION["edit_assignment_success"]."<br></div>";
		?>
			<script>
				setTimeout(function(){
					$("#assignment-creation-message").hide("slow");
				}, 2500);
			</script>
		<?
		unset($_SESSION["edit_assignment_success"]);
	}
	elseif (isset($_SESSION["edit_assignment_error"]))
	{
		echo "<div id='assignment-creation-message' class='warning message'>".$_SESSION["edit_assignment_error"]."<br></div>";
		?>
			<script>
				setTimeout(function(){
					$("#assignment-creation-message").hide("slow");
				}, 2500);
			</script>
		<?
		unset($_SESSION["edit_assignment_error"]);
	}

	$now = time();

	//|| strtotime($late_due_date) < time() 

	if ($now > strtotime($late_due_date) && $_SESSION["usertype"] == "student")
	{
		echo "<div class='warning message'>This assignment is closed for submission.</div>";
		$closed = true;
	}

	elseif ($now > strtotime($due_date) && $_SESSION["usertype"] == "student")
	{
		echo "<div class='warning message'>This assignment was due by $due_date.  Any files submitted will now be counted as late.</div>";
	}

	echo("<p> Title: <b> ".$title."</b></p>");
	echo("<p> Date Assigned: <b> ".date("l, F j, Y \a\\t g:ia",strtotime($date_assigned))."</b></p>");
	echo("<p> Description: <b> ".$description."</b></p>");
	echo("<p> Due Date: <b> ".date("l, F j, Y \a\\t g:ia",strtotime($due_date))."</b></p>");
	echo("<p> Late Due Date: <b> ".date("l, F j, Y \a\\t g:ia",strtotime($late_due_date))."</b></p>");
	echo("<p> Number of Files Required:<b> ".$num_files."</b></p>");
	if ($is_open)
	{
		echo "<p> Assignment is <strong>open";
		if (isset($closed) && $closed): 
			echo "</strong> (but closed for submission).<br><br>";
			$results = $db->arrayQuery("select grade from Grade where user_id='$user_id' and assignment_id='$assignment_id'");
			if (!empty($results))
			{
				$grade = $results[0]["grade"];
				echo "Your grade for this assignment is <strong>$grade/70</strong>.";
			}
			else
			{
				echo "You have not yet received a grade for this assignment.";
			}

		else: "</strong>.</p>";
		endif;
	}
	else
	{
		echo "<p> Assignment is <strong>closed</strong>.</p>";	
	}

	if($_SESSION["usertype"] == "teacher" || $_SESSION["usertype"] == "admin")
	{
		?>
		<form action="grade_assig.php?class_id=<?= $class_id ?>&amp;assignment_id=<?= $assignment_id ?>" class='single-button' method='post'>
			<input type="submit" value="Grade Assignment">
			<? add_token(); ?>
		</form>
		&nbsp;
		<form action="edit_assig.php?class_id=<?= $class_id ?>&amp;assignment_id=<?= $assignment_id ?>" class='single-button' method='post' onsubmit='return checkEdit()'>
			<input type="submit" name='selection'value="Edit Assignment" id="edit"/>
			<? add_token(); ?>
		</form>
		&nbsp;
		<form action="edit_assig.php?class_id=<?= $class_id ?>&amp;assignment_id=<?= $assignment_id ?>" class='single-button' method='post' onsubmit='return checkDelete()'>
			<input type="submit" name='selection'value="Delete Assignment" id="delete"/>
			<? add_token(); ?>
		</form>
		<script>
			function checkEdit(){
				return confirm("Are you sure you want to edit this assignment?");
			}

			function checkDelete(){
				return confirm("Are you sure you want to delete this assignment?");
			}	

		</script>

		<?
	}

	elseif($_SESSION["usertype"] == "student")
	{
		if ((isset($closed) && !$closed) || !isset($closed))
		{
			$results = $db->arrayQuery("select * from Assignment where assignment_id = '$assignment_id' and class_id = '$class_id'");
			$assignment = $results[0];
			print_form($assignment, $class_id, $assignment_id); //after EVERYTHING has been checked, print out the homework submissionform and the relevant JavaScript
		}
	}

get_footer(); 

function print_form($assignment, $class_id, $assignment_id)
{
	$num_files = (isset($assignment["num_files_required"]) && $assignment["num_files_required"] > 0) ? $assignment["num_files_required"] : 3;
	$title = $assignment["title"];
	global $user_id, $username;

	if (false) // check for Uploadify stuff
	{

	}
	else
	{
		?>
		<br><br>Select files to upload:<br>
		<form id='submission-form' enctype='multipart/form-data' action='process_submit.php' method='post'>
			<? for ($count=0; $count < $num_files; $count++): ?>
				<? if ($count == $num_files - 1): ?>
					<input type='file' name='userfile[]' id='last' style="display: inline;">&nbsp;<input type='button' value='-' id='less'>&nbsp;<input type='button' value='+' id='more'><br>
				<? else: ?>
					<input type='file' name='userfile[]' style="display: block;">
				<? endif; ?>
			<? endfor; ?>
			<input type='Submit' value='Submit' id='submit' disabled='disabled'>&nbsp;<input type='reset' value='Reset'>
		</form>

		<script>
			$(document).ready(function(){
				$('#submission-form').submit(function(){
					var now = new Date();
					<?
						$due = strtotime($assignment["due_date"])*1000;
						$late = strtotime($assignment["late_due_date"])*1000;
					?>
					var due = new Date(); due.setTime(<?= $due ?>);
					var late = new Date(); late.setTime(<?= $late ?>);

					if (now.getTime() > late.getTime())
					{
						//alert("Now: " + now.getTime() + " Late: " + late.getTime());
						alert("Sorry, assignment submission has closed.");

						submit_log_entry("assignment_id=<?= $assignment_id ?>&class_id=<?= $class_id ?>&user_id=<?= $user_id ?>&username=<?= $username ?>&submission_time=" + now.getTime() + "&successful=0&commentsubmission_time=File submission attempt logged - FAILED, ASSIGNMENT CLOSED AFTER PAGE LOAD.");

						return false;
					}
					else if (now.getTime() > due.getTime()) //assignment is late
					{
						$('#submission-form').append("<? add_token(); ?>");
						$('#submission-form').append("<input type='hidden' name='course_id' value='<?= $class_id ?>'>");
						$('#submission-form').append("<input type='hidden' name='assignment_id' value='<?= $assignment_id ?>'>");
						$('#submission-form').append("<input type='hidden' name='late' value='true'>");

						submit_log_entry("assignment_id=<?= $assignment_id ?>&class_id=<?= $class_id ?>&user_id=<?= $user_id ?>&username=<?= $username ?>&submission_time=" + now.getTime() + "&successful=1&comment=File submission attempt logged - LATE.");

						return true;
					}
					else //assignment is on time
					{
						$('#submission-form').append("<? add_token(); ?>");
						$('#submission-form').append("<input type='hidden' name='course_id' value='<?= $class_id ?>'>");
						$('#submission-form').append("<input type='hidden' name='assignment_id' value='<?= $assignment_id ?>'>");
						$('#submission-form').append("<input type='hidden' name='late' value='false'>");

						submit_log_entry("assignment_id=<?= $assignment_id ?>&class_id=<?= $class_id ?>&user_id=<?= $user_id ?>&username=<?= $username ?>&submission_time=" + now.getTime() + "&successful=1&comment=File submission attempt logged - ON TIME.");

						return true;
					}
					return false;
				});
				
				$('#submit').attr('disabled',false);
				
				$('#less').bind('click',function(){
					var prev = $('#last').prev();
					var last = $('#last');
					if ($(prev).is("input"))
					{
						$(prev).css("display","inline");
						$(prev).attr("id","last");
						$(last).remove();
					}
				});

				$('#more').bind('click',function(){
					var last = $('#last');
					$(last).attr("id","");
					$(last).css("display","block");
					$(last).after("<input type='file' name='userfile[]' id='last' style='display: inline;'>");
				});
			});

			function submit_log_entry(data)
			{
				post("update_log.php",data,function(){
					//alert(arguments[0]);
				});
			}
		</script>
		<?
	}
}



?>