<?
	require("../glue.php");
	init("page");
	//enqueue_script($filename)
	get_header();

<<<<<<< HEAD

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
	


	<label>Add User</label>
	<br>
	<br>
	<form name="adduserform" method="POST" action="process_add_user.php">
		Username: <input type="text" name="username"/><br>
		Email: <input type="text" name="email"/><br>
		Password: <input type="text" name="password"/><br>
		<input type="radio" name="usertype" value="student" checked="true"/>Student<br>
		<input type="radio" name="usertype" value="teacher"/>Teacher<br>
		<input type="submit" Value="Add User" name="adduser"/>

	</form>
	<br><br>


	<label>Add User via CSV</label><br><br>
	<form enctype="multipart/form-data" action="process_add_user.php" method="POST" name="csvform">
		<input type="hidden" name="MAX_FILE_SIZE" value="300000000"/>
		Choose a file to upload: <input name="uploadedfile" type="file" /><br />
		<input type="submit" value="Upload File" name="uploadcsv" />
	</form>


<?




?>
=======
	if ($_SESSION["usertype"] != "admin")
	{
		error_message("Invalid access.");
		get_footer();
		die;
	}

?>
	
	<h1>Add User</h1>
	<div id='results-message' class='message' style='display: none;'></div>
	<hr>
	<h2>Add Single User</h2>
	<form id='single' name="adduserform" method="POST" action="process_add_user.php">
		<table>
			<tr>
				<td style='width: 150px;'>Username:</td><td> <input type="text" id='username' name="username"/></td>
			</tr>
			<tr>
				<td>Email:</td><td><input type="email" id='email' name="email"/></td>
			</tr>
			<tr>
				<td>Password:</td><td><input type="password" id='password' name="password"/></td>
			</tr>
			<tr>
				<td>User Type:</td>
				<td>
					<input type="radio" name="usertype" value="student" checked="true"/>Student<br>
					<input type="radio" name="usertype" value="teacher"/>Teacher<br>
					<input type="radio" name="usertype" value="admin"/>Admin<br>
					<? add_token(); ?>
				</td>
			</tr>
		</table>	
			<input type="submit" Value="Add User" name="adduser"/>&nbsp;<input type='reset' value='Reset'>
	</form>
	<br>
	<hr>

	<h2>Add Multiple Users</h2>
	<form enctype="multipart/form-data" id='multi' action="process_add_user.php" method="POST" name="csvform">
		<input type="hidden" name="MAX_FILE_SIZE" value="300000000"/>
		Choose a file to upload: <input id='file' name="uploadedfile" type="file" /><br />
		<input type="submit" value="Upload File" name="uploadcsv" />
		<? add_token(); ?>
	</form>
	<em><div style='font-size: 12px; margin-top: 15px;'>File must be formated as (username, email, user type, password).
		If you <br>are exporting data from Excel, please make sure to remove the column headers.</div></em>
	<br>

<script>
	$(document).ready(function(){
		$('#single').submit(function(){
			if ($('#username').val() == "" || $('#email').val() == "" || $('#password').val() == ""){
				alert("All fields required.");
				return false;
			}
			else{
				return true;
			}
		});
		$('#multi').submit(function(){
			if ($('#file').val() == ""){
				alert("Please specify a file to upload.");
				return false;
			}
			else{
				var filename = $('#file').val();
				var ext = filename.slice(-4);
				if (ext != ".csv"){
					alert("File must be a .csv file");
					return false;
				}
				else{
					return true;
				}
			}
		});
	});
	<?
		if (isset($_SESSION["aur"]))
		{
			if ($_SESSION["aur"]["success"])
			{
				?>
					$('#results-message').addClass("info");
				<?
			}
			else
			{
				?>
					$('#results-message').addClass("warning");
				<?	
			}
				?>	
					$('#results-message').html("<?= $_SESSION["aur"]["message"] ?>");
					$('#results-message').show("slow");
					setTimeout(function(){
						$('#results-message').hide('slow');
						$('#results-message').css("display","none");
						$('#results-message').html("");
						$('#results-message').attr("class","message");
					},2500);
				<?
			
			unset($_SESSION["aur"]);
		}
	?>
</script>
>>>>>>> afbcc726e6b69e129803f2d4f723b6fee3b742e4

<? get_footer(); ?>