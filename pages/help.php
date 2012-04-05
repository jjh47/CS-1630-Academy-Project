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
				<h1>Admin Help Page</h1>
				<ol>
					<li><a href="#CreateUser">Creating Users</a></li>
					<li><a href="#CreateClass">Creating Classes</a></li>
					<li><a href="#ModifyUsers">Modifying Users</a></li>
				</ol>

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
			   Once created, you can now add students to the class using the "Modify Users" page.</p>
			   
			   <h2 id="ModifyUsers">Modify Users</h2>
			   <p>First, navigate to the "Modify Users" page in the navigation. From here you can select any number of users
			   	and perform either individual or bulk actions on those users.  Those actions include deleting users, changing
			   	user passwords by providing a password in the blank field, or enrolling users in courses.  In order to enroll
			   	a user in a course, you must first select that course from the drop-down list.  Enrolling a teacher in a course
			   	means giving that teacher administrative privileges over that course.  That teacher may now create, edit, delete,
			   	and grade assignments for that course.  Enrolling a student in a course means giving that student permission to view
			   	assignments for that course and submit files when the assignment is available for submission.</p>
			<?
			break;
	}



	get_footer(); 
?>