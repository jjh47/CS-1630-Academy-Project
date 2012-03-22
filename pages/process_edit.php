<?
	require("../../../glue.php");
	init("page");

	if($_SESSION["usertype"] != "teacher" && $_SESSION["usertype"] != "admin"){
		return_to(HOME_DIR);
	}
	if(isset($_GET['class_id']) && isset($_GET['assignment_id'])){
		$class_id = $_GET['class_id'];
		$assignment_id = $_GET['assignment_id'];
	}
	else{
		return_to(HOME_DIR);
	}


	$class_id = $_POST[0];
	$assignment_id = $_POST[1];
	$title = $_POST[2];
	$date_assigned = $_POST[3];
	$description = $_POST[4];
	$due_date = $_POST[5];
	$late_due_date = $_POST[6];
	$is_open = $_POST[7];
	$num_files = $_POST[8];

	$results = $db->query("UPDATE assignment set title='".$title.
					"', date_assigned ='".$date_assigned.
					"', description = '".$description.
					"', due_date = '".$due_date.
					"', late_due_date = '".$late_due_date.
					"', $is_open = '".$is_open.
					"', $num_files = '".$num_files.
					"' where class_id ='".$class_id."' and assignment_id = '".$assignment_id."'");




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


	return_to(); //don't forget to specify a page
?>