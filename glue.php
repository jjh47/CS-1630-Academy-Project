<?
session_start();

require("includes/defines.php");
include("includes/Browser.php");

$db = new SQLiteDatabase(DB_PATH, 0666, $dberror);
$_SESSION["db"] = $db;

//if (MODE == "dev"): include("includes/database.php"); endif;

if (!isset($_SESSION["tokens_set"]))
{
	set_tokens();
}

function init($type = "page")
{

	if (!isset($_SESSION["script_list"]))
	{
		$_SESSION["script_list"][] = "jquery.js";
		$_SESSION["script_list"][] = "functions.js";
	}

	switch($type)
	{
		case "offline_script":
			check_env();
			break;

		case "script":
			check_private_token();
			break;

		case "form_process":
			check_private_token();
			check_public_token();
			break;

		case "page":
		default:
			break;
	}
}

function return_to($pagename = "/")
{
	header("Location: $pagename");
}

function check_env()
{
	if (empty($_SERVER['REMOTE_ADDR']) && !isset($_SERVER['HTTP_USER_AGENT']) && count($_SERVER['argv']))
	{
		header("Location: /");
		die;
	}
}

function check_private_token()
{
	if (!isset($_SESSION["private_token"]) || !isset($_SESSION["public_token"]))
	{
		header("Location: /");
		die;
	}
}

function check_public_token()
{
	if (!isset($_POST["token"]) || $_POST["token"] != $_SESSION["public_token"])
	{
		header("Location: /");
		die;
	}
}

function get_header()
{
	lock();
	?>
	<!doctype html>
		<html>
		<head>
			<title>Pittsburgh Science & Technology Academy | Homework Submission Page</title>
			<? print_links(); ?>
		</head>	
		<body>
			<div id="title-bar">
				<div id='title'>Pittsburgh Science and Technology Academy</div>
				<div id='subtitle'>Homework Grading and Submission System</div>
				<img src="<?= HOME_DIR ?>images/PSTALogo.png">
			</div>	
			<div id="content">
				<div id="sidebar" class='nav'>
					<? $usertype = $_SESSION["usertype"]; ?>
					<div class='nav-item'>Welcome, <?= $_SESSION["username"] ?></div>
					<? hr(); ?>
					<div class='nav-item'><a href="<?= HOME_DIR ?>">Home</a></div>
					<div class='nav-item'><a href="<?= $_SERVER["HTTP_REFERER"] ?>">Back</a></div>
					<? hr(); ?>
					<!--both-->
					<div class='nav-item'><a href="<?= HOME_DIR ?>pages/view_classes.php">View Classes</a></div>
					<!--specific-->
					<? if ($usertype == "student"): ?>
					<!--student only stuff-->
					
					<? elseif ($usertype == "teacher"): ?>
					<!--teacher only stuff-->
					<div class='nav-item'><a href="<?= HOME_DIR ?>/pages/create_assig.php">Create Assignment</a></div>

					<? endif; ?>
				</div>
				<div id="inner-content">
	<?
}

function hr()
{
	echo "<hr class='light'>";
	echo "<hr class='dark'>";
}

function get_footer()
{
	?>
		</div>
		<br style="clear: both;">
		</div>
		<div id='bottom-bar'><a id='logout-anchor' href='javascript:logout()'>Logout</a></div>
	</body>
	</html>
	<?
}

function add_token()
{
	?> <input type='hidden' name='token' id='token' value='<?= $_SESSION['public_token'] ?>'> <?
}

function lock()
{
	$validation_url = HOME_DIR."process_login.php";

	//if not logged in
	if (!isset($_SESSION["logged_in"]) || empty($_SESSION["logged_in"]) || $_SESSION["logged_in"] == false)
	{
		?>
		<!doctype html>
		<html>
		<head>
			<title>Pittsburgh Science & Technology Academy | Homework Submission Page</title>
			<? print_links(); ?>
		</head>	
		<body class='lock'>
			<div id="lock-inner-contents">
				<div id="login-title">
					<div id='title'>Pittsburgh Science and Technology Academy</div>
					<div id='subtitle'>Homework Grading and Submission System</div>
					<img src="<?= HOME_DIR ?>images/PSTALogo.png">
				</div>
				<div id="lock-wrapper">
					<form id="login_form" method="post">
						<big><strong>Student &amp; Teacher Login</strong></big>
						<br><br><br>
						<div id='lables'>
							Email
							<br><br><br>
							Password
						</div>
						<input type="email" name="email" id="email" value="rafael.colton+ten@gmail.com" onkeypress="eval_form(event,'#login-submitbutton')"><br>
						<br>
						<input type="password" name="password" id="password" value="asdf" onkeypress="eval_form(event,'#login-submitbutton')"><br>
						<br>
						<div id='buttons'>
							<input type="button" class="button" value="Submit" id = "login-submitbutton" onclick = "submit_unlock_request()">&nbsp;
							<input type="button" class="button" id="login-resetbutton" onclick="reset_form()" value="Reset"><br>
						</div>
						<br>
						<? add_token(); ?>
					</form>
					<div id="error-message" class='warning message' style='display: none;'></div><br>
					<small><em>Please contact your system administrator with any issues regarding login.</em></small>
				</div>
				<script>
				function reset_form(){
					$('#username').val("");	
					$('#password').val("");	
				}
				
				function submit_unlock_request(){
					if ($("#username").val() == "" || $("#password").val() == "" ){
						alert("Both fields required.");
					}
					else{
						var $formdata = $("#login_form").serialize();
						post('<?= $validation_url ?>', $formdata, function(){
							var $data = arguments[0];
							if ($data.indexOf("authenticated") != -1){
								window.location.href = "<?= HOME_DIR ?>";
							}
							else{
								
								if (! typeof t === undefined) clearTimeout(t);
								$('#error-message').html($data);
								$('#error-message').show("slow");
								t = setTimeout(function(){
									$('#error-message').hide("slow");
								},2500);
							}
						});	
					}
				}
				</script>
			<div id='bottom-bar'></div>
			</div>
		</body>
		</html>
		<?
		die;
	}
}

function enqueue_script($filename)
{
	$_SESSION["script_list"][] = $filename;
}

function print_links()
{
	#css
	echo "<link type='text/css' href='".HOME_DIR."css/styling.css' rel='stylesheet'>\n";

	#other
	echo "<link rel='shortcut icon' href='".HOME_DIR."images/psta-logo.png'>\n";

	#javascript
	foreach ($_SESSION["script_list"] as $filename)
	{
		echo "<script src='".HOME_DIR."js/$filename'></script>\n";
	}
}


function set_tokens()
{
	$characters = "abcdefghijklmnopqrstuvwxyz0123456789";
	$length = strlen($characters);
	$public_token = "";
	$private_token = "";

	for ($x=0; $x<50; $x++)
	{

		$private_token .= $characters[rand(0,$length-1)];
		$public_token .= $characters[rand(0,$length-1)];
	}
	$_SESSION["private_token"] = $private_token;
	$_SESSION["public_token"] = $public_token;
	$_SESSION["tokens_set"] = true;
}

function error_message($message)
{
	echo "<em>$message</em>";
}