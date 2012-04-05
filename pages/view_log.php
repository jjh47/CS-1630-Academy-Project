<?
	require("../glue.php");
	init("page");
	enqueue_script("jquery.dataTables.min.js");
	get_header();

	if(isset($_POST['delete']))
	{
		delete_selected();
	}


	$results = $db->arrayQuery("select * from Log");

	?>
	<div id='log-table-container'>
	<form method="POST" name="logform" action="view_log.php">
	<table id="logtable">	
		<thead>
			<tr>
				<th>Select</th>
				<th>Course</th>
				<th>Assig</th>
				<th>User Name</th>
				<th>Submission Time</th>
				<th>Successful</th>
				<th>Comment</th>
			</tr>
		</thead>
		<tbody>
	<?
	for($i=0;$i<count($results);$i++)
	{
		$res = $results[$i];
		$loaded[$i] = $res['submission_id'];
		echo "<tr>";
		echo "<td><input type='checkbox' name='LogId_$loaded[$i]'/>";
		echo "<td>$res[course_id]</td>";
		echo "<td>$res[assignment_id]</td>";
		echo "<td>$res[username]</td>";
		echo "<td>$res[submission_time]</td>";
		echo "<td>$res[successful]</td>";
		echo "<td>$res[comment]</td>";
		echo "</tr>";
	}
		if(isset($loaded))
			$_SESSION['logloaded'] = $loaded;

	?>
		</tbody>
	</table><br><br>
	<input type="submit" Value="Delete Selected" Name="delete"/>
	<input type="button" Value="Toggle All" name="toggle" onclick="toggle_all()"/>
	</form>
	</div>
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
		unset($_POST["delete"]);
	}

?>
	<script type="text/javascript">

		$(document).ready(function() {
    		$('#logtable').dataTable({});
		});

		function toggle_all()
		{
			$('input[type=checkbox]').each(function(index) {
				if($(this).is(':checked'))
				{
					$(this).removeAttr('checked'); //unchecks it
				}
				else
				{
					$(this).attr('checked','checked'); //turns them on
				}
			});
		}


	</script>

<? get_footer(); ?>