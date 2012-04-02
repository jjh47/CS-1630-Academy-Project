<?
	require("../glue.php");
	init("page");
	get_header();

	$username = $_SESSION["username"];
	$user_id = $_SESSION["user_id"];
	$usertype = $_SESSION["usertype"];
	
	if ($usertype == "teacher" || $usertype == "admin")
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
?>


<? get_footer(); ?>