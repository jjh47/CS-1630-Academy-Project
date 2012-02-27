<?
	require("glue.php");
	init("form_process");

	//for now, login universally
	$_SESSION["username"] = $_POST["username"];

	$_SESSION["usertype"] = "";

	$_SESSION["logged_in"] = true;
	echo "authenticated";


?>