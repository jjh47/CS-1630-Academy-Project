<?
$testing = "web";

if($testing == "Ping"){
	define("DB_PATH","C:/xampp/htdocs/CS1630/CS-1630-Academy-Project/pages/academyprojectdb"); //path to the database file
	define("HOME_DIR","http://localhost/cs1630/CS-1630-Academy-Project/");
	define("BASE_PATH","C:/xampp/htdocs/CS1630/CS-1630-Academy-Project/");
	define("CLASS_PATH","C:/xampp/htdocs/CS1630/CS-1630-Academy-Project/");
	define("GSCRIPT_PATH", "C:/xampp/htdocs/CS1630/CS-1630-Academy-Project/pages/script_grade.php");
}
else if ($testing == "web")
{
	define("DB_PATH","/afs/cs.pitt.edu/projects/vis/visweb/webtest/cs1630Academy/db/academyprojectdb"); //path to the database file
	define("HOME_DIR","http://vis.cs.pitt.edu/webtest/cs1630Academy/");
	define("BASE_PATH","/afs/cs.pitt.edu/projects/vis/visweb/webtest/cs1630Academy/db/");
	define("CLASS_PATH","/afs/cs.pitt.edu/projects/vis/visweb/webtest/cs1630Academy/db/");
	define("GSCRIPT_PATH", "/afs/cs.pitt.edu/projects/vis/visweb/webtest/cs1630Academy/pages/script_grade.php");
}

else
{
//BASE_PATH and CLASS_PATH
	define("HOME_DIR","http://1630academy/");
	define("DB_PATH","academyprojectdb");
	define("BASE_PATH","../");
}

define("MODE","dev");
define("LATE_FILE_NAME", "late.txt");//the file contains a list of late files
define("RESULT_FILE_NAME", "results.txt");


	
