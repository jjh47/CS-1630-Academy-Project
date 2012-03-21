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
	<tr><td>Due Date:</td><td>
	<select id="due_year" name="due_year" onchange="checkDay('due')">
		<option value="Year">Year</option>
		<?
		$format = '<option value=%1$04d>%1$04d</option>';
		for($i = 2010; $i <= 2014; $i++){
			printf($format, $i);
		}?>
	</select> 
	<select id="due_month" name="due_month" onchange="checkDay('due')">
		<option value="Month">Month</option>
		<?
		$format = '<option value=%1$02d>%1$02d</option>';
		for($i = 1; $i <= 12; $i++){
			printf($format, $i);
		}?>
	</select>
	<select id="due_day" name="due_day">
		<option value="Day">Day</option>
		<?
		$format = '<option value=%1$02d>%1$02d</option>';
		for($i = 1; $i <= 31; $i++){
			printf($format, $i);
		}?>
	</select>
	<select id="due_hour" name="due_hour">
		<option value="Hour">Hour</option>
		<?
		$format = '<option value=%1$02d>%1$02d</option>';
		for($i = 0; $i <= 23; $i++){
			printf($format, $i);
		}?>
	</select> 
	<select id="due_minute" name="due_minute">
		<option value="Minute">Minute</option>
		<?
		$format = '<option value=%1$02d>%1$02d</option>';
		for($i = 0; $i <= 59; $i++){
			printf($format, $i);
		}?>
	</select> </td></tr>
	<tr><td>Late Due Date:</td><td>
	<select id="late_year" name="late_year" onchange="checkDay('late')">
		<option value="Year">Year</option>
		<?
		$format = '<option value=%1$04d>%1$04d</option>';
		for($i = 2010; $i <= 2014; $i++){
			printf($format, $i);
		}?>
	</select> 
	<select id="late_month" name="late_month" onchange="checkDay('late')">
		<option value="Month">Month</option>
		<?
		$format = '<option value=%1$02d>%1$02d</option>';
		for($i = 1; $i <= 12; $i++){
			printf($format, $i);
		}?>
	</select>
	<select id="late_day" name="late_day">
		<option value="Day">Day</option>
		<?
		$format = '<option value=%1$02d>%1$02d</option>';
		for($i = 1; $i <= 31; $i++){
			printf($format, $i);
		}?>
	</select>
	<select id="late_hour" name="late_hour">
		<option value="Hour">Hour</option>
		<?
		$format = '<option value=%1$02d>%1$02d</option>';
		for($i = 0; $i <= 23; $i++){
			printf($format, $i);
		}?>
	</select> 
	<select id="late_minute" name="late_minute">
		<option value="Minute">Minute</option>
		<?
		$format = '<option value=%1$02d>%1$02d</option>';
		for($i = 0; $i <= 59; $i++){
			printf($format, $i);
		}?>
	</select></td></tr>
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
		if($('#num_files_required').val() == "0"){
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