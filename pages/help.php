<?
	require("../glue.php");
	init("page");
	get_header();


	switch($_SESSION["usertype"])
	{
		case "teacher":

			break;

		case "student":

			break;

		case "admin":
			?>
				<p><h1>Table of Contents</h1></p>
			   <p><a href="#CreateUser">Creating Users</a></p>
			   <p><a href="#CreateClass">Creating Classes</a></p>
			  <?// <p><a href="#AddStudent">Adding Students to Classes</a></p> ?>
			   
			   <h2 id="CreateUser">Creating Users</h2>
			   <p>In order to create a new user you must first navigate to the "Add User" section
			   of the webpage. From there you have the option of adding different kinds of users to the system.
			   This can be done by either adding an individual user via the provided form or by uploading
			   a .csv file containing one or more users. After filling out the username, email, and password field
			   you must choose which type of user to create. Choosing either student, teacher, or administrator will 
			   create the account with the permissions that come along with that user type. By using the file browser
			   option you can navigate to a .csv file and upload it in order to add multiple users at once. The file 
			   must be formatted properly. A success or error message will be displayed at the top of the screen once
			   the user(s) are submitted.</p>
			   
			   <h2 id="CreateClass">Creating Classes</h2>
			   <p>A class can be added to the system by navigating to "Create Class" section of the webpage. From 
			   here you will be provided a form that takes in all necessary information such as teacher email, room 
			   number, description, etc. Once the appropriate information has been filled in, click the submit button to process
			   the form. A success or failure message will be displayed at the top of the screen.
			   Once created, you can now <a href="#AddStudent">add students to the class</a>.</p>
			   
			   <?/*<h2 id="AddStudent">Adding Students to Classes</h2>
			   <p>First, navigate to the class for which you want to add students to. From here you have two options; 
			   either you can add a single student to the class or you can add multiple students by supplying a .csv file
			   that has all of the student information in it. You must supply the username of the student in order to add
			   an individual. Alternatively, you can use the file browser to select a .csv file from your computer that is
			   properly formatted with all of the students information. Hitting submit will add all the given students to 
			   that particular class. Students will now be able to access that class and any information you or the teacher
			   has provided.</p>*/?>
			<?
			break;
	}



	get_footer(); 
?>