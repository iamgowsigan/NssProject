<?php
session_start();
include('includes/config.php');
error_reporting(0);
include 'includes/language.php';

if (strlen($_SESSION['login']) == 0 && $_SESSION['power'] == 0) {
	header('location:index.php');
} else {
	$adminid = $_SESSION['userid'];


	?>

	<!DOCTYPE html>
	<html lang="en">

	<head>
		<?php include 'includes/head.php'; ?>
	</head>

	<body class="loading"
		data-layout-config='{"leftSideBarTheme":"<?= $menumode; ?>","layoutBoxed":false, "leftSidebarCondensed":<?= $iconmode; ?>, "leftSidebarScrollable":false,"darkMode":<?= $bodymode; ?>, "showRightSidebarOnStart": false}'>
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
											<li class="breadcrumb-item"><a href="javascript: void(0);">
													<?= $projectname; ?>
												</a></li>
											<li class="breadcrumb-item"><a href="javascript: void(0);">
													<?php mylan("Dashboard ", " لوحة القيادة"); ?>
												</a></li>
											<li class="breadcrumb-item active">
												<?php mylan("Account ", " الحساب  "); ?>
											</li>
										</ol>
									</div>
									<h4 class="page-title">
										<?php mylan(" Project Info", "معلومات المشروع "); ?>
									</h4>
								</div>
							</div>
						</div>
						<div class="row"><!-- container Start-->
							<?php include 'includes/warnings.php'; ?>

							<!-- --------------->
						<!-- container START-->
							<!------------------>

							<div class="col-lg-12">
								<div class="card">
									<div class="card-body">
										<h4 class="header-title">
											<?php mylan(" PROJECT SUMMARY", "ملخص المشروع "); ?>
										</h4>
										<p class="text-muted font-14">
											Start Date: 11.04-2021<br>
											Launch Date: 24-05-2021</p>


										<h4 class="header-title">Play Store</h4>
										<p class="text-muted font-14">
											Username: aaa@gmail.com
											Password- $aaa@2022</p>



									</div> <!-- end card-body-->
								</div> <!-- end card-->
							</div> <!-- end col-->



							<!-- --------------->
						<!-- container End-->
							<!------------------>
						</div>
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

			<!-- demo app -->
			<script src="assets/js/pages/demo.dashboard-crm.js"></script>
			<!-- end demo js-->
	</body>

	</html>
<?php } ?>