<?
	require("../glue.php");
	init("page");
	//enqueue_script($filename)
	get_header();

	if($_SESSION["usertype"] != "teacher" && $_SESSION["usertype"] != "admin")
	{
		error_message("User does not have access to this feature...");
		get_footer();
		die;
	}
	if(isset($_GET['class_id']))
	{
		$class_id = sqlite_escape_string($_GET['class_id']);
		$results = $db->arrayQuery("select class_name from Class where class_id = '$class_id'");
		if (empty($results))
		{
			error_message("Invalid course selected...");
			get_footer();
			die;
		}
		else
		{
			$coursename = $results[0]["class_name"];
		}
		$needs_class_id = false;
	}
	else
	{
		$user_id = $_SESSION["user_id"];
		$results = $db->arrayQuery("select class_id from Enrollment where user_id = '$user_id'");
		if (empty($results))
		{
			error_message("User cannot edit any courses...");
			get_footer();
			die;
		}
		foreach($results as $result)
		{
			$id = $result["class_id"];
			$results = $db->arrayQuery("select class_name from Class where class_id='$id'");
			$name = $results[0]["class_name"];
			$courses[$id] = $name;
		}

		$needs_class_id = true;
	}
	
?>

<h1>Create Assignment<?= isset($coursename) ? " for $coursename" : "" ?></h1>

<form id="create_assig" method="post" action="process_create.php" onsubmit="return submit_create_assig()">
<table>
	<? if (!$needs_class_id): ?>
		<input type="hidden" name="class_id" id="class_id" value="<?= $class_id ?>"/>
	<? else: ?>
	<tr>
		<td>Course:</td>
		<td> 
			<select name='class_id' id='class_id'>
				<? foreach ($courses as $id => $name): ?>
					<option value="<?= $id ?>"><?= $name ?></option> 
				<? endforeach; ?>
			</select>
		</td>
	</tr>
	<? endif; ?>
	<tr><td>Title: </td><td><input type="text" name="assig_title" id="assig_title" /></td></tr>
	<tr><td>Date Created:</td><td>
	<input type="hidden" name="date_assigned" id="date_assigned" value="<?= date("Y-m-d H:i:s") ?>">
	<?= date("l, F jS \a\\t g:ia") ?></td>
	<tr><td>Description:</td><td>
	<textarea id="description" name="description" rows="3" cols="40"></textarea></td></tr>
	<tr><td>Due Date:</td><td><input type='text' class='datepicker' name='due-date' id='due-date'></td></tr>
	<tr><td>Late Due Date:</td><td><input type='text' class='datepicker' name='late-due-date' id='late-due-date'></td></tr>
	<tr><td>Visibility:</td><td>
	<input type="radio" name="is_open" id="open" value="1" checked="true"/>Open&nbsp;
	<input type="radio" name="is_open" id="close" value="0"/>Close</td></tr>
	<tr><td>Number Files Required:</td><td>
	<select id="num_files_required" name="num_files_required">
		<?
		for($i = 0; $i <= 10; $i++){
			echo "<option value=$i>$i</option>";
		}?>
	</select></td></tr>
	<tr><td><input type="submit" value="Submit"/>&nbsp;
<input type="reset" value="Reset"/></td><td><? add_token(); ?></td></tr></table>
</form>

<script type="text/javascript">

	$(document).ready(function(){
		$(".datepicker").datetimepicker({
			ampm: true
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
		if($('#num_files_required').val() == "0"){
			var c2 =  confirm("Leave Number of Files Required as '0'?");
		}
		if(c && c2) return true;
	}

</script>

<? get_footer(); ?>