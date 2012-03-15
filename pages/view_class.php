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
				$assignments = $db->arrayQuery("select * from Assignment where class_id = '$selected';");
				if (empty($assignments))
				{
					echo "<em>No assignments currently available for $course_name...</em>";
				}
				else //assignments are available
				{
					echo "<h1>Assignments for $course_name</h1>";
					echo "<ol id='assignment-list'>";
					foreach ($assignments as $assignment)
					{
						?><li><a href="view_assig.php?class_id=<?= $assignment["class_id"] ?>&amp;assignment_id=<?= $assignment["assignment_id"] ?>"><?= $assignment["title"] ?></a></li><?
					}
					echo "</ol>";
				}
			}		
		}
		
		
	}
	get_footer();

	/*
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
	}

	echo "<h1>Courses for $username</h1>";

	echo "<ol id='course-list'>";
	foreach ($courses as $course)
	{
		?><li><a href="view_class.php?class_id=<?= $course["class_id"] ?>"><?= $course["class_name"] ?></a></li><?
	}
	echo "</ol>";
?>


<? get_footer(); ?>
*/
?>

