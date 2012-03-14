<?
	require("glue.php");
	init("form_process");

	$email = sqlite_escape_string($_POST["email"]);
	$password = sqlite_escape_string($_POST["password"]);

	$results = $db->arrayQuery("select * from User where email = '$email'");

	if (!isset($results) || empty($results))
	{
		echo "Invalid User";
		if (isset($results) && $results !== FALSE)
		{
			//echo "Count: ".count($results)."<br>";
			//var_dump($results);
		}
	}
	else
	{
		$user = $results[0];
		if ($user["password"] != crypt($password, '$5$'.$user["salt"])) //the $5$ ensures that SHA-256 is used
		{
			echo "Invalid Password";
		}
		else
		{
			$_SESSION["usertype"] = $user["usertype"];
			$_SESSION["email"] = $user["email"];
			$_SESSION["username"] = $user["username"];
			$_SESSION["user_id"] = $user["user_id"];
			$_SESSION["logged_in"] = true;
			echo "authenticated";
		}
	}

?>

