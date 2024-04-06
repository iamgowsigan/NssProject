<?php
	
	session_start();
	$GetLocal=$_SESSION['lan'];
	$mydb = $_SESSION['mydb'];
	
	$mylangLable=$_SESSION['mylable'];
	$mylangImg=$_SESSION['myimg'];
	
	
	if($mylangLable==""){
		$mylangLable="English";
		$mylangImg="us";
	}

	if($GetLocal==""){
		$_SESSION['lan']="En";

	}
	
	function mylan($eng,$ara){
		
		$lan = $_SESSION['lan'];
		if($lan=="En" || $lan==""){
			echo $eng;
			}else{
			echo $ara;
		}
		
		
	}
	
	
?>