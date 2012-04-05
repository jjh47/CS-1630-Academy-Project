<?
	require("../glue.php");
	init("page");
	//enqueue_script($filename)
	get_header();

	$usertype = $_SESSION["usertype"];

	if($_SESSION["usertype"] != "admin")
	{
		error_message("User does not have access to this feature...");
		get_footer();
		die;
	}
	else{
		$results = $db->arrayQuery("select user_id, username, email from User where usertype = 'teacher'");
	}

	if ($usertype == "admin")
	{
		if (isset($_SESSION["creation-message"]))
		{
			echo "<div id='class-creation-message' class='info message'>".$_SESSION["creation-message"]."<br></div>";
			unset($_SESSION["creation-message"]);
			?>
				<script>
					setTimeout(function(){
						$('#class-creation-message').hide("slow");
					}, 2000);
				</script>
			<?
		}
		elseif (isset($_SESSION["creation-message-error"]))
		{
			echo "<div id='class-creation-message' class='warning message'>".$_SESSION["creation-message-error"]."<br></div>";
			unset($_SESSION["creation-message-error"]);
			?>
				<script>
					setTimeout(function(){
						$('#class-creation-message').hide("slow");
					}, 2000);
				</script>
			<?	
		}
	}	
	
?>
	<h1>Create Class</h1>
	<form id="create_class" method="post" action="process_create_class.php" onsubmit="return submit_create_class()">
	<table>
	<tr>
		<td>Class Name:</td><td><input type="text" name="class_name" id="class_name" style='width: 325px;'/></td>
	</tr>
	<tr>
		<td>Instructor Email:</td>
		<td>
			<select name="instructor_email" id="instructor_email" style='width: 330px;'>
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
		<td>Room:</td><td><input type="text" name="room" id="room" style='width: 325px;'/></td>
	</tr>
	<tr>
		<td>Description:</td><td><textarea name='description' id='description' rows=10 cols=40 style="resize: vertical;"></textarea></td>
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