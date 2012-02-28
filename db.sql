--So I haven't gotten around to setting up foreign keys.  I just put this together quickly because I'm running terribly behind on an android project for 1635. 

--Feel free to change this if needed.  Sorry it's not of better quality.

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";



CREATE TABLE IF NOT EXISTS `User` (
  `USER_ID` int(11) NOT NULL,
  `NAME` varchar(20) NOT NULL,
  `EMAIL` varchar(20) NOT NULL,
  `ACCOUNT_TYPE` varchar(20) NOT NULL,
  `PASSWORD` varchar(20) NOT NULL,
  PRIMARY KEY (`USER_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `Class` (
  `CLASS_ID` int(11) NOT NULL,
  `CLASS_NAME` int(11) NOT NULL,
  `INSTRUCTOR_ID` int(11) NOT NULL,
  `INSTRUCTOR_EMAIL` varchar(40) NOT NULL,
  `SCHEDULE` VARCHAR(4000) NOT NULL,
  `ROOM` varchar(20) NOT NULL,
  `DESCRIPTION` varchar(20) NOT NULL,
  PRIMARY KEY (`CLASS_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `Assignment` (
  `ASSIGNMENT_ID` int(11) NOT NULL,
  `CLASS_ID` int(11) NOT NULL,
  `TITLE` varchar(255) NOT NULL,
  `DATE_ASSIGNED` date DEFAULT NULL,
  `COMMENT` varchar(4000) DEFAULT NULL,
  `DUE_DATE` date DEFAULT NULL,
  `FILES_REQUIRED` int(11) NOT NULL,
  PRIMARY KEY (`ASSIGNMENT_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `Submission` (
  `SUBMISSION_ID` int(11) NOT NULL,
  `ASSIGNMENT_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `SUBMISSION_TIME` timestamp NOT NULL,
  `FOLDER_NAME` int(11) NOT NULL,
  `GRADE` int(11) NOT NULL,
  `COMMENT` int(11) NOT NULL,
  PRIMARY KEY (`SUBMISSION_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `Enrollment` (
  `CLASS_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  PRIMARY KEY (`CLASS_ID`, `USER_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


