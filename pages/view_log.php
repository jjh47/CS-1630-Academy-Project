<?
	require("../glue.php");
	init("page");
	//enqueue_script($filename)
	get_header();


/* PLEASE CAREFULLY READ THESE COMMENTS!
 *
 * FIRST: specify any $_GET variables you need to have when getting to this page.  For example, if it is the page for viewing an assignment, assume that $_GET will have a variable representing the assignment ID.  That way, you know which assignment to query from the database.  TELL ME HERE WHAT YOU WANT THE VARIABLE TO BE NAMED.  This ensures that the pages will link together correctly.
 * use $_SESSION["username"] and $_SESSION["usertype"] to segregate the components of the page (i.e. if ($username != "admin") etc.)
 * use $db to make database calls
 * make calls to get ALL relevant information on the page loaded into PHP variables
 * CAREFULLY DOCUMENT the contents of these variables (e.g. $assignments is an array and each element is an array representing an assignment.  In this array, "id" => the ID of the course, "name" => the name of the course, etc)
 * Do not worry about having too much information loaded - it is easy to show only parts of it or show it in chunks with HTML/JavaScript.  Just worry about getting it on the page.
 *
 * FORMS: If this page is a data page and requires a form, please either 1. specify the fields the form needs to have (i.e. inputs: text "name", text "email", password "password").  This includes what type of input it is and WHAT THE NAME IS.  This is critical to making sure it lines up with get/post on the next page.  If you are comfortable writing HTML, simply write the form.  If any information from your PHP variables needs to be included, please either included it or leave careful instructions.
 * MAKE ABSOLUTELY SURE you use the add_token() method in every form or your form will not work
 *
 * FINALLY: don't forget to check if things exist?  Use the (bool ? A : B) notation to accomplish this.  For example.  $result = ((isset($var) && !empty($var)) ? $var : "" )
 *
 */


	if(isset($_POST['delete']))
	{
		delete_selected();
	}


	$results = $db->arrayQuery("select * from Log");

	?>
	<form method="POST" name="logform" action="view_log.php">
	<table id="logtable">	
		<tr>
		<th>Select</th>
		<th>Submission ID</th>
		<th>Assignment ID</th>
		<th>Course ID</th>
		<th>User ID</th>
		<th>User Name</th>
		<th>Submission Time</th>
		<th>Successful</th>
		<th>Comment</th>
		</tr>
	<?
	for($i=0;$i<count($results);$i++)
	{
		$res = $results[$i];
		$loaded[$i] = $res['submission_id'];
		echo "<tr>";
		echo "<td><input type='checkbox' name='LogId_$loaded[$i]'/>";
		echo "<td>$res[submission_id]</td>";
		echo "<td>$res[assignment_id]</td>";
		echo "<td>$res[course_id]</td>";
		echo "<td>$res[user_id]</td>";
		echo "<td>$res[username]</td>";
		echo "<td>$res[submission_time]</td>";
		echo "<td>$res[successful]</td>";
		echo "<td>$res[comment]</td>";
		echo "</tr>";
	}
		if(isset($loaded))
			$_SESSION['logloaded'] = $loaded;

	?>
	</table>
	<input type="submit" Value="Delete Selected" Name="delete"/>
	<input type="button" Value="Toggle All" name="toggle" onclick="toggle_all()"/>
	</form>
<?

	function delete_selected()
	{	
		global $db;
		$loaded = $_SESSION['logloaded'];
		for($i=0;$i<count($loaded);$i++)
		{
			if(isset($_POST['LogId_' . $loaded[$i]]))
			{
				$db->queryExec("DELETE FROM Log WHERE submission_id=$loaded[$i]");
			}
		}

	}

?>
	<script type="text/javascript">


		$(document).ready(function() {
    		$('#logtable').dataTable();
		} );

		var onoff=false;


		function toggle_all()
		{
			$('input').each(function(index) {
				if(!onoff)
				{
					$(this).attr('checked','checked'); //turns them on
				}
				else
				{
					$(this).removeAttr('checked'); //unchecks it
				}
			});
			if(onoff)
				onoff=false;
			else
				onoff=true;

		}


	</script>

<? get_footer(); ?>