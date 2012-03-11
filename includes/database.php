<?

$db = $_SESSION["db"];

if ($db)
{
    drop_tables();
    query("
            create table 'User' (
                'user_id' int not null,
                'username' text not null,
                'email' text not null,
                'usertype' text not null,
                'password' text not null,
                'salt' text not null,
                primary key ('user_id'),
                unique (email)
            )
        ");
    query("
            create table 'Class' (
                'class_id' int not null,
                'class_name' int not null,
                'instructor_id' int not null,
                'instructor_email' text not null,
                'room' text,
                'description' text,
                primary key ('class_id')
            )
        ");
    query("
            create table 'Assignment' (
                'assignment_id' int not null,
                'class_id' int not null,
                'title' text not null,
                'date_assigned' text not null,
                'description' text,
                'due_date' text not null,
                'late_due_date' text not null,
                'num_files_required' int,
                primary key ('assignment_id'),
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
                'submission_id' int not null primary key,
                'assignment_id' int not null,
                'course_id' int not null,
                'user_id' int not null,
                'user_name' text not null,
                'submission_time' text not null,
                'successful' int(1) not null,
                'comment' text,
                unique ('assignment_id','course_id','user_id','submission_time')
            )
        ");
    insert_users();
    insert_classes();
    enroll();  
}
else
{
    die($dberror);
}

function drop_tables()
{
    query("drop table User; drop table Class; drop table Assignment; drop table Enrollment; drop table Log;");
}

function insert_users()
{
    $pass = crypt("asdf",'$5$thisisthesalt!!!'); //IMPORTANT: $5$ indicates that SHA-256 is to be used.  Salt MUST be in single quotes.
    query("
        insert into User values ('0', 'Rafe Zero', 'rhc8@pitt.edu', 'student', '$pass', 'thisisthesalt!!!');
        insert into User values ('1', 'Rafe One', 'rafael.colton+one@gmail.com', 'student', '$pass', 'thisisthesalt!!!');
        insert into User values ('2', 'Rafe Two', 'rafael.colton+two@gmail.com', 'student', '$pass', 'thisisthesalt!!!');
        insert into User values ('3', 'Rafe Three', 'rafael.colton+three@gmail.com', 'student', '$pass', 'thisisthesalt!!!');
        insert into User values ('4', 'Rafe Four', 'rafael.colton+four@gmail.com', 'student', '$pass', 'thisisthesalt!!!');
        insert into User values ('10', 'Rafe Ten', 'rafael.colton+ten@gmail.com', 'teacher', '$pass', 'thisisthesalt!!!');
        insert into User values ('11', 'Rafe Eleven', 'rafael.colton+eleven@gmail.com', 'teacher', '$pass', 'thisisthesalt!!!');
        insert into User values ('12', 'Rafe Twelve', 'rafael.colton+twelve@gmail.com', 'teacher', '$pass', 'thisisthesalt!!!');
        insert into User values ('13', 'Rafe Thirteen', 'rafael.colton+thirteen@gmail.com', 'teacher', '$pass', 'thisisthesalt!!!');
        insert into User values ('14', 'Rafe Fourteen', 'rafael.colton+fourteen@gmail.com', 'teacher', '$pass', 'thisisthesalt!!!');
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
        ");
}

function query($query)
{
    global $db;
    $result = $db->queryExec($query, $error);
    if (!$result || $error)
    {
        die("Query error: $error");
    }
    else
    {
        return true;
    }
}

?>
