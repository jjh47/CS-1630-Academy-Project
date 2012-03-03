<?
	require("glue.php");
	init("form_process");

	//for now, login universally
	$_SESSION["username"] = $_POST["username"];
	$_SESSION["usertype"] = "";
	$_SESSION["email"] = "";
	$_SESSION["user_id"] = "";

	$_SESSION["logged_in"] = true;
	echo "authenticated";
?>