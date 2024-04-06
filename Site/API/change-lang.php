<?php
	session_start();
	include("includes/config.php");
	
	$getLang = $_GET['lan'];
	$getmydb = $_GET['mydb'];
	$getLable = $_GET['lable'];
	$getImage = $_GET['limg'];
	$getPage = $_GET['page'];
	
	$_SESSION['lan']=$getLang;
	$_SESSION['mydb']=$getmydb;
	
	$_SESSION['mylable']=$getLable;
	$_SESSION['myimg']=$getImage;

	
?>
<script language="javascript">
	document.location="<?php echo htmlentities($getPage); ?>";
</script>
