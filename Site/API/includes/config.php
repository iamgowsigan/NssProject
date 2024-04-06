<?php
//require_once 'vendor/autoload.php';
include('database.php');

$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
error_reporting(0);

$imgloc="../mec/";
	$fileloc=$_SERVER['SERVER_NAME']."/FlutterProjects/NssProject/Site/mec/";
	$production="0";

// Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$adminid = $_SESSION['userid'];
$username = $_SESSION['username'];
$adminname = $_SESSION['adminname'];
$login = $_SESSION['login'];
$adminpower = $_SESSION['power'];
$projectname = "NSS";
$projectcode = "NSS";


$yesNoRaw = '[
	
	{"id": "1", "lable": "Yes", "lable_arab": "Yes"},
	{"id": "2", "lable": "No", "lable_arab": "No"}
	]';
$yesNolist = json_decode($yesNoRaw, true);

$donateRaw = '[
	
	{"id": "D", "lable": "Donated"},
	{"id": "N", "lable": "Not Donated"}
	]';
$donatedList = json_decode($donateRaw, true);

$statusRaw = '[
	
	{"id": "0", "lable": "Absent"},
	{"id": "1", "lable": "Present"}
	]';
$statusList = json_decode($statusRaw, true);

$genderRaw = '[
	
	{"id": "male", "lable": "male"},
	{"id": "female", "lable": "female"}
	]';
$genderList = json_decode($genderRaw, true);


$languageraw = '[
	
	{"id": "1", "lable": "English", "lable_arab": "English"},
	{"id": "2", "lable": "Arabic", "lable_arab": "عربي"},
	{"id": "3", "lable": "French", "lable_arab": "فرنسي"}
	]';
$languagelist = json_decode($languageraw, true);


$mediascreenraw = '[
	
	{"id": "1", "lable": "English", "lable_arab": "English"},
	{"id": "2", "lable": "Arabic", "lable_arab": "عربي"},
	{"id": "3", "lable": "French", "lable_arab": "فرنسي"}
	]';
$mediascreen = json_decode($langumediascreenrawageraw, true);



?>