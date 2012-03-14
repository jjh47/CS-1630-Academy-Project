<?
	require("../glue.php");
	init("page");
	//enqueue_script($filename)
	get_header();
	$username = $_SESSION["username"];
	$user_id = $_SESSION["user_id"];
	$usertype = $_SESSION["usertype"];

	if (!isset($_GET["class_id"]))
	{
		echo "<em>No course selected. Click <a href='view_classes.php'>here</a> to return to course selection page.</em>";
	}
	else
	{
		$class_id = sqlite_escape_string($_GET["class_id"]);
		$results = $db->arrayQuery("select * from Enrollment where class_id = '$class_id' and user_id = '$user_id';");
		if (empty($results)) //if not enrolled
		{
			echo "<em>You are not enrolled in the course you have selected.  Click <a href='view_classes.php'>here</a> to return to course selection page.</em>";
		}
		else //if enrolled
		{
			if (!isset($_GET["assignment_id"]))
			{
				echo "<em>No assignment selected.  Click <a href='view_class.php?class_id=$class_id'>here</a> to return to the assignment selection page.</em>";
			}
			else
			{
				$assignment_id = sqlite_escape_string($_GET["assignment_id"]);
				$results = $db->arrayQuery("select * from Assignment where assignment_id = '$assignment_id' and class_id = '$class_id'");
				if (empty($results))
				{
					echo "<em>Invalid assignment selected.  Click <a href='view_class.php?class_id=$class_id'>here</a> to return to the assignment selection page.</em>";		
				}
				else
				{
					$assignment = $results[0];
					$due = $assignment["due_date"];
					$late = $assignment["late_due_date"];
					$now = time();
					$is_open = $assignment["is_open"];
					
					if (!$is_open || $now > $late)
					{
						echo "<em>This assignment is closed.</em>";
					}
					else
					{
						//$now = 1331595001; //for testing purposes
						if ($now > $due)
						{
							echo "<div class='warning message'>This assignment was due by ".date("F jS, Y \a\\t g:i A",$due).".  Any files submitted will now be counted as late.</div>";
						}
						print_form($assignment, $class_id, $assignment_id); //after EVERYTHING has been checked, print out the homework submissionform and the relevant JavaScript
					}
				}
			}
		}

	}
?>


<? get_footer(); 


function print_form($assignment, $class_id, $assignment_id)
{
	$num_files = (isset($assignment["num_files_required"]) && $assignment["num_files_required"] > 0) ? $assignment["num_files_required"] : 3;
	$title = $assignment["title"];
	global $user_id, $username;

	echo "<h2>Submit Files for $title</h2>";

	if (false) // check for Uploadify stuff
	{

	}
	else
	{
		?>
		<form id='submission-form' enctype='multipart/form-data' action='process_submit.php' method='post'>
			Select files to upload:<br>
			<? for ($count=0; $count < $num_files; $count++): ?>
				<? if ($count == $num_files - 1): ?>
					<input type='file' name='userfile[]' id='last'>&nbsp;<input type='button' value='-' id='less'>&nbsp;<input type='button' value='+' id='more'><br>
				<? else: ?>
					<input type='file' name='userfile[]'><br>
				<? endif; ?>
			<? endfor; ?>
			<input type='Submit' value='Submit' id='submit' disabled='disabled'>&nbsp;<input type='reset' value='Reset'>
		</form>

		<script>
			$(document).ready(function(){
				$('#submission-form').submit(function(){
					var now = new Date();
					var due = new Date(); due.setTime(<?= $assignment["due_date"] * 1000 ?>);
					var late = new Date(); late.setTime(<?= $assignment["late_due_date"] * 1000 ?>);

					if (now.getTime() > late.getTime())
					{
						alert("Now: " + now.getTime() + " Late: " + late.getTime());
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
					var prevprev = $(prev).prev();
					if (prevprev.length != 0)
					{
						$(prev).remove();
						$(prevprev).remove();
					}
				});

				$('#more').bind('click',function(){
					$('#last').before("<input type='file' name='userfile[]'><br>");
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