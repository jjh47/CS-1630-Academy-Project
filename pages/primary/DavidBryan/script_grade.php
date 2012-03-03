<?
	require("../../../glue.php");
	init("script");

/* PLEASE CAREFULLY READ THESE COMMENTS!
 * 
 * On script pages, you should not need to reference a lot of other pages.  You WILL need
 * to reference data though.
 * 
 * use $_SESSION["username"] and $_SESSION["usertype"] to segregate the components of the page (i.e. if ($username != "admin") etc.)
 * use $db to make database calls
 *
 * CAREFULLY use the defines.php file (includes/definies.php) to define any important information like file paths - specifically anything that may chance from one person's machine to another or on the production server.  This makes sure we can just change things here and they won't break elsewhere.  Please name your defines carefully.
 *
 *
 */


	return_to(); //don't forget to specify a page
?>
