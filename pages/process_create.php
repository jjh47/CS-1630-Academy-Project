<?
	require("../glue.php");
	init("form_process");
	get_header();
	
/* PLEASE CAREFULLY READ THESE COMMENTS!
 * 
 * On form_process pages, you will need to work with $_POST variables.  PLEASE SPECIFY WHAT THESE ARE NAMED AND WHAT YOU REQUIRE so we can make sure they are delivered by the form.  Do not worry about the token, that is taken care of automatically
 * 
 * use $_SESSION["username"] and $_SESSION["usertype"] to segregate the components of the page (i.e. if ($username != "admin") etc.)
 * use $db to make database calls
 *
 * CAREFULLY use the defines.php file (includes/definies.php) to define any important information like file paths - specifically anything that may chance from one person's machine to another or on the production server.  This makes sure we can just change things here and they won't break elsewhere.  Please name your defines carefully.
 *
 * Make sure to capture any errors or failure so the user doesn't get stuck on a blank page.
 * 
 */

	
	if($_SESSION["usertype"] != "teacher" && $_SESSION["usertype"] != "admin"){
		//return_to(HOME_DIR);
	}
	?>
	<script>
	function goBack(){
		window.history.back();
	}
	</script>
	<?
	$assignment_id = mysql_real_escape_string(trim($_POST['assignment_id']));
	$class_id = mysql_real_escape_string(trim($_POST['class_id']));
	$title = mysql_real_escape_string(trim($_POST['title']));
	$assigned_year = mysql_real_escape_string(trim($_POST['assigned_year']));
	$assigned_month = mysql_real_escape_string(trim($_POST['assigned_month']));
	$assigned_day = mysql_real_escape_string(trim($_POST['assigned_day']));
	$assigned_hour = mysql_real_escape_string(trim($_POST['assigned_hour']));
	$assigned_minute = mysql_real_escape_string(trim($_POST['assigned_minute']));
	$description = mysql_real_escape_string(trim($_POST['description']));
	$due_year = mysql_real_escape_string(trim($_POST['due_year']));
	$due_month = mysql_real_escape_string(trim($_POST['due_month']));
	$due_day = mysql_real_escape_string(trim($_POST['due_day']));
	$due_hour = mysql_real_escape_string(trim($_POST['due_hour']));
	$due_minute = mysql_real_escape_string(trim($_POST['due_minute']));
	$late_year = mysql_real_escape_string(trim($_POST['late_year']));
	$late_month = mysql_real_escape_string(trim($_POST['late_month']));
	$late_day = mysql_real_escape_string(trim($_POST['late_day']));
	$late_hour = mysql_real_escape_string(trim($_POST['late_hour']));
	$late_minute = mysql_real_escape_string(trim($_POST['late_minute']));
	$is_open = mysql_real_escape_string(trim($_POST['is_open']));
	$num_files_required = mysql_real_escape_string(trim($_POST['num_files_required']));

	echo "Assignment ID = " . $assignment_id . "<br/>";
	echo "Class ID = " . $class_id . "<br/>";
	echo "Title = " . $title . "<br/>";
	echo "Date Assigned = ";
	echo "$assigned_year-$assigned_month-$assigned_day $assigned_hour:$assigned_minute<br/>";
	echo "Description = " . $description . "<br/>";
	echo "Due Date = ";
	echo "$due_year-$due_month-$due_day $due_hour:$due_minute<br/>";
	echo "Late Due Date = ";
	echo "$late_year-$late_month-$late_day $late_hour:$late_minute<br/>";
	echo "Is Open = " . $is_open . "<br/>";
	echo "Number of Files Required = " . $num_files_required . "<br/>";
	echo "<br/>";
	
	$error = false;
	if($assignment_id == ""){
		echo "Error: Assignment ID cannot be left empty<br/>";
		$error = true;
	}
	if($class_id == ""){
		$error = true;
		echo "Error: Class ID cannot be left empty<br/>";
	}
	if($title == ""){
		$error = true;
		echo "Error: Title cannot be left empty<br/>";
	}
	if($error){
		?> <button id="back" name="back" value="BACK" onClick="goBack()">BACK</button> <?
	}
	else{
		
		$date_assigned = $assigned_year.'-'.$assigned_month.'-'.$assigned_day.' '.$assigned_hour.':'.$assigned_minute.':00';
		$due_date = $due_year.'-'.$due_month.'-'.$due_day.' '.$due_hour.':'.$due_minute.':00';
		$late_due_date = $late_year.'-'.$late_month.'-'.$late_day.' '.$late_hour.':'.$late_minute.':00';
		$query = "insert into Assignment values ('$assignment_id', '$class_id', '$title', '$date_assigned', '$description',
			'$due_date', '$late_due_date', $is_open, $num_files_required)";
		$result = $db->queryExec($query, $error);
		if($result){
			echo "Assignment created successfully<br/>";
		}
		else{
			echo "Assignment created failed<br/>";
			echo "Error: $error<br/>";
		}
		
		//test only
		$query = $db->query("SELECT * FROM Assignment WHERE assignment_id = '$assignment_id' AND class_id = '$class_id'");
		
		$result = $query->fetchAll(SQLITE_ASSOC);
		foreach ($result as $entry) {
			echo "<br/>";
			var_dump($entry);
		}
	}
	
	
	//return_to(); //don't forget to specify a page
?>