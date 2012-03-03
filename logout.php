<?
	require("glue.php");
	init("script");

	$_SESSION["logged_in"] = false;

	unset($_SESSION["username"]);
	unset($_SESSION["usertype"]);
	unset($_SESSION["tokens_set"]);
	unset($_SESSION["public_token"]);
	unset($_SESSION["private_token"]);	

	echo "success";
?>