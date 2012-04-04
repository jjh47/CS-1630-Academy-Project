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

 $usertype = $_SESSION["usertype"];
	//We start out with checking that the user is an admin and rejecting them if they are not.
	if($_SESSION["usertype"] != "admin")
	{
		error_message("User does not have access to this feature...");
		get_footer();
		die;
	}
	else{ //Now we pull the data for the table from the database.
		//This should be enough to initially populate the table.
		$results = $db->arrayQuery("select user_id, username, email, usertype from User");
		//Change needed:
		//May need another request to verify changes, such as having the enrollment table to approve/deny enrollment changes
		
	}

?>

<!-- Now we have the HTML for displaying the table. After this works, add on DataTables -->
<h1>Modify Users</h1>
<!-- TODO: create page process_modify_users.php (or do it another way, inline?) and function (below) submit_modify_users -->
<form id="modify_users" method="post" action="process_modify_users.php" onsubmit="return submit_modify_users()">
<table id="users_table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Password</th>
			<th>Select</th>
        </tr>
    </thead>
    <tbody>
	<!-- It is now time to go to php to populate the table with info from $results -->
	<?php
	//$arrayLength = count($results); //Delete if not used at end
	//for($i = 0; $i <= $arrayLength; $i = $i + 6)
	foreach ($results as $entry)
	{ 
      echo "<tr>"; 
      echo "<td>{$entry['username']}</td><td>{$entry['usertype']}</td><td>text box goes here</td><td>checkbox goes here</td>"; 
      echo "</tr>";     
	} 
	?>
	
    
	
    </tbody>
</table>
</form>



<? get_footer(); ?>