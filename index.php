<?
	require("glue.php");
	init("page");
	get_header();
?>
This is the homepage. Do things here.
<ul>
<li><a href="pages/view_classes.php">View Classes</a></li>
<li><a href="pages/submit_assig.php?class_id=0&assignment_id=0">Submit Sample Assignment</a></li>
<li><a id='logout-anchor' href='javascript:logout()'>Logout</a></li>
</ul>

<? get_footer(); ?>

