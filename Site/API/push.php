<?php
	session_start();
	include('includes/config.php');
	error_reporting(0);
	include 'includes/language.php';
	
	if(strlen($_SESSION['login'])==0)
	{ 
		header('location:index.php');
	}
	else{
		$adminid=$_SESSION['userid'];
		$id = intval($_GET['scid']);
		
		
		
		
		if(isset($_POST['updatee']))
		{
			
			
			$detail = addslashes($_POST['detail']);
			$title = addslashes($_POST['title']);
			
			$file_name = $_FILES['image']['name'];
			$file_tmp = $_FILES['image']['tmp_name'];
			$temp = explode(".", $_FILES["image"]["name"]);
			$file_name = 'push' . round(microtime(true)) . '.' . end($temp);
			
			
			if($production=="1"){
				$file_type=$_FILES['image']['type'];
				$result = $s3->putObject([
				'Bucket' => $bucketName,
				'Key'    => $file_name,
				'ContentType'    => $file_type,
				'SourceFile' => $file_tmp			
				]);
				
			}
			else{
				
				move_uploaded_file($file_tmp, $imgloc . $file_name);
			}
			
			
			
			$imgurl=$imgloc.$file_name;
			require_once('firebase.php');
			sendMessage($title,$detail,$imgurl,"newsfeed");
			$msg = "Sent".$imgloc.$file_name;
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
												<li class="breadcrumb-item active"><?php mylan("Notification ","إشعار "); ?></li>
											</ol>
										</div>
										<h4 class="page-title"><?php mylan("Notification ","إشعار "); ?></h4>
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
											<h4 class="header-title"></h4>
											
										</p>
										
										
										<form class="form" method="post" enctype="multipart/form-data">
											<div class="row">
												
												
												<div class="col-12">
													<div class="form-group mb-3">
														<label for="validationCustom01"><?php mylan("Title ","عنوان "); ?> </label>
														<input type="text" class="form-control" name="title"  required>
													</div>
												</div>
												
												
												
												<div class="col-12">
													<div class="form-group mb-3">
														<label for="validationCustom01"><?php mylan("Detail ","التفاصيل "); ?> </label>
														<input type="text" class="form-control" name="detail" required>
													</div>
												</div>
												
												
												
												
												
												
												<div class="col-6">
													<div class="form-group mb-3">
														<label for="validationCustom03"><?php mylan("Image ","صورة "); ?></label>
														<input type="file" class="form-control-file" id="exampleInputFile" name="image"><br><br>
														
														
													</div>
												</div>
												
												
												
											</div>
											<button name="updatee" class="btn btn-primary" type="submit"><?php mylan("Send ","إرسال "); ?></button>
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