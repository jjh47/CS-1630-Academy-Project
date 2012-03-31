<?
	require("../glue.php");
	if (isset($_POST["selection"]) && $_POST["selection"] == "Delete Assignment")
	{
		init("form_process");
		if (!isset($_GET["assignment_id"]) || !isset($_GET["class_id"]))
		{
			return_to();
		}
		$assignment_id = sqlite_escape_string($_GET["assignment_id"]);
		$class_id = sqlite_escape_string($_GET["class_id"]);
		$result = $db->queryExec("delete from assignment where assignment_id='$assignment_id';");

		$success = $db->changes();

		if ($success)
		{
			$_SESSION["delete_success"] = "Assignment successfully deleted.";
		}
		else
		{
			$_SESSION["delete_failure"] = "Assignment was not deleted.";
		}
		return_to(HOME_DIR."pages/view_class.php?class_id=$class_id");
		
	}
	init("page");
	get_header();

	$user_id = $_SESSION["user_id"];

	if($_SESSION["usertype"] != "teacher" && $_SESSION["usertype"] != "admin")
	{
		error_message("Invalid permissions");
		get_footer();
		die;
	}
	if(isset($_GET['assignment_id']))
	{
		$assignment_id = sqlite_escape_string($_GET['assignment_id']);
	}
	else
	{
		error_message("No assignment specified...");
		get_footer();
		die;
	}

	$results = $db->arrayQuery("select * from assignment where assignment_id = '$assignment_id'");

	if (empty($results))
	{
		error_message("Invalid assignment...");
		get_footer();
		die;
	}

	$result = $results[0];

	$title = $result["title"];
	$date_assigned = $result["date_assigned"];
	$description = $result["description"];
	$due_date = $result["due_date"];
	$late_due_date = $result["late_due_date"];
	$is_open = $result["is_open"];
	$num_files = $result["num_files_required"];
	$class_id = $results[0]["class_id"];

	$results = $db->arrayQuery("select * from Enrollment where class_id='$class_id' and user_id='$user_id';");

	if (empty($results))
	{
		error_message($_SESSION["username"]." does not have permission to edit this assignment.");
		get_footer();
		die;
	}

//This form probably needs looked at.  Not sure if it works.
	?>
	<h1>Edit <?= $title ?></h1>
	<?= date("m/d/Y g:i a",strtotime($due_date)) ?>
	<form method='post' action='process_edit.php'>
		<table>
			<tr><td>Title:</td>
			<td><input type='text' name='assig_title' id='assig_title' value="<?= $title ?>"></td></tr>
			<tr><td>Date Assigned:</td>
			<td><?= date("l, F jS \a\\t g:ia",strtotime($date_assigned)) ?><input type='hidden' id='date_assigned' name='date_assigned' value="<?= $date_assigned ?>"></td></tr>
			<tr><td>Description:</td>
			<td><textarea id="description" name="description" rows="3" cols="40"><?= $description ?></textarea></td></tr>
			<tr><td>Due Date:</td>
			<td><input type='text' class='datepicker' name='due-date' id='due-date' value="<?= date("m/d/Y g:i a",strtotime($due_date)) ?>"></td></tr>
			<tr><td>Late Due Date:</td>
			<td><input type='text' class='datepicker' name='late-due-date' id='late-due-date' value="<?= date("m/d/Y g:i a",strtotime($late_due_date)) ?>"></td></tr>
			<tr><td>Visibility:</td>
			<td>
			<input type="radio" name="is_open" id="open" value="1" <?= ($is_open == 1) ? "checked='checked'" : ""  ?>/>Open&nbsp;
			<input type="radio" name="is_open" id="close" value="0" <?= ($is_open == 0) ? "checked='checked'" : ""  ?>/>Close</td></tr>
			<tr><td>Number Files Required:</td>
			<td>
			<select id="num_files" name="num_files">
		<?
		for($i = 0; $i <= 10; $i++){
			if($num_files == $i) echo "<option selected=\"selected\" value=$i>$i</option>";
			else echo "<option value=$i>$i</option>";
		}?>
	</select></td></tr>
			<tr><td><input type='submit' value="Submit">&nbsp;<input type='reset' value='Reset'></td>
			<td><? add_token() ?><input type='hidden' id='assignment_id' name='assignment_id' value=<?= $assignment_id ?>><input type='hidden' id='class_id' name='class_id' value=<?= $class_id ?>></td></tr>
		</table>
	</form>

<script type="text/javascript">

	$(document).ready(function(){
		$(".datepicker").datetimepicker({
			ampm: true,
		});
	});

	function submit_create_assig(){
		
		if($('#assig_title').val() == ""){
			alert("Title cannot be empty");
			return false;
		}
		if($('#description').val() == ""){
			var c = confirm("Leave the Description empty?");
		}
		if($('#num_files').val() == "0"){
			var c2 =  confirm("Leave Number of Files Required as '0'?");
		}
		if(c && c2) return true;
	}
</script>	

<? get_footer(); ?>