<?
	require("../glue.php");
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


	if($_SESSION["usertype"] != "teacher" && $_SESSION["usertype"] != "admin"){
		//return_to(HOME_DIR);
	}
	if(isset($_GET['class_id']) && isset($_GET['assignment_id'])){
		$class_id = $_GET['class_id'];
		$assignment_id = $_GET['assignment_id'];
	}
	else{
		return_to(HOME_DIR);
	}
	
?>
<form id="create_assig" method="post" action="process_create.php" onsubmit="return submit_create_assig()">
<table>
<tr>
<td>
	Assignment ID: <br/>
	Class ID: <br/>
	Title: <br/>
	Date Assigned: <br/>
	Description: <br/><br/><br/>
	Due Date: <br/>
	Late Due Date: <br/>
	Visibility: <br/>
	Number of Files Required: <br/>
</td>
<td>
	<input type="hidden" name="assignment_id" id="assignment_id" value="<?echo $assignment_id;?>"/>
	<?echo $assignment_id;?><br/>
	<input type="hidden" name="class_id" id="class_id" value="<?echo $class_id;?>"/>
	<?echo $class_id;?><br/>
	<input type="text" name="title" id="title" /><br/>
	<select id="assigned_year" name="assigned_year" onchange="checkDay('assigned')">
		<option value="Year">Year</option>
		<?
		$format = '<option value=%1$04d>%1$04d</option>';
		for($i = 2010; $i <= 2014; $i++){
			printf($format, $i);
		}?>
	</select> 
	<select id="assigned_month" name="assigned_month" onchange="checkDay('assigned')">
		<option value="Month">Month</option>
		<?
		$format = '<option value=%1$02d>%1$02d</option>';
		for($i = 1; $i <= 12; $i++){
			printf($format, $i);
		}?>
	</select>
	<select id="assigned_day" name="assigned_day">
		<option value="Day">Day</option>
		<?
		$format = '<option value=%1$02d>%1$02d</option>';
		for($i = 1; $i <= 31; $i++){
			printf($format, $i);
		}?>
	</select>
	<select id="assigned_hour" name="assigned_hour">
		<option value="Hour">Hour</option>
		<?
		$format = '<option value=%1$02d>%1$02d</option>';
		for($i = 0; $i <= 23; $i++){
			printf($format, $i);
		}?>
	</select> 
	<select id="assigned_minute" name="assigned_minute">
		<option value="Minute">Minute</option>
		<?
		$format = '<option value=%1$02d>%1$02d</option>';
		for($i = 0; $i <= 59; $i++){
			printf($format, $i);
		}?>
	</select> <br/>
	<textarea id="description" name="description" rows="3" cols="40"></textarea><br/>
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
	</select> <br/>
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
	</select> <br/>
	<input type="radio" name="is_open" id="open" value="1" checked="true"/>Open
	<input type="radio" name="is_open" id="close" value="0"/>Close<br/>
	<select id="num_files_required" name="num_files_required">
		<?
		for($i = 0; $i <= 10; $i++){
			echo "<option value=$i>$i</option>";
		}?>
	</select> <br/>
	
</td>
</tr>
<tr>
<td>
<input type="submit" value="Submit"/>
<input type="button" value="Reset" onclick="reset_create_assig()" />
</td>
</tr>
</table>
<? set_tokens(); add_token(); ?>
</form>

<script type="text/javascript">
function submit_create_assig(){
	if($('#assignment_id').val() == ""){
		alert("Assignment ID cannot be empty");
		return false;
	}
	if($('#class_id').val() == "") {
		alert("Class ID cannot be empty");
		return false;
	}
	if($('#title').val() == ""){
		alert("Title cannot be empty");
		return false;
	}
	if($('#assigned_year').val() == "Year"){
		alert("Please select the 'Year' in Date Assigned");
		return false;
	}
	if($('#assigned_month').val() == "Month"){
		alert("Please select the 'Month' in Date Assigned");
		return false;
	}
	if($('#assigned_day').val() == "Day"){
		alert("Please select the 'Day' in Date Assigned");
		return false;
	}
	if($('#assigned_hour').val() == "Hour"){
		alert("Please select the 'Hour' in Date Assigned");
		return false;
	}
	if($('#assigned_minute').val() == "Minute"){
		alert("Please select the 'Minute' in Date Assigned");
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
function reset_create_assig()
{
	$('#title').val("");
	document.getElementById('assigned_year').options[0].selected = true;
	document.getElementById('assigned_month').options[0].selected = true;
	document.getElementById('assigned_day').options[0].selected = true;
	document.getElementById('assigned_hour').options[0].selected = true;
	document.getElementById('assigned_minute').options[0].selected = true;
	$('#description').val("");
	document.getElementById('due_year').options[0].selected = true;
	document.getElementById('due_month').options[0].selected = true;
	document.getElementById('due_day').options[0].selected = true;
	document.getElementById('due_hour').options[0].selected = true;
	document.getElementById('due_minute').options[0].selected = true;
	document.getElementById('late_year').options[0].selected = true;
	document.getElementById('late_month').options[0].selected = true;
	document.getElementById('late_day').options[0].selected = true;
	document.getElementById('late_hour').options[0].selected = true;
	document.getElementById('late_minute').options[0].selected = true;
	document.getElementById('open').checked = true;
	$('#num_files_required').val("0");
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