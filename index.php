<?
	require("glue.php");
	init("page");
	get_header();
	include("includes/database.php");
?>
This is the homepage.
<br><a id='logout-anchor' style="margin: 20px;" href='javascript:logout()'>Logout</a><br><br>

<? get_footer(); ?>

