<?
	require("../glue.php");
	init("page");
	enqueue_script("jquery.dataTables.min.js");
	get_header();

	$username = $_SESSION["username"];
	$user_id = $_SESSION["user_id"];
	$usertype = $_SESSION["usertype"];
	
	$results = $db->arrayQuery("select class_id from Enrollment where user_id = '$user_id'");
	if (!isset($results) || empty($results)) //user has no classes
	{
		if ($usertype == "student"):
			echo "<em>Sorry $username, you are not currently enrolled in any courses...</em>";
		else:
			echo "<em>Sorry $username, you do not currently have any courses available...</em>";
		endif;
	}
	else

	//This if block here contains the confirmation/error messages the admin receives upon trying to delete classes.
	if ($usertype == "admin")
	{
		if (isset($_SESSION["delete-classes-message"]))
		{
			echo "<div id='class-delete-classes-message' class='info message'>".$_SESSION["delete-classes-message"]."<br></div>";
			unset($_SESSION["delete-classes-message"]);
			?>
				<script>
					setTimeout(function(){
						$('#class-delete-classes-message').hide("slow");
					}, 2000);
				</script>
			<?
		}
		elseif (isset($_SESSION["delete-classes-message-error"]))
		{
			echo "<div id='class-delete-classes-message' class='warning message'>".$_SESSION["delete-classes-message-error"]."<br></div>";
			unset($_SESSION["delete-classes-message-error"]);
			?>
				<script>
					setTimeout(function(){
						$('#class-delete-classes-message').hide("slow");
					}, 2000);
				</script>
			<?	
		}
	}	
	
	//If the user is an admin, they get all classes, with delete buttons instead of the user's own classes.
	if ($usertype == "admin")
	{		
		$results = $db->arrayQuery("select class_id, class_name, instructor_id, username, instructor_email from Class, User where User.user_id = Class.instructor_id");
		//The above SQL query is long, but it essentially justs takes the Class table but with the instructor's name added to each row.
		?>
		<form id="delete_classes" method="post" action="process_delete_classes.php" >
		<input type="submit" name="delete_classesSubmit" onclick="return clickDelete()" value="Delete Classes"/>&nbsp;
		<input type="reset" value="Reset">
		<? add_token(); ?>
		<br />
		<br />
		<table id="classes_table">
			<thead>
				<tr>
					<th>Class Name</th>
					<th>Instructor Name</th>
					<th>Email</th>
					<th>Select</th>
				</tr>
			</thead>
			<tbody>
			<!-- It is now time to go to php to populate the table with info from $results -->
			<?php
			foreach ($results as $entry)
			{ 
			  //It's easier to echo this out if it is stored this way.
			  $id = $entry['class_id'];
			  echo "<tr>";
			  //First we have the class's name and instructor's name and email.
			  echo "<td>{$entry['class_name']}</td><td>{$entry['username']}</td><td>{$entry['instructor_email']}</td>";
			  //Now a checkbox for deleting the class.
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
		  $('#classes_table').dataTable({
			"aoColumnDefs": [
				  { "asSorting": [ "asc", "desc" ], "aTargets": [ 0, 1, 2 ] },
				  { "asSorting": [ ], "aTargets": [ 3 ] },
				  { "sWidth": "35%", "aTargets": [ 0 ] },
				  { "sWidth": "40%", "aTargets": [ 1 ] }
				] //the sizes might need to be changed. These percentages looked good when I tested it.
			});

		  $('#classes_table_filter').after("<br>");

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
		
		//If the Delete button is hit, there should be a class selected and a confirmation box should pop up.
		function clickDelete()
		{
			if(checkedCount <= 0)
			{
				alert("Please select a class before deleting.")
				return false;
			}
			else
			{
				var ok = confirm("Select ok to confirm deletion");
				return ok;
			}
			return true;
		}

	</script>
	<?
	}
	else //Teachers and students instead see only their classes.

	{
		$results = $db->arrayQuery("select class_id from Enrollment where user_id = '$user_id'");
		if (!isset($results) || empty($results)) //user has no classes
		{
			if ($usertype == "student"):
				echo "<em>Sorry $username, you are not currently enrolled in any courses...</em>";
			else:
				echo "<em>Sorry $username, you do not currently have any courses available...</em>";
			endif;
		}
		else
		{
			foreach ($results as $row)
			{
				$course = $db->arrayQuery("select * from class where class_id = '".$row["class_id"]."'");
				if (!empty($course))
				{
					$courses[] = $course[0];
				}
			}

			echo "<h1>Courses for $username</h1>";

			echo "<ol id='course-list'>";
			foreach ($courses as $course)
			{
				?><li><a href="view_class.php?class_id=<?= $course["class_id"] ?>"><?= $course["class_name"] ?></a></li><?
			}
			echo "</ol>";
		}
	}
?>


<? get_footer(); ?>