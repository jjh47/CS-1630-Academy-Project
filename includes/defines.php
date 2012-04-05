<?

$testing = "Rafe";

if($testing == "Ping"){
	define("DB_PATH","C:/xampp/htdocs/CS1630/CS-1630-Academy-Project/pages/academyprojectdb"); //path to the database file
	define("HOME_DIR","http://localhost/cs1630/CS-1630-Academy-Project/");
	define("BASE_PATH","C:/xampp/htdocs/CS1630/CS-1630-Academy-Project/");
	define("CLASS_PATH","C:/xampp/htdocs/CS1630/CS-1630-Academy-Project/");
	define("GSCRIPT_PATH", "C:/xampp/htdocs/CS1630/CS-1630-Academy-Project/pages/script_grade.php");
}
else if ($testing == "web")
{
	define("DB_PATH","/afs/cs.pitt.edu/projects/marai/gfx3d/cs1630/2012/Academy/academyprojectdb"); //path to the database file
	define("HOME_DIR","https://vis.cs.pitt.edu/webtest/cs1630Academy/");
	define("BASE_PATH","/afs/cs.pitt.edu/projects/marai/gfx3d/cs1630/2012/Academy/incoming/");
	define("CLASS_PATH","/afs/cs.pitt.edu/projects/marai/gfx3d/cs1630/2012/Academy/incoming/");
	define("GSCRIPT_PATH", "/afs/cs.pitt.edu/projects/vis/visweb/webtest/cs1630Academy/pages/script_grade.php");
	define("MODE","dev");
	define("LATE_FILE_NAME", "late.txt");//the file contains a list of late files
	define("RESULT_FILE_NAME", "results.txt");
}

else if ($testing == "Rafe")
{
	define("HOME_DIR","http://cs1630academy/");
	define("DB_PATH","academyprojectdb");
	define("BASE_PATH","/Users/rabbits1756/Documents/Classes/Current/CS 1630/CS-1630-Academy-Project/");
	define("CLASS_PATH","/Users/rabbits1756/Documents/Classes/Current/CS 1630/CS-1630-Academy-Project/");
	define("GSCRIPT_PATH", "http://cs1630academy/pages/script_grade.php");
}

else if($testing == "James")
{
	define("HOME_DIR","http://localhost:8888/");
	define("DB_PATH","/Users/jjh47/Documents/CS-1630-Academy-Project/academyprojectdb");
	define("BASE_PATH","/Users/jjh47/Documents/CS-1630-Academy-Project/");
	define("CLASS_PATH","/Users/jjh47/Documents/CS-1630-Academy-Project/");
	define("GSCRIPT_PATH", "/Users/jjh47/Documents/CS-1630-Academy-Project/pages/");	
}
else if($testing == "Brian"){
	define("DB_PATH","D:/xampp/htdocs/CS-1630-Academy-Project/pages/academyprojectdb"); //path to the database file
	define("HOME_DIR","http://localhost/CS-1630-Academy-Project/");
	define("BASE_PATH","D:/xampp/htdocs/CS-1630-Academy-Project/");
	define("CLASS_PATH","D:/xampp/htdocs/CS-1630-Academy-Project/");
	define("GSCRIPT_PATH", "D:/xampp/htdocs/CS-1630-Academy-Project/pages/script_grade.php");
}



	
