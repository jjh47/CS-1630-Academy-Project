<?
	require("glue.php");
	init("page");
	get_header();
?>

<h1>CS 1630 Homework Grading System</h1>
<?
	echo "<h2>Welcome to the Pittsburgh Science and Technology Academy Homework Grading System!</h2>";
	switch($_SESSION["usertype"])
	{
		case "teacher":
			echo "<p>As a teacher you have a few different options available. You may view the classes for which you are registered.
				From there, you can create assignments or view and edit preexisting assignments.
   				You may also choose to review files for and grade any assignment.  However, it is recommended that
   				you run the grading script before grading any assignment.  To get started, please select an option from the navigation menu on the left.<p>";
			break;

		case "student":
			echo "<p>As a student you have a few different options available. You can view all of the classes for which you are registered,
				 and from there, you may look at assignments your teacher has posted.  If the assignment is open for submission, you may submit your files,
				  which will then be graded by the teacher.  Be aware that all assignments have a deadline.  If you are about to submit files for an assignment
				  and the normal submission deadline has passed, you will be warned that your files will be marked as late.  Once the assignment late deadline
				  has passed, if the teacher has graded your assignment, you will be able to view your grade on the assignment page.
				  To get started, please select an option from the navigation menu on the left.<p>";
			break;

		case "admin";
			echo "<p>As an administrator you have the ablility to perform different maintainence actions. You can add users to the system, create new classes, and enroll users in specific classes. To get started, please select an option from the navigation menu on the left.</p>";
			break;
	}
?>

<? get_footer(); ?>

