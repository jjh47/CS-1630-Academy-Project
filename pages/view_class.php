<?
	require("../glue.php");
	init("page");
	get_header();

	$username = $_SESSION["username"];
	$user_id = $_SESSION["user_id"];
	$usertype = $_SESSION["usertype"];

	if (!isset($_GET["class_id"]))
	{
		echo "<em>No course selected...</em>";
	}
	else
	{
		$selected = sqlite_escape_string($_GET["class_id"]);
		$results = $db->arrayQuery("select * from Class where class_id = '$selected';");
		if (!isset($results) || empty($results))
		{
			echo "<em>Selected course is invalid...</em>";
		}
		else
		{
			$course_name = isset($results[0]["class_name"]) ? $results[0]["class_name"] : "selected course";
			$results = $db->arrayQuery("select * from Enrollment where user_id = '$user_id' and class_id = '$selected';");
			if (!isset($results) || empty($results)) //user is not in the course
			{
				echo "<em>Sorry $username, your are not currently enrolled in $course_name...</em>";
			}
			else
			{
				$assignments = $db->arrayQuery("select * from Assignment where class_id = '$selected' and is_open = 1;");
				if (empty($assignments) && $_SESSION["usertype"] == "student")
				{
					echo "<em>No assignments currently available for $course_name...</em>";
				}
				else //assignments are available
				{
					echo "<h1>Assignments for $course_name</h1>";
					if ($usertype == "teacher")
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

						if(isset($_SESSION["delete_success"]))
						{
							echo "<div id='class-deletion-message' class='info message'>".$_SESSION["delete_success"]."<br></div>";
							unset($_SESSION["delete_success"]);
							?>
								<script>
									setTimeout(function(){
										$('#class-deletion-message').hide("slow");
									}, 2000);
								</script>
							<?

						}
						elseif(isset($_SESSION["delete_failure"]))
						{
							echo "<div id='class-deletion-message' class='warning message'>".$_SESSION["delete_failure"]."<br></div>";
							unset($_SESSION["delete_failure"]);
							?>
								<script>
									setTimeout(function(){
										$('#class-deletion-message').hide("slow");
									}, 2000);
								</script>
							<?
						}
					}
					echo "<ol id='assignment-list'>";
					foreach ($assignments as $assignment)
					{
						?><li><a href="view_assig.php?class_id=<?= $assignment["class_id"] ?>&amp;assignment_id=<?= $assignment["assignment_id"] ?>"><?= $assignment["title"] ?></a></li><?
					}
					if ($usertype == "teacher")
					{
						echo "<li><a href='create_assig.php?class_id=$selected'>[+]</a></li>";
					}
					echo "</ol>";
				}
			}		
		}
		
		
	}
	get_footer();
?>

