<?
	require("glue.php");
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
	$date_assigned = mysql_real_escape_string(trim($_POST['date_assigned']));
	$description = mysql_real_escape_string(trim($_POST['description']));
	$due_date = mysql_real_escape_string(trim($_POST['due_date']));
	$late_due_date = mysql_real_escape_string(trim($_POST['late_due_date']));
	$num_files_required = mysql_real_escape_string(trim($_POST['num_files_required']));

	echo "this is process create<br/>";
	if(empty($assignment_id) || empty($class_id) || empty($title) || empty($date_assigned) ||
		empty($due_date) || empty($late_due_date)){
		//TODO
		echo "Error: some fields cannot be left empty<br/>";
		?> <button id="back" name="back" value="BACK" onClick="goBack()">BACK</button> <?
	}
	else{
		//TODO
		echo "insert data into database<br/>";
	}
	
	
	//return_to(); //don't forget to specify a page
?>