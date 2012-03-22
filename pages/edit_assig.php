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
	$due_year = substr($due_date, 0, 4);
	$due_month = substr($due_date, 5, 2);
	$due_day = substr($due_date, 8, 2);
	$due_hour = substr($due_date, 11, 2);
	$due_minute = substr($due_date, 14, 2);
	$late_year = substr($late_due_date, 0, 4);
	$late_month = substr($late_due_date, 5, 2);
	$late_day = substr($late_due_date, 8, 2);
	$late_hour = substr($late_due_date, 11, 2);
	$late_minute = substr($late_due_date, 14, 2);
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
	<form method='post' action='process_edit.php'>
		<table>
			<tr><td>Title:</td>
			<td><input type='text' name='assig_title' id='assig_title' value="<?= $title ?>"></td></tr>
			<tr><td>Date Assigned:</td>
			<td><?= date("l, F jS \a\\t g:ia",strtotime($date_assigned)) ?><input type='hidden' id='date_assigned' name='date_assigned' value="<?= $date_assigned ?>"></td></tr>
			<tr><td>Description:</td>
			<td><textarea id="description" name="description" rows="3" cols="40"><?= $description ?></textarea></td></tr>
			<tr><td>Due Date:</td>
			<td>
			<select id="due_year" name="due_year" onchange="checkDay('due')">
				<option value="Year">Year</option>
				<?
				$format = '<option value=%1$04d>%1$04d</option>';
				$format2 = '<option selected=\"selected\" value=%1$04d>%1$04d</option>';
				for($i = 2010; $i <= 2014; $i++){
					if($due_year == $i) printf($format2, $i);
					else printf($format, $i);
				}?>
			</select> 
			<select id="due_month" name="due_month" onchange="checkDay('due')">
				<option value="Month">Month</option>
				<?
				$format = '<option value=%1$02d>%1$02d</option>';
				$format2 = '<option selected=\"selected\" value=%1$02d>%1$02d</option>';
				for($i = 1; $i <= 12; $i++){
					if($due_month == $i) printf($format2, $i);
					else printf($format, $i);
				}?>
			</select>
			<select id="due_day" name="due_day">
				<option value="Day">Day</option>
				<?
				$format = '<option value=%1$02d>%1$02d</option>';
				for($i = 1; $i <= 31; $i++){
					if($due_day == $i) printf($format2, $i);
					else printf($format, $i);
				}?>
			</select>
			<select id="due_hour" name="due_hour">
				<option value="Hour">Hour</option>
				<?
				$format = '<option value=%1$02d>%1$02d</option>';
				for($i = 0; $i <= 23; $i++){
					if($due_hour == $i) printf($format2, $i);
					else printf($format, $i);
				}?>
			</select> 
			<select id="due_minute" name="due_minute">
				<option value="Minute">Minute</option>
				<?
				$format = '<option value=%1$02d>%1$02d</option>';
				for($i = 0; $i <= 59; $i++){
					if($due_minute == $i) printf($format2, $i);
					else printf($format, $i);
				}?>
			</select></td></tr>
			<tr><td>Late Due Date:</td>
			<td>
			<select id="late_year" name="late_year" onchange="checkDay('late')">
				<option value="Year">Year</option>
				<?
				$format = '<option value=%1$04d>%1$04d</option>';
				$format2 = '<option selected=\"selected\" value=%1$04d>%1$04d</option>';
				for($i = 2010; $i <= 2014; $i++){
					if($late_year == $i) printf($format2, $i);
					else printf($format, $i);
				}?>
			</select> 
			<select id="late_month" name="late_month" onchange="checkDay('late')">
				<option value="Month">Month</option>
				<?
				$format = '<option value=%1$02d>%1$02d</option>';
				$format2 = '<option selected=\"selected\" value=%1$02d>%1$02d</option>';
				for($i = 1; $i <= 12; $i++){
					if($late_month == $i) printf($format2, $i);
					else printf($format, $i);
				}?>
			</select>
			<select id="late_day" name="late_day">
				<option value="Day">Day</option>
				<?
				$format = '<option value=%1$02d>%1$02d</option>';
				for($i = 1; $i <= 31; $i++){
					if($late_day == $i) printf($format2, $i);
					else printf($format, $i);
				}?>
			</select>
			<select id="late_hour" name="late_hour">
				<option value="Hour">Hour</option>
				<?
				$format = '<option value=%1$02d>%1$02d</option>';
				for($i = 0; $i <= 23; $i++){
					if($late_hour == $i) printf($format2, $i);
					else printf($format, $i);
				}?>
			</select> 
			<select id="late_minute" name="late_minute">
				<option value="Minute">Minute</option>
				<?
				$format = '<option value=%1$02d>%1$02d</option>';
				for($i = 0; $i <= 59; $i++){
					if($late_minute == $i) printf($format2, $i);
					else printf($format, $i);
				}?>
			</select></td></tr>
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

	function submit_create_assig(){
		
		if($('#assig_title').val() == ""){
			alert("Title cannot be empty");
			return false;
		}
		if($('#description').val() == ""){
			var c = confirm("Leave the Description empty?");
		}
		if($('#due_year').val() == "Year"){
			alert("Please select the 'Year' in Due Date");
			return false;
		}
		if($('#due_month').val() == "Month"){
			alert("Please select the 'Month' in Due Date");
			return false;
		}
		if($('#due_day').val() == "Day"){
			alert("Please select the 'Day' in Due Date");
			return false;
		}
		if($('#due_hour').val() == "Hour"){
			alert("Please select the 'Hour' in Due Date");
			return false;
		}
		if($('#due_minute').val() == "Minute"){
			alert("Please select the 'Minute' in Due Date");
			return false;
		}
		if($('#late_year').val() == "Year"){
			alert("Please select the 'Year' in Late Due Date");
			return false;
		}
		if($('#late_month').val() == "Month"){
			alert("Please select the 'Month' in Late Due Date");
			return false;
		}
		if($('#late_day').val() == "Day"){
			alert("Please select the 'Day' in Late Due Date");
			return false;
		}
		if($('#late_hour').val() == "Hour"){
			alert("Please select the 'Day' in Late Due Date");
			return false;
		}
		if($('#late_minute').val() == "Minute"){
			alert("Please select the 'Minute' in Late Due Date");
			return false;
		}
		if($('#num_files').val() == "0"){
			var c2 =  confirm("Leave Number of Files Required as '0'?");
		}
		if(c && c2) return true;
	}

	function checkDay(pre){
		var month = $('#' + pre+ '_month').val();
		if(month == "02"){
			var year = $('#' + pre+ '_year').val();
			if(year%4 == 0){
				if(year%100 == 0){
					if(year%400 == 0) setDay(pre, 29);
					else setDay(pre, 28);
				}
				else setDay(pre, 29);
			}
			else setDay(pre, 28);
		}
		else if(month == "01" || month == "03" || month == "05" || month == "07" 
			|| month == "08" || month == "10" || month == "12"){
			setDay(pre, 31);
		}
		else if(month == "04" || month == "06" || month == "09" || month == "11"){
			setDay(pre, 30);
		}
	}

	function setDay(pre, num){
		var day = document.getElementById(pre + '_day');
		if(day.length != num){
			for (i = 1; i < day.length;) {
				day.remove(i);
			}
			for(i = 1; i <= num; i++){
				var j = "" + i;
				if(j.length == 1) j = "0" + j;
				var newDay = document.createElement('option');
				newDay.text = j;
				newDay.value = j;
				try{
					day.add(newDay, null);
				}catch(ex){
					day.add(newDay);
				}
			}
		}
		day.options[0].selected = true;
	}
</script>	

<? get_footer(); ?>