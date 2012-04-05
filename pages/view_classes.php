<?
	require("../glue.php");
	init("page");
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