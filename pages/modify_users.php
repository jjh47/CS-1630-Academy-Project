<?
	require("../glue.php");
	
	init("page");
	enqueue_script("jquery.dataTables.min.js");
	get_header();
 

 $usertype = $_SESSION["usertype"];
//We start out with checking that the user is an admin and rejecting them if they are not.
if($usertype != "admin")
{
	error_message("User does not have access to this feature...");
	get_footer();
	die;
}
else
{ //Now we pull the data for the table from the database.
	//This should be enough to initially populate the table.
	$results = $db->arrayQuery("select user_id, username, email, usertype from User");
	$classes = $db->arrayQuery("select class_id, class_name from Class");
	//Change needed:
	//May need another request to verify changes, such as having the enrollment table to approve/deny enrollment changes
	
}
if ($usertype == "admin")
{
	if (isset($_SESSION["modify-message"]))
	{
		echo "<div id='class-modify-message' class='info message'>".$_SESSION["modify-message"]."<br></div>";
		unset($_SESSION["modify-message"]);
		?>
			<script>
				setTimeout(function(){
					$('#class-modify-message').hide("slow");
				}, 2000);
			</script>
		<?
	}
	elseif (isset($_SESSION["modify-message-error"]))
	{
		echo "<div id='class-modify-message' class='warning message'>".$_SESSION["modify-message-error"]."<br></div>";
		unset($_SESSION["modify-message-error"]);
		?>
			<script>
				setTimeout(function(){
					$('#class-modify-message').hide("slow");
				}, 2000);
			</script>
		<?	
	}
}	

?>

<!-- Now we have the HTML for displaying the table. After this works, add on DataTables -->
<h1>Modify Users</h1>
<!-- TODO: create page process_modify_users.php (or do it another way, inline?) and function (below) submit_modify_users -->
<!-- onsubmit="return submit_modify_users()" -->

<form id="modify_users" method="post" action="process_modify_users.php" >
	<select name="class_name" id="class_name" style='width: 130px;'>
		<option value="">Classes...</option>
		<? 
		for($i = 0; $i < count($classes); $i++){
			echo "<option value='". $classes[$i]['class_name']."'>". $classes[$i]['class_name']. "</option>";
		}
		?>
	</select>
	<input type="submit" name="modifySubmit" onclick="return clickEnroll()" value="Enroll"/>&nbsp;
	<input type="submit" name="modifySubmit" onclick="return clickDelete()" value="Delete Users"/>&nbsp;
	<input type="submit" name="modifySubmit" onclick="return clickPassword()" value="Change Passwords"/>&nbsp;
	<input type="reset" value="Reset">
	<? add_token(); ?>
	<br />
	<br />
	<table id="users_table">
		<thead>
			<tr>
				<th>Name</th>
				<th>Type</th>
				<th>Password</th>
				<th>Select</th>
			</tr>
		</thead>
		<tbody>
		<!-- It is now time to go to php to populate the table with info from $results -->
		<?php
		//$arrayLength = count($results); //Delete if not used at end
		//for($i = 0; $i <= $arrayLength; $i = $i + 6)
		foreach ($results as $entry)
		{ 
			//admin's can't modify other admins
			if ($entry['usertype'] == "admin" && $entry['user_id'] != $_SESSION['user_id']) continue;
		  //It's easier to echo this out if it is stored this way.
		  $id = $entry['user_id'];
		  echo "<tr>";
		  //First we have the particular user's name and usertype.
		  echo "<td>{$entry['username']}</td><td>{$entry['usertype']}</td>";
		  //Now we get a text input box for inputing a new password. 
		  echo "<td><input type='text' name='password[]' id='password_$id' style='width: 100px;'/></td>";
		  //Now a checkbox for applying the given action to this person.
		  echo "<td><input type='checkbox' name='check[]' id='check_$id' value='$id' onclick='checkClick($id)' /></td>"; 
		  echo "</tr>";     
		} 
		?>
		
		
		
		</tbody>
	</table>
</form>
	

<script type="text/javascript">
	//This bit here starts up DataTables
	$(document).ready(function(){
	  $('#users_table').dataTable({
		"aoColumnDefs": [
		  { "asSorting": [ "asc", "desc" ], "aTargets": [ 0, 1 ] },
		  { "asSorting": [ ], "aTargets": [ 2, 3 ] },
		  { "sWidth": "35%", "aTargets": [ 0 ] },
		  { "sWidth": "25%", "aTargets": [ 1 ] }
		]
	  });
	});

	var checkedCount = 0;
	
	//Keeps track of how many checkboxes are checked, so that we can refuse to submit a page with none checked.
	function checkClick(id_num)
	{
		var id = "check_".concat(id_num);
		var checkbox = document.getElementById(id);
		if(checkbox.checked == true)
		{
			checkedCount++;
		}
		else
		{
			checkedCount--;
		}
	}
	
	//If the Enroll button is hit, make sure a class is selected and at least one user is selected.
	function clickEnroll()
	{
		if($('#class_name').val() == "" || $('#class_name').val() == null){
			alert("Please select a class in which to enroll the students.");
			return false;
		}
		if(checkedCount <= 0)
		{
			alert("Please select a student to enroll.")
			return false;
		}
		return true;
	}
	
	//If the Delete button is hit, there should be a user selected and a confirmation box should pop up.
	function clickDelete()
	{
		if(checkedCount <= 0)
		{
			alert("Please select a user before deleting.")
			return false;
		}
		else
		{
			var ok = confirm("Select ok to confirm deletion");
			return ok;
		}
		return true;
	}
	
	//If the Change Password button is hit, none of the relevant password boxes should be blank.
	function clickPassword()
	{
		//I'll get back to this.
		if(checkedCount <= 0)
		{
			alert("Please select the users who's passwords you are changing.")
			return false;
		}
		return true;
	}
	

</script>

<? get_footer(); ?>