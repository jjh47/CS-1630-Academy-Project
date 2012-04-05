<?
	require("../glue.php");
	init("page");
	get_header();


	switch($_SESSION["usertype"])
	{
		case "teacher":
			?>
				<h1>Teacher Help Page</h1>
				<h2>Table of Contents</h2>
				<ol>
				   <li><a href="#ViewClasses">Viewing Classes</a></li>
				   <li><a href="#CreateAssignment">Creating Assignments</a></li>
				   <li><a href="#EditAssignment">Editting Assignments</a></li>
				   <li><a href="#GradeScript">Running Grading Script</a></li>
				   <li><a href="#GradeAssignment">Grading Assignments</a></li>
				</ol>
				   
				   <h2 id="ViewClasses">View Classes</h2>
				   <p>Using the navigation menu on the left hand side of the page you can view all classes
				   for which you are registered as the teacher. You must be added as a teacher by a system administrator
				   before a class will appear in your list. Clicking on the "View Classes" link will take 
				   you to a new page containing a list of your classes. Clicking on a specific class will bring
				   you to a page that contains that class's information including any assignments that have previously been
				   created. From here you can choose to create an assignment, edit an assignment, or 
				   grade an assignment.</p>
				   
				   
				   <h2 id="CreateAssignment">Creating Assignments</h2>
				   <p>Creating an assignment can be achieved by using the link found on the left hand side of the
				   screen in the navigation menu. This link will take you to new page containing a form that you 
				   can fill out to specify any desired details for the assignment. You must specify for which class 
				   the assignment is intended. You may also create a new assignment by naving to the class page first. 
				   This will allow you to create an assignment specifically for this class without having to specify a 
				   course from the drop down. Once the desired information has been entered,
				   click the create button to finish creating the assignment. A message will be displayed at the
				   top of the screen, indicatin success or failure.  The message will be green if the creation was succesful 
				   and red if there was an error. Upon a succesful creation of 
				   an assignment, the assignment will be visible to the students enrolled in the class, and the will be able to submit
				   files, provided you listed the assignment as open and not closed.
				   </p>
				   
				   <h2 id="EditAssignment">Editting Assignments</h2>
				   <p>In order to edit an already existing assignment you must 
				   navigate to a particular class and then choose the desired assignment
				   from the list of previously created assignments. Once you have chosen the assignment you wish to 
				   edit you must click on edit and will be shown the same options that are presented when 
				   you create an assignment. All fields will be preloaded with the current assignment information, so if
				   you submit immediately, no information will be changed.  Or, at this screen, you may choose to edit any
				   of the information in the current fields. Once you are
				   finished, click the submit button. A green message will be shown at the top of the page if the edit
				   was succesful, otheriwise a red error message will be shown. Upon a succesful edit, the new information
				   will be reflected when you or a student views the assignment.
				   </p>
				   
				   <h2 id="GradeScript">Running Grading Script</h2>
				   <p>The first part of grading assignments is running the grading script to automatically compile,
				   run, and record the output of the students' submissions. As a teacher you will be given SSH access to the 
				   elements.cs.pitt.edu server cluster where the students' files are contained. First it is important to identify the file structure 
				   that will be used. Within the file for each class there are files for each assignment made. In these
				   assignment files, there is a file for each student and the grading script, script_grade.php. The script_grade.php file
				   is automatically placed within the assignment folder when the first student submits the first file.  Each
				   student's folder contains any files that student has submitted.  There may also be a results.txt and late.txt file.  The results.txt
				   file will be created after running the grading script and will contain the results.  The late.txt file will contain a log
				   of which of the student's files were submitted late.  Both files will be visible on the website when grading the assignment.</p>
				   <p>From the selected assignment file you can compile all of the
				   students' projects and record the output by using the command "php script_grade.php -c assignment_name.java".
				   The -c flag stands for compile and the argument that corresponds to it will indicate the file that will 
				   be used to run javac. Note that the grading scrpt also supports Python, which does not need to be compiled.  Therefore,
				   if you are grading a Python assignment, you may skip this step.  To run a test input file on all of the students' 
				   projects and record the output use the command: "php script_grade.php -t assignment_name -f input_file_name.txt". The -t flag stands for test and the argument 
				   that corresponds to it indicates the Java class to execute. The -f file stands for file and the argument 
				   corresponding to it indicates the test file. To clean all of the Results.txt files for the assignment use 
				   the command: "php script_grade.php -d all". The -d flag stands for delete and "all" would delete each students' 
				   results.txt file for that project. To clean an individual student's Results.txt file for the assignment use
				   the command: "php script_grade.php -d studentID". The argument that corresponds to -d will either be "all" or a 
				   specific student id (like in this example). When a test is run on the the students' projects, the output will 
				   be appended on to the end of the results.txt file which already contains the compilation information. If the 
				   project did not compile successfully, the resulting errors will be appended for this section. Multiple tests 
				   can be run and they will be simply appended to the end of the file in another section. The sections will be 
				   distinguished by the title of the input file that was run.</p>
				   
				   <h2 id="GradeAssignment">Grading Assignments</h2>
				   <p>After running the grading script, the individual student submission can be reviewed
				   via the grading website. On the left hand side of the screen in the navigation menu there is an option to 
				   "Grade Assignment". Here you will have to select which class the assignment is in and then pick the desired
				   assignment to grade. From here you will be brought to a page for the first student listed for that assignment.
				   At the top of the screen you can select wich file to view, which will be pulled from the student's folder for 
				   that assignment. Results.txt will contain the output generated from the grading script but you can also view
				   the actual source code directly on the page. Underneath the view window of the file you selected will be the 
				   grading rubric for that student's submission. Whenever values that are entered into the fields will be added
				   together in a total at the bottom. You may also provide comments to the student if you wish.  All grading results
				   will be emailed to the student for whom the grade is being submitted. 
				   Clicking reset will clear all of the rubric fields. Underneath the submit/reset buttons are arrows that allow you to 
				   cycle throught the different students for that assignment whose names will be displayed at the top of the screen.
				   </p>
			<?
			break;

		case "student":
			?>
				<h1>Student Help Page</h1>
				<h2>Table of Contents</h2>
				<ol>
			   		<li><a href="#ViewClasses">Viewing Classes</a></li>
			   		<li><a href="#ViewAssignments">Viewing Assignments</a></li>
			   		<li><a href="#SubmitSolution">Submitting Solutions</a></li>
			   	</ol>
			   
			   <h1 id="ViewClasses">View Classes</h1>
			   <p>In order to view the classes in which you are enrolled, use the "View Classes" link
				found in the navigation menu on the left hand side of the screen. This option will
				take you to a new page where any classes for which you are currently registered can be found.
				You must be added to a class by a system administrator in order for the class to appear on this list.
				Choosing a specific class will bring you to a page containing any information provided
				about the class.  The page will also contain a list of assignments for which you may be able to submit solutions.</p>
			   
			   <h1 id="ViewAssignments">View Assignments</h1>
			   <p>Within the page for each class you will be able to view all assignments for that
			   particular class. Clicking on an individual assignment will
			   redirect you to the assignment's information page. Here, you will be able to view any information provided
			   for the assignment, such as description, due date, late due date, etc. From this assignment page you have the
			   option to submit solution files if the late due date has not passed.</p>
			   
			   <h1 id="SubmitSolution">Submit a Solution</h1>
			   <p>Submitting a solution for an assignment, provided the teacher is still accepting solutions,
			   can be done once you navigate to a particular class and assignment. On the assignment's page you will
			   see a form to allow you to upload files. Click on the browse button to pull up a file browser and navigate to the
			   desired file location. Click the open button and browse for a file to include it in the submission. You can choose
			   to add more or less files as needed, but the default number of fields displayed represents the suggested number of files
			   your teacher has indicated should be submitted the assignment. Once satisfied with the chosen files, click on the submit button to finish submitting
			   your files. A status message will be displayed at the top.  The message will be green if the files were properly submitted
			   and red if there was an issue.  Once you have submitted files, you will be able to view a list of files you have submitted.  You will be
			   able to view the contents of these files and, should you choose, delete the selected file.</p>
			<?
			break;

		case "admin":
			?>
				<h1>Admin Help Page</h1>
				<h2>Table of Contents</h2>
				<ol>
					<li><a href="#CreateUser">Creating Users</a></li>
					<li><a href="#CreateClass">Creating Classes</a></li>
					<li><a href="#DeleteClass">Deleting Classes</a></li>
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
			   
			   <h2 id="DeleteClass">Deleting Classes</h2>
			   <p>In much the same way that you can create a class, you can also delete classes.  The "Delete Classes" page
			   	works a little differently than then "Create Classes" page in that it allows you to take bulk action.  With
			   	the "Delete Classes" page, you can search for classes by name or just view a list of all classes.  You can then
			   	check a box for every class you wish to delete.  Upon submitting the form, you will delete any of the classes you
			   	have selected.</p>

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