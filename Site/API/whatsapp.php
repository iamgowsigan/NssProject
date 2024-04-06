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
		
		
		$arrContextOptions=array(
		"ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
		),
		);  
		
		$response = file_get_contents("https://whatsapp.myappstores.com/api/qrCodeLink?token=aaaaaaa", false, stream_context_create($arrContextOptions));
		$responseArray = json_decode($response, true);
		

		
		
	?>
	<!DOCTYPE html>
	<html lang="en">
		
		<head>
		<meta http-equiv="refresh" content="3">
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
												<li class="breadcrumb-item"><a href="javascript: void(0);"><?php mylan("Dashboard ","لوحة القيادة "); ?></a></li>
												<li class="breadcrumb-item active"><?=$projectname;?></li>
											</ol>
										</div>
										<h4 class="page-title"><?php mylan("Whatsapp ","ال WhatsApp "); ?></h4>
									</div>
								</div>
							</div>     
							<div class="row"><!-- container Start-->
								
													<!--/ Table List-->
		
								<div class="col-12">
									<div class="card">
										<div class="card-header">
											<h4 class="card-title"><?php mylan("Whatsapp Device ","جهاز Whatsapp "); ?></h4>
										</div>
										<div class="card-body">
											<?php if($responseArray['status']=='success'){ 
											
												$getData=$responseArray['data'];
												$getPhone=$getData['info'];
												
												
												?>
												<h3><?php mylan("Already Connected ","متصل بالفعل "); ?></h3><br><br>
												
												<p><?php mylan(" Pushname"," بوشنام"); ?> : <b><?=$getPhone['Pushname'];?></b></p>
												<p><?php mylan("Platform ","برنامج "); ?> : <b><?=$getPhone['Platform'];?></b></p>
												<p><?php mylan("Battery "," بطارية"); ?> : <b><?=$getPhone['Battery'];?>%</b></p>
												
												<?php }else{ ?>
												<h3><?php mylan("No Device Connected "," لا يوجد جهاز متصل"); ?></h3><br><br>
												<p><?php mylan("Scan QR code to connect new device ","امسح رمز الاستجابة السريعة لتوصيل جهاز جديد "); ?></p>
												<img src="<?=$responseArray['data'];?>" height="300px" width="300px" style="object-fit:contain" onerror="this.src='assets/images/banner.png'">
											<?php } ?>
											
											
											
										</div> 
										
									</div>
								</div>
						<!--/ Table List -->
								
								
								
							</div> <!-- container End-->
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
				
				
				<!-- demo:js -->
				<?php include 'dashboard-chart.php';?>
				
				<!-- end demo js-->
			</body>
		</html>
	<?php } ?>																																								