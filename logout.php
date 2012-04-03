<?
	require("glue.php");
	init("script");

	foreach ($_SESSION as $key => $value)
	{
		unset($_SESSION[$key]);
	}

	$_SESSION["logged_in"] = false;

	return_to(HOME_DIR);
?>