<?
	require("../../../glue.php");
	init("page");
	//enqueue_script($filename)
	get_header();

	if(isset($_GET['class_id']) && isset($_GET['assignment_id'])){
		$class_id = $_GET['class_id'];
		$assignment_id = $_GET['assignment_id'];
	}
	else{
		return_to(HOME_DIR);
	}

	$results = $db->arrayQuery("select * from assignment where class_id = '$class_id' and assignment_id = '$assignment_id'");

	$title = $results[2];
	$date_assigned = $results[3];
	$description = $results[4];
	$due_date = $results[5];
	$late_due_date = $results[6];
	$is_open = $results[7];
	$num_files = $results[8];

	echo("<p> <b> ".$title."</b></p>");
	echo("<p> <b> ".$date_assigned."</b></p>");
	echo("<p> <b> ".$description."</b></p>");
	echo("<p> <b> ".$due_date."</b></p>");
	echo("<p> <b> ".$late_due_date."</b></p>");
	echo("<p> <b> ".$is_open."</b></p>");
	echo("<p> <b> ".$num_files."</b></p>");


	if($_SESSION["usertype"] != "teacher" && $_SESSION["usertype"] != "admin"){
		
//**this submit button may be wrong.  Not sure exactly how you wan them.	
	?>



	<Form action="edit_assig.php">
		<input type="submit" value="Edit Assignment" />
	</Form>
	<?php

	}

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

?>

<? get_footer(); ?>