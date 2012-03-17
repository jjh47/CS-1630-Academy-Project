<?
	require("glue.php");
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

?>
<form id="create_assig" method="post" action="process_create.php">
<table>
<tr>
<td>
	Assignment ID: <br/>
	Class ID: <br/>
	Title: <br/>
	Date Assigned: <br/>
	Description: <br/>
	Due Date: <br/>
	Late Due Date: <br/>
	Number of Files Required: <br/>
</td>
<td>
	<input type="text" name="assignment_id" id="assignment_id" /><br/>
	<input type="text" name="class_id" id="class_id" /><br/>
	<input type="text" name="title" id="title" /><br/>
	<input type="text" name="date_assigned" id="date_assigned" /><br/>
	<input type="text" name="description" id="description" /><br/>
	<input type="text" name="due_date" id="due_date" /><br/>
	<input type="text" name="late_due_date" id="late_due_date" /><br/>
	<input type="text" name="num_files_required" id="num_files_required" /><br/>
</td>
</tr>
<tr>
<td>
<input type="submit" value="Submit" />
</td>
</tr>
</table>
</form>

<? add_token(); ?>

<? get_footer(); ?>