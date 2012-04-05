 <?
//session_start();

$db = $_SESSION["db"];
$testing = true;


if ($db && $testing)
{
    drop_tables();
    query("
            create table 'User' (
                'user_id' integer primary key,
                'username' text not null,
                'email' text not null,
                'usertype' text not null,
                'password' text not null,
                'salt' text not null,
                unique (email)
            )
        ");
    query("
            create table 'Class' (
                'class_id' integer primary key,
                'class_name' int not null,
                'instructor_id' int not null,
                'instructor_email' text not null,
                'room' text,
                'description' text
            )
        ");
    query("
            create table 'Assignment' (
                'assignment_id' integer primary key,
                'class_id' int not null,
                'title' text not null,
                'date_assigned' text not null,
                'description' text,
                'due_date' text not null,
                'late_due_date' text not null,
                'is_open' int(1) default 1,
                'num_files_required' int,
                foreign key ('class_id') references 'Class' ('class_id') on delete cascade
            )
        ");
    query("
            create table 'Enrollment' (
                'class_id' int not null,
                'user_id' int not null,
                primary key ('class_id','user_id'),
                foreign key ('class_id') references 'Class' ('class_id') on delete cascade,
                foreign key ('user_id') references 'User' ('user_id') on delete cascade
            )
        ");
    query("
            create table 'Log' (
                'submission_id' integer primary key,
                'assignment_id' int not null,
                'course_id' int not null,
                'user_id' int not null,
                'username' text not null,
                'submission_time' text not null,
                'successful' int(1) not null,
                'comment' text
            )
        ");
    query("
            create table 'Grade' (
                'user_id' int not null,
                'assignment_id' int not null,
                'grade' int not null,
                'comment' text,
                primary key ('user_id', 'assignment_id'),
                foreign key ('user_id') references 'User' ('user_id') on delete cascade,
                foreign key ('assignment_id') references 'Assignment' ('assignment_id') on delete cascade
            )
        ");
    insert_users();
    insert_classes();
    insert_assignments();
    enroll();  
}
else
{
    //die($dberror);
}

function drop_tables()
{
   query("drop table User; drop table Class; drop table Assignment; drop table Enrollment; drop table Log; drop table Grade;");
}

function insert_users()
{
    $pass = crypt("asdf",'$5$thisisthesalt!!!'); //IMPORTANT: $5$ indicates that SHA-256 is to be used.  Salt MUST be in single quotes.
    $pass2 = crypt("cs1630", '$5$thisisthesalt!!!');
    query("
        insert into User values ('0', 'Rafe Zero', 'rhc8@pitt.edu', 'student', '$pass', 'thisisthesalt!!!');
        insert into User values ('1', 'Generic Student', 'rafael.colton+student@gmail.com', 'student', '$pass', 'thisisthesalt!!!');
        insert into User values ('2', 'Rafe Two', 'rafael.colton+two@gmail.com', 'student', '$pass', 'thisisthesalt!!!');
        insert into User values ('3', 'Rafe Three', 'rafael.colton+three@gmail.com', 'student', '$pass', 'thisisthesalt!!!');
        insert into User values ('4', 'Rafe Four', 'rafael.colton+four@gmail.com', 'student', '$pass', 'thisisthesalt!!!');
        insert into User values ('10', 'Generic Teacher', 'rafael.colton+teacher@gmail.com', 'teacher', '$pass', 'thisisthesalt!!!');
        insert into User values ('11', 'Rafe Eleven', 'rafael.colton+eleven@gmail.com', 'teacher', '$pass', 'thisisthesalt!!!');
        insert into User values ('12', 'Rafe Twelve', 'rafael.colton+twelve@gmail.com', 'teacher', '$pass', 'thisisthesalt!!!');
        insert into User values ('13', 'Rafe Thirteen', 'rafael.colton+thirteen@gmail.com', 'teacher', '$pass', 'thisisthesalt!!!');
        insert into User values ('14', 'Rafe Fourteen', 'rafael.colton+fourteen@gmail.com', 'teacher', '$pass', 'thisisthesalt!!!');
        insert into User values ('15', 'Generic Admin', 'rafael.colton+admin@gmail.com', 'admin', '$pass', 'thisisthesalt!!!');
        insert into User values ('16', 'Admin', 'cs1630_academy_project@googlegroups.com', 'admin', '$pass2','thisisthesalt!!!');
        ");
}

function insert_classes()
{
    query("
        insert into Class values (0, 'Class Zero', 10, 'rhc8@pitt.edu', '100', 'Description of class zero.');
        insert into Class values (1, 'Class One', 11, 'rhc8@pitt.edu', '101', 'Description of class one.');
        insert into Class values (2, 'Class Two', 12, 'rhc8@pitt.edu', '102', 'Description of class two.');
        insert into Class values (3, 'Class Three', 13, 'rhc8@pitt.edu', '103', 'Description of class three.');
        insert into Class values (4, 'Class Four', 14, 'rhc8@pitt.edu', '104', 'Description of class four.');
        ");
}

function insert_assignments()
{
    $due = "2012-03-25 23:45:00";
    $late = "2012-07-25 23:59:59";
    $assigned = "2012-03-11 12:00:00";
    query("
        insert into Assignment values (0, 0, 'Assignment 0 for Class 0', '$assigned', 'no description', '$due', '$late', 1, 3);
        insert into Assignment values (5, 0, 'Assignment 5 for Class 0', '$assigned', 'no description', '$due', '$late', 1, 3);
        insert into Assignment values (1, 1, 'Assignment 1 for Class 1', '$assigned', 'no description', '$due', '$late', 1, 3);
        insert into Assignment values (2, 2, 'Assignment 2 for Class 2', '$assigned', 'no description', '$due', '$late', 1, 3);
        insert into Assignment values (3, 3, 'Assignment 3 for Class 3', '$assigned', 'no description', '$due', '$late', 1, 3);
        insert into Assignment values (4, 4, 'Assignment 4 for Class 4', '$assigned', 'no description', '$due', '$late', 1, 3);
        ");
}

function enroll()
{
    query("
        insert into Enrollment values (0, 0);
        insert into Enrollment values (0, 1);
        insert into Enrollment values (0, 2);
        insert into Enrollment values (0, 3);
        insert into Enrollment values (0, 4);
        insert into Enrollment values (1, 1);
        insert into Enrollment values (2, 2);
        insert into Enrollment values (3, 3);
        insert into Enrollment values (4, 4);
        insert into Enrollment values (0, 10);
        insert into Enrollment values (1, 10);
        insert into Enrollment values (3, 10);
        insert into Enrollment values (4, 10);
        ");
}

function query($query)
{
    global $db;
    $result = $db->queryExec($query, $error);
    if (!$result || $error)
    {
        return false;
        //die("Query error: $error");
    }
    else
    {
        return true;
    }
}

?>
