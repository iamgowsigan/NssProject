<?php
session_start();
include('includes/config.php');
error_reporting(0);
include 'includes/language.php';
include 'includes/dbhelp.php';
include 'includes/formdata.php';

if (strlen($_SESSION['login']) == 0) {
	header('location:index.php');
} else {
	$adminid = $_SESSION['userid'];
	$id = intval($_GET['scid']);


	//Update
	if (isset($_POST['update'])) {

		$eid = intval($_GET['eid']);
		$oldimage = $_POST['oldimage'];
		if ($_FILES['image']['name'] == '') {
			$file_name = $oldimage;
		} else {
			$file_name = Uploadimage('splashscreen', 'image');
		}


		$field['app_version'] = addslashes($_POST['app_version']);
		$field['tax'] = addslashes($_POST['tax']);
		$field['otp'] = addslashes($_POST['otp']);
		$field['splashscreen'] = addslashes($_POST['splashscreen']);
		$field['splashscreen_time'] = addslashes($_POST['splashscreen_time']);
		$field['api_translate'] = addslashes($_POST['api_translate']);
		$field['api_currency'] = addslashes($_POST['api_currency']);
		$field['api_whatsapp'] = addslashes($_POST['api_whatsapp']);
		$field['api_otp'] = addslashes($_POST['api_otp']);
		$field['api_map'] = addslashes($_POST['api_map']);
		$field['android_url'] = addslashes($_POST['android_url']);
		$field['ios_url'] = addslashes($_POST['ios_url']);
		$field['otp_email'] = addslashes($_POST['otp_email']);
		$field['otp_email_password'] = addslashes($_POST['otp_email_password']);
		$field['whatsapp'] = addslashes($_POST['whatsapp']);
		$field['website'] = addslashes($_POST['website']);
		$field['email'] = addslashes($_POST['email']);
		$field['app_font'] = addslashes($_POST['app_font']);
		$field['direct_dashboard'] = addslashes($_POST['direct_dashboard']);
		$field['vendor_notification'] = addslashes($_POST['vendor_notification']);
		$field['local_delivery_cost'] = addslashes($_POST['local_delivery_cost']);
		$field['local_delivery_time'] = addslashes($_POST['local_delivery_time']);
		$field['international_delivery_cost'] = addslashes($_POST['international_delivery_cost']);
		$field['international_delivery_time'] = addslashes($_POST['international_delivery_time']);

		$field['local_remote_delivery_cost'] = addslashes($_POST['local_remote_delivery_cost']);
		$field['local_remote_delivery_time'] = addslashes($_POST['local_remote_delivery_time']);

		$field['enable_cod'] = addslashes($_POST['enable_cod']);
		$field['enable_online'] = addslashes($_POST['enable_online']);
		$field['vendor_share_percentage'] = addslashes($_POST['vendor_share_percentage']);
		$field['splashscreen'] = $file_name;

		$res = Updatedata("app_setting", $field, "s_id =1");
		if ($res != 0) {

			$msg = "Success ";
		} else {
			$error = "something error ";
		}
	}
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
											<li class="breadcrumb-item"><a href="dashboard.php">
													<?php mylan("Dashboard ", " لوحة القيادة"); ?>
												</a></li>
											<li class="breadcrumb-item active">
												<?php mylan(" Settings", "إعدادات "); ?>
											</li>
										</ol>
									</div>
									<h4 class="page-title">
										<?php mylan("App Settings ", " إعدادات التطبيقات"); ?>
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
										<h4 class="header-title"></h4>

										</p>
										<?php

										$query1 = mysqli_query($con, "Select * from app_setting WHERE s_id=1");
										while ($row = mysqli_fetch_array($query1)) {
											?>

											<form class="form" method="post" enctype="multipart/form-data">
												<div class="row">

													<?php forminput('App Version', 'app_version', $row['app_version'], '', '6', 'number'); ?>

													<?php forminput('Tax', 'tax', $row['tax'], '', '6', 'text'); ?>

													<?php forminput('OTP', 'otp', $row['otp'], '', '6', 'text'); ?>

													<?php forminput('Splash Screen Time', 'splashscreen_time', $row['splashscreen_time'], '', '6', 'text'); ?>

													<?php forminput('API Translate', 'api_translate', $row['api_translate'], '', '6', 'text'); ?>

													<?php forminput('API Currency', 'api_currency', $row['api_currency'], '', '6', 'text'); ?>

													<?php forminput('API Whatsapp', 'api_whatsapp', $row['api_whatsapp'], '', '6', 'text'); ?>

													<?php forminput('API OTP', 'api_otp', $row['api_otp'], '', '6', 'text'); ?>

													<?php forminput('API Map', 'api_map', $row['api_map'], '', '6', 'text'); ?>

													<?php forminput('Android URL', 'android_url', $row['android_url'], '', '6', 'text'); ?>

													<?php forminput('IOS URL', 'ios_url', $row['ios_url'], '', '6', 'text'); ?>

													<?php forminput('OTP Email', 'otp_email', $row['otp_email'], '', '6', 'text'); ?>

													<?php forminput('OTP Email Password', 'otp_email_password', $row['otp_email_password'], '', '6', 'text'); ?>

													<?php forminput('Whatsapp', 'whatsapp', $row['whatsapp'], '', '6', 'text'); ?>

													<?php forminput('Website', 'website', $row['website'], '', '6', 'text'); ?>

													<?php forminput('Email', 'email', $row['email'], '', '6', 'text'); ?>


													<?php forminput('Local Delivery Cost', 'local_delivery_cost', $row['local_delivery_cost'], '', '6', 'text'); ?>
													<?php forminput('Local Delivery Time', 'local_delivery_time', $row['local_delivery_time'], '', '6', 'text'); ?>
													<?php forminput('Local Remote Location Delivery Cost', 'local_remote_delivery_cost', $row['local_remote_delivery_cost'], '', '6', 'text'); ?>
													<?php forminput('Local Remote Location Delivery Time', 'local_remote_delivery_time', $row['local_remote_delivery_time'], '', '6', 'text'); ?>
													<?php forminput('International Delivery Cost', 'international_delivery_cost', $row['international_delivery_cost'], '', '6', 'text'); ?>
													
													
													<?php forminput('International Delivery Time', 'international_delivery_time', $row['international_delivery_time'], '', '6', 'text'); ?>
													
													<?php formselect('Enable COD', 'enable_cod', $yesNolist, '', '6', $row['enable_cod'], 'id', 'lable'); ?>
													<?php formselect('Enable Online Pay', 'enable_online', $yesNolist, '', '6', $row['enable_online'], 'id', 'lable'); ?>

													
													
													<?php forminput('Vendor Share', 'vendor_share_percentage', $row['vendor_share_percentage'], '', '6', 'text'); ?>

													<?php forminput('App Font', 'app_font', $row['app_font'], '', '6', 'text'); ?>

													<?php formselect('Direct Dashboard', 'direct_dashboard', $yesNolist, '', '6', $row['direct_dashboard'], 'id', 'lable'); ?>




													<?php formimage('Splash Screen', 'image', $row['splashscreen'], '', '6', 'oldimage', '80', '120', 'cover'); ?>


												</div>
												<button name="update" class="btn btn-primary" type="submit">
													<?php mylan("Update Settings ", "إعدادات التحديث"); ?>
												</button>
											</form>
										<?php } ?>
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