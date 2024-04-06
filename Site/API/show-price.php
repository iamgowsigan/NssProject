<?php
	session_start();
	include('includes/config.php');
	error_reporting(0);
	include 'includes/language.php';
	include 'includes/dbhelp.php';
include 'includes/formdata.php';
	
	$cat = $_GET['cate'];

	$shopcategory = Selectdata("SELECT * FROM products WHERE p_id='$cat'");
	forminput('Original Price'.$cat.' __', 'sc_id', $cat, 'disable', '12', 'text');

?>
 
