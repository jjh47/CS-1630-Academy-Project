<?
	
	require("../glue.php");
	init("form_process");


	if(isset($_POST['delete']))
	{
		delete_selected();
		return_to("view_log.php");
	}


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



