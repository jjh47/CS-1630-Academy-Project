<?

$db = $_SESSION["db"];

if ($db)
{
    /*drop_tables();
    query("
            create table 'User' (
                'user_id' int not null,
                'name' text not null,
                'email' text not null,
                'user_type' text not null,
                'password' text not null,
                'salt' text not null,
                primary key ('user_id'),
                unique (email, user_type)
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
                'submission_time' text not null,
                'comment' text,
                unique ('assignment_id','course_id','user_id','submission_time')
            )
        ");
    echo "done";*/
}
else
{
    die($dberror);
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

function drop_tables()
{
    //global $db;
    //$db->queryExec("drop table User; drop table Class; drop table Assignment; drop table Enrollment; drop table Log;");
}

?>
