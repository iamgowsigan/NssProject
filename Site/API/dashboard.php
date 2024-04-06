<?php
session_start();
include('includes/config.php');
error_reporting(0);
include 'includes/language.php';
include 'includes/dbhelp.php';

if (strlen($_SESSION['login']) == 0) {
	header('location:index.php');
} else {

?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<?php include 'includes/head.php'; ?>
	</head>

	<body class="loading" data-layout-config='{"leftSideBarTheme":"<?= $menumode; ?>","layoutBoxed":false, "leftSidebarCondensed":<?= $iconmode; ?>, "leftSidebarScrollable":false,"darkMode":<?= $bodymode; ?>, "showRightSidebarOnStart": false}'>
		<div class="wrapper">
			<?php include 'includes/left-navigation.php'; ?>
			<div class="content-page">
				<div class="content">
					<?php include 'includes/top-menu.php'; ?>
					<div class="container-fluid">
						<div class="row">
							<div class="col-12">
								<div class="page-title-box">
									<div class="page-title-right">
										<ol class="breadcrumb m-0">
											<li class="breadcrumb-item"><a href="javascript: void(0);"><?= $projectname; ?></a></li>
											<li class="breadcrumb-item"><a href="javascript: void(0);"><?php mylan("Dashboard ", "لوحة القيادة "); ?></a></li>
											<li class="breadcrumb-item active"><?= $projectname; ?></li>
										</ol>
									</div>
									<h4 class="page-title"><?php mylan("INSIGHTS ", " أفكار"); ?></h4>
								</div>
							</div>
						</div>
						<div class="row"><!-- container Start-->

							<?php





 
	 

							?>
		 




					 




						</div> <!-- container End-->
					</div>
					<!-- Footer Start -->
					<?php include 'includes/footer.php'; ?>
				</div>
			</div>


			<!-- Right Sidebar -->
			<?php include 'includes/right-bar.php'; ?>
			<!-- bundle -->
			<script src="assets/js/vendor.min.js"></script>
			<script src="assets/js/app.min.js"></script>

			<!-- Apex js -->
			<script src="assets/js/vendor/apexcharts.min.js"></script>

			<!-- Todo js -->
			<script src="assets/js/ui/component.todo.js"></script>



			<!-- end demo js-->
	</body>

	</html>
<?php } ?>