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

	if($_SESSION["usertype"] != "teacher" && $_SESSION["usertype"] != "admin")
	{
		error_message("User does not have access to this feature...");
		get_footer();
		die;
	}
	else{
		$results = $db->arrayQuery("select user_id, username, email from User where usertype = 'teacher'");
	}
	
	
?>
	<h1>Create Class</h1>
	<form id="create_class" method="post" action="process_create_class.php" onsubmit="return submit_create_class()">
	<table>
	<tr>
		<td>Class Name:</td><td><input type="text" name="class_name" id="class_name" /></td>
	</tr>
	<tr>
		<td>Instructor Email:</td>
		<td>
			<select name="instructor_email" id="instructor_email">
				<option value=""></option>
				<? 
				for($i = 0; $i < count($results); $i++){
					echo "<option value='". $results[$i]['email']."'>". $results[$i]['email']. "</option>";
				}
				?>
			</select> 
		</td>
	</tr>
	<tr>
		<td>Room:</td><td><input type="text" name="room" id="room" /></td>
	</tr>
	<tr>
		<td>Description:</td><td><input type="text" name="description" id="description" /></td>
	</tr>
	<tr>
		<td><input type="submit" value="Submit"/>&nbsp;
		<input type="reset" value="Reset"/></td>
		<td><? add_token(); ?></td>
	</tr>
	</table>
	</form>
	
	<script type="text/javascript">

	function submit_create_class(){
		
		if($('#class_name').val() == ""){
			alert("Class name cannot be empty");
			return false;
		}
		if($('#instructor_email').val() == "" || $('#instructor_email').val() == null){
			alert("Instructor email cannot be empty");
			return false;
		}
		if($('#room').val() == ""){
			var c = confirm("Leave the Room empty?");
			if(!c) return false;
		}
		if($('#description').val() == ""){
			var c = confirm("Leave the Description empty?");
			if(!c) return false;
		}
		return true;
	}

</script>

	
<? get_footer(); ?>