<?
	require("../../../glue.php");
	init("page");
	//enqueue_script($filename)
	get_header();


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

	$results = $db->arrayQuery("select * from assignment where class_id = '$class_id' and assignment_id = '$assignment_id'");

	$title = $results[2];
	$date_assigned = $results[3];
	$description = $results[4];
	$due_date = $results[5];
	$late_due_date = $results[6];
	$is_open = $results[7];
	$num_files = $results[8];


//This form probably needs looked at.  Not sure if it works.
	echo("<Form method='post' action='process_edit.php'>");
	echo("Title: <input type='text' name='title' text=".$title."/><br />");
	echo("Date Assigned: <input type='text' name='lastname' text=".$date_assigned."/>");
	echo("description: <input type='text' name='description' text=".$description."/><br />");
	echo("due_date: <input type='text' name='due_date' text=".$due_date."/><br />");
	echo("late_due_date: <input type='text' name= 'late_due_date' text=".$late_due_date."/><br />");
	echo("open/close: <input type='checkbox' name='is_open'/><br />");
	echo("Title: <input type='text' name='num_files' text=".$num_files."/><br />");
	echo("</Form>");
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


print("this is a string");
?>

<? get_footer(); ?>