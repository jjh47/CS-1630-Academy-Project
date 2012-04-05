<?
	require("glue.php");
	init("page");
	get_header();
?>

<h1>CS 1630 Homework Grading System</h1>
<?
	echo "<p>Welcome to the Pittsburgh Science and Technology Academy Homework Grading System!</p>";
	switch($_SESSION["usertype"])
	{
		case "teacher":

			break;

		case "student":

			break;

		case "admin";
			echo "As an administrator you have the ablility to perform different maintainence actions. You can add users to the system, create new classes, and enroll users in specific classes. To get started, please select an option from the navigation menu on the left.";
			break;
	}
?>

<? get_footer(); ?>

