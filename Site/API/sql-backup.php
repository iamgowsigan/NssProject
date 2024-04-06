<?php
	session_start();
	include('includes/config.php');
	error_reporting(0);
	include 'includes/language.php';
	if(strlen($_SESSION['login'])==0 && $_SESSION['power']==0)
	{ 
		header('location:index.php');
	}
	else{
		$adminid=$_SESSION['userid'];
		if (isset($_POST['dozip'])) {
			
			$tables = array();
			$result = mysqli_query($con,"SHOW TABLES");
			while ($row = mysqli_fetch_row($result)) {
				$tables[] = $row[0];
			}
			
			$return = '';
			
			foreach ($tables as $table) {
				$result = mysqli_query($con, "SELECT * FROM ".$table);
				$num_fields = mysqli_num_fields($result);
				
				$return .= 'DROP TABLE '.$table.';';
				$row2 = mysqli_fetch_row(mysqli_query($con, 'SHOW CREATE TABLE '.$table));
				$return .= "\n\n".$row2[1].";\n\n";
				
				for ($i=0; $i < $num_fields; $i++) { 
					while ($row = mysqli_fetch_row($result)) {
						$return .= 'INSERT INTO '.$table.' VALUES(';
						for ($j=0; $j < $num_fields; $j++) { 
							$row[$j] = addslashes($row[$j]);
							if (isset($row[$j])) {
							$return .= '"'.$row[$j].'"';} else { $return .= '""';}
							if($j<$num_fields-1){ $return .= ','; }
						}
						$return .= ");\n";
					}
				}
				$return .= "\n\n\n";
				
			}
			
			
			$handle = fopen('databasebackup.sql', 'w+');
			fwrite($handle, $return);
			fclose($handle);
			
			$msg="Database Backup success";
			
			
			
			
		}
		
		if (isset($_POST['submitsubcat'])) {
			$path = "databasebackup.sql";
			if (file_exists($path)) {
				unlink($path);
				$GLOBALS['status'] = array('error' => 'success: File Deleted.');
				$msg="Backup Deleted";
				} else {
				$GLOBALS['status'] = array('error' => 'Error: Backup not exist.');
				$warning="No Backup found";
			}
			
		}
		
		
	?>
	
	<!DOCTYPE html>
	<html lang="en">
		
		<head>
			<?php include 'includes/head.php';?>
		</head>	
		<body class="loading" data-layout-config='{"leftSideBarTheme":"<?=$menumode;?>","layoutBoxed":false, "leftSidebarCondensed":<?=$iconmode;?>, "leftSidebarScrollable":false,"darkMode":<?=$bodymode;?>, "showRightSidebarOnStart": false}'>
			<div class="wrapper">		
				<?php include 'includes/left-navigation.php';?>
				<div class="content-page">
					<div class="content">
						<?php include 'includes/top-menu.php';?>
						<div class="container-fluid">
							<div class="row">
								<div class="col-12">
									<div class="page-title-box">
										<div class="page-title-right">
											<ol class="breadcrumb m-0">
												<li class="breadcrumb-item"><a href="javascript: void(0);"><?=$projectname;?></a></li>
												<li class="breadcrumb-item"><a href="dashboard.php"><?php mylan("Dashboard ","لوحة القيادة "); ?></a></li>
												<li class="breadcrumb-item active"><?php mylan("SQL ","SQL "); ?></li>
											</ol>
										</div>
										<h4 class="page-title"><?php mylan("Project Info ","معلومات المشروع "); ?></h4>
									</div>
								</div>
							</div>   
							<div class="row"><!-- container Start-->
								<?php include 'includes/warnings.php';?>
								
								<!-- --------------->							
								<!-- container START-->							
								<!------------------>		
								
								<div class="col-lg-12">
									<div class="card">
										<div class="card-body">
											<p class="status status--<?php echo strtoupper(key($GLOBALS['status'])); ?>">
												<?php mylan("Status ","حالة "); ?>: <?php echo reset($GLOBALS['status']); ?><br />
											<span class="small"><?php mylan("Processing Time ","وقت المعالجة "); ?>: <?php echo $time; ?> <?php mylan("seconds ","ثواني "); ?></span></p>
											
											
											<form action="" method="POST">
												<fieldset>
													
													<label for="zippath"></label>
													<input type="hidden" name="zippath" class="form-field" />
													<p class="info"><?php mylan("Before Backup, Delete old backup file ","قبل النسخ الاحتياطي ، احذف ملف النسخ الاحتياطي القديم "); ?></p>
													
													
													<?php $path = "databasebackup.sql";
														if (file_exists($path)) {  ?>
														
														<button type="submit" class="btn btn-danger "
														name="submitsubcat"><?php mylan("Delete Old Backup ","حذف النسخة الاحتياطية القديمة "); ?></button>
														
														<a class="btn btn-success" href="databasebackup.sql"><?php mylan("Download Backup ","تنزيل النسخ الاحتياطي "); ?></a>
														
														
														<?php } else{ ?>
														
														
														<input type="submit" name="dozip" class="btn btn-primary btn-rounded"
														value="Backup Now" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
														
													<?php } ?>
													
												</fieldset>
											</form>
											
											
											
										</div> <!-- end card-body-->
									</div> <!-- end card-->
								</div> <!-- end col-->	
								
								
								
								
								
								
								
								
								<!-- --------------->							
								<!-- container End-->							
								<!------------------>							
							</div> 
						</div> 
						<!-- Footer Start -->
						<?php include 'includes/footer.php';?>
					</div>
				</div>
				<!-- Right Sidebar -->
				<?php include 'includes/right-bar.php';?>
				<!-- bundle -->
				<script src="assets/js/vendor.min.js"></script>
				<script src="assets/js/app.min.js"></script>
				
				<!-- Apex js -->
				<script src="assets/js/vendor/apexcharts.min.js"></script>
				
				<!-- Todo js -->
				<script src="assets/js/ui/component.todo.js"></script>
				
				<!-- demo app -->
				<script src="assets/js/pages/demo.dashboard-crm.js"></script>
				<!-- end demo js-->
			</body>
		</html>
	<?php } ?>				