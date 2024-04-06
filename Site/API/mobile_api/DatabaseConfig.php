<?php
	include('../includes/database.php');
	
	
	
	header('Content-Type: application/json');
	
	
	$conn = new mysqli(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
	$conn->set_charset("UTF8");
	header('Content-Type: application/json');
	header('Access-Control-Allow-Origin:*');
	header('Access-Control-Allow-Headers:Origin, Content-Type, Cookie, X-CSRF-TOKEN, Accept, Authorization, X-XSRF-TOKEN, Access-Control-Allow-Origin');
	
 
	$production="0";
	$imgloc="../../mec/";
	
	
	error_reporting(0);
	
	
	function itemssql($column, $value){
		
		$result='';
		
		$value_array=explode(",",$value);
		if($value!="" ){
			$result=" AND (";
			for($i=0;$i<sizeof($value_array);$i++ ){
				$result=$result.$column." = '".$value_array[$i]. "' OR ";
			}
			
			$result=substr($result, 0, -4)." )";
			
		}
		
		return $result;
		
	}
	
	function likesql($column, $value){
		
		$result='';
		
		$value_array=explode(",",$value);
		if($value!="" ){
			$result=" AND (";
			for($i=0;$i<sizeof($value_array);$i++ ){
				$result=$result.$column." LIKE '%".$value_array[$i]. "%' OR ";
			}
			
			$result=substr($result, 0, -4)." )";
			
		}
		
		return $result;
		
	}
	
	
	function singlesql($column, $value){
		
		$result='';
		
		if($value=="0" || $value==""){
			$result="";
			}else{	
			$result=" AND ".$column."='".$value."'";
		}
		
		return $result;
		
	}
	
	function emptysql($column, $value){
		
		$result='';
		
		if($value=="0" || $value==""){
			$result="";
			}else{	
			$result=" AND ".$column."='".$value."'";
		}
		
		return $result;
		
	}
	
	
	
	

	
	
?>