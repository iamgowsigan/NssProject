<?php
session_start();
include('includes/config.php');
//error_reporting(0);
include 'includes/language.php';
include 'includes/dbhelp.php';
include 'includes/formdata.php';
include 'includes/country.php';
if (strlen($_SESSION['login']) == 0) {
	header('location:index.php');
} else {

	if (isset($_POST['save'])) {
		$uid = addslashes($_POST['uid']);

		$_SESSION['uid'] = $uid;

		header("location: user-page.php");
	}


	//ADD
	if (isset($_POST['submit'])) {


		$getUserSetting = Selectdata("SELECT * FROM app_setting LIMIT 1");
		$field['shop_share'] = $getUserSetting[0]['shop_share'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];


		$field['name'] = addslashes($_POST['name']);
		$field['phone'] = addslashes($_POST['phone']);
		$field['email'] = addslashes($_POST['email']);
		$field['country'] = addslashes($_POST['country']);
		$field['city'] = addslashes($_POST['city']);
		$field['profile_pic'] = 'default/user23.jpg';
		$field['email_verify'] = '1';
		$field['gender'] = 'male';



		$checkPhone = Countdata("SELECT * FROM user WHERE phone='$phone'");
		if ($checkPhone == 0) {

			$checkemail = Countdata("SELECT * FROM user WHERE email='$email'");
			if ($checkemail == 0) {

				$res = Insertdata("user", $field);
				if ($res != 0) {
					$msg = "Success ";
					header("location: " . $_SERVER['PHP_SELF'] . "?eid=" . $res . "&&edit=1");
				} else {
					$error = "Deleted ";
				}
			} else {
				$error = "Email Already Exists ";
			}
		} else {
			$error = "Phone Number Already Exists ";
		}
	}

	//Update
	if (isset($_POST['update'])) {

		$eid = intval($_GET['eid']);
		$oldimage = $_POST['oldimage'];
		if ($_FILES['image']['name'] == '') {
			$file_name = $oldimage;
		} else {
			$file_name = Uploadimage('user', 'image');
		}

		$phone = $_POST['phone'];
		$email = $_POST['email'];


		$checkPhone = Countdata("SELECT * FROM user WHERE phone='$phone' AND u_id!='$eid'");
		if ($checkPhone == 0) {
			$checkemail = Countdata("SELECT * FROM user WHERE email='$email' AND u_id!='$eid'");
			if ($checkemail == 0) {

				$field['name'] = addslashes($_POST['name']);
				$field['phone'] = addslashes($_POST['phone']);
				$field['email'] = addslashes($_POST['email']);
				$field['country'] = addslashes($_POST['country']);
				$field['gender'] = addslashes($_POST['gender']);
				$field['city'] = addslashes($_POST['city']);
				$field['profile_pic'] = $file_name;
				$field['dob'] = addslashes($_POST['dob']);
				$field['about'] = addslashes($_POST['about']);
				$field['shop_name'] = addslashes($_POST['shop_name']);
				$field['shop_category'] = addslashes($_POST['shop_category']);
				$field['shop_map'] = addslashes($_POST['shop_map']);
				$field['shop_landline'] = addslashes($_POST['shop_landline']);
				$field['shop_address'] = addslashes($_POST['shop_address']);
				$field['bank_name'] = addslashes($_POST['bank_name']);
				$field['bank_holder'] = addslashes($_POST['bank_holder']);
				$field['bank_ac'] = addslashes($_POST['bank_ac']);
				$field['bank_code'] = addslashes($_POST['bank_code']);
				$field['shop_share'] = addslashes($_POST['shop_share']);

				$res = Updatedata("user", $field, "u_id =$eid");
				if ($res != 0) {

					$msg = "Success ";
				} else {
					$error = "something error ";
				}
			} else {
				$error = "Email Already Exists ";
			}
		} else {
			$error = "Phone Number Already Exists ";
		}
	}


	//delete
	if ($_GET['action'] == 'del' && $_GET['scid']) {
		$id = intval($_GET['scid']);
		$res = Deletedata("delete from user where u_id='$id'");
		header("location: " . $_SERVER['PHP_SELF']);
		$msg = "Deleted";
	}


	//Active
	if ($_GET['action'] == 'status' && $_GET['scid']) {
		$scid = intval($_GET['scid']);
		$val = $_GET['val'];
		$field['shop_live'] = $val;
		$res = Updatedata("user", $field, "u_id=$scid");
		$msg = "Success ";
		//header("location: ".$_SERVER['PHP_SELF']);
	}



	//Screen Operations
	$edit = 0;
	$eid = '';
	if ($_GET['edit'] == 1) {
		$edit = 1;
		$eid = intval($_GET['eid']);
		$_SESSION['pid'] = $eid;
	}

	if ($_GET['edit'] == 0) {
		$edit = 0;
	}

	?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<?php include 'includes/head.php'; ?>
		<link href="assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
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
											<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
											<li class="breadcrumb-item active">User</li>
										</ol>
									</div>
									<h4 class="page-title">Manage User</h4>
								</div>
							</div>
						</div>
						<div class="row"><!-- container Start-->
							<?php include 'includes/warnings.php'; ?>

							<!-- --------------->
						<!-- container START-->
							<!------------------>



							<!--LIST VIEW-->
							<?php if ($edit == 0) { ?>



								<div class="col-lg-12">
									<div class="card">
										<div class="card-body">
											<table data-order='[[ 0, "desc" ]]' id="basic-datatable"
												class="table dt-responsive nowrap w-100">
												<thead>
													<tr>
														<th>ID</th>
														<th>Image</th>
														<th>Name</th>
														<th>Contact</th>
														<th>Active</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php

													$listdata = Selectdata("Select * FROM user WHERE expert='USER'");

													if (sizeof($listdata) == 0) {
														?>
														<tr>
															<td colspan="7" align="center">
																<h3 style="color:black">
																	<?php mylan("No record found ", "لا يوجد سجلات "); ?>
																</h3>
															</td>
														<tr>
															<?php
													} else {
														foreach ($listdata as $row) {

															?>
															<tr>

																<td>
																	<?php text($row['u_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
																</td>

																<td>
																	<?php image($row['profile_pic'], '60', '60', 'cover'); ?>
																</td>

																<td>
																	<?php text($row['name'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?><br>


																	<?php
																	$vendorCategory = Selectdata("SELECT * FROM vendor_category ORDER BY v_id ASC");
																	text(ArrayToName($row['shop_category'], $vendorCategory, 'v_id', 'v_title' . $mydb), $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
																</td>

																<td>
																	<?php text($row['phone'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = 'mdi mdi-cellphone'); ?>
																	<br>
																	<?php text($row['email'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = 'mdi mdi-email'); ?>
																	<br>
																	<?php text(ArrayToName($row['city'], $statelist, 'id', 'name'), $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = 'mdi mdi-map-marker-down'); ?>
																</td>



																<td>
																	<?php active($row['u_active'], $row['u_id']); ?>
																</td>

																<td>
																	<?php action($row['u_id'], 'D', $name = $row['name']); ?>
																</td>

															</tr>
														<?php }
													} ?>
												</tbody>
											</table>
										</div> <!-- end card-body-->
									</div> <!-- end card-->
								</div> <!-- end col-->
							<?php } ?>
							<!--LIST VIEW END-->


							<!--EDIT VIEW-->
							<?php if ($edit == 1) { ?>

								<div class="col-lg-12">
									<a class="btn btn-primary m-2" href="<?= $_SERVER['PHP_SELF']; ?>?edit=0">Back</a>


									<?php
									$listdata = Selectdata("Select * FROM user WHERE u_id=$eid");
									foreach ($listdata as $row) {
										?>

										<form class="form" method="post" enctype="multipart/form-data">
											<div class="col-lg-12">

												<!--Card Start-->
												<div class="card">
													<div class="card-body">

														<h4 class="header-title mb-2">Basic Details</h4>
														<div class="row">

															<?php forminput('Name', 'name', $row['name'], 'required', '12', 'text'); ?>
															<?php forminput('Phone number', 'phone', $row['phone'], 'required', '6', 'text'); ?>
															<?php forminput('Email', 'email', $row['email'], 'required', '6', 'text'); ?>

															<?php formselect('Gender', 'gender', $genderList, 'required', '6', $row['gender'], 'id', 'lable' . $mydb); ?>
															<?php forminput('Date of Birth', 'dob', $row['dob'], '', '6', 'date'); ?>

															<?php
															include 'includes/country.php';
															formselect('Country', 'country', $countrylist, 'required', '6', $row['country'], 'phonecode', 'name'); ?>

															<?php
															include 'includes/country.php';
															formselect('City', 'city', $statelist, 'required', '6', $row['city'], 'id', 'name'); ?>
															<?php formimage('Shop Picture', 'image', $row['profile_pic'], '', '6', 'oldimage', '80', '50', 'cover'); ?>

														</div>
													</div> <!-- end card-body-->
												</div> <!-- end card-->

												<!--Card Start-->
												<div class="card">
													<div class="card-body">

														<h4 class="header-title mb-2">Shop Details</h4>
														<div class="row">

															<?php forminput('Shop Name', 'shop_name', $row['shop_name'], 'required', '12', 'text'); ?>

															<?php
															$vendorCategory = Selectdata("SELECT * FROM vendor_category ORDER BY v_id ASC");
															formselect('Shop Category', 'shop_category', $vendorCategory, 'required', '6', $row['shop_category'], 'v_id', 'v_title' . $mydb); ?>

															<?php forminput('Shop Map', 'shop_map', $row['shop_map'], '', '6', 'text'); ?>

															<?php forminput('Landline / Contact Number', 'shop_landline', $row['shop_landline'], '', '6', 'phone'); ?>

															<?php forminput('Shop Share', 'shop_share', $row['shop_share'], 'required', '6', 'number'); ?>


															<?php formtextarea('Shop Address', 'shop_address', $row['shop_address'], '', '6', 'text'); ?>

															<?php formtextarea('About Shop', 'about', $row['about'], '', '6', 'text'); ?>



														</div>
													</div> <!-- end card-body-->
												</div> <!-- end card-->


												<!--Card Start-->
												<div class="card">
													<div class="card-body">

														<h4 class="header-title mb-2">Bank Details</h4>
														<div class="row">


															<?php forminput('Bank Name', 'bank_name', $row['bank_name'], '', '6', 'text'); ?>

															<?php forminput('A/C Holder Name', 'bank_holder', $row['bank_holder'], '', '6', 'text'); ?>

															<?php forminput('Account Number', 'bank_ac', $row['bank_ac'], '', '6', 'text'); ?>

															<?php forminput('Bank Code', 'bank_code', $row['bank_code'], '', '6', 'text'); ?>






														</div>
													</div> <!-- end card-body-->
												</div> <!-- end card-->


												<div class="col-md-12 pb-4">
													<div class="float-right"
														style="position:fixed;right:0;left:0;bottom:0;width:100%;height: 60px;background:white;">
														<div class="float-right" style="position:fixed;right:30px;bottom:10px;">
															<button name="update" class="btn btn-primary " type="submit">Update
																Changes</button>
														</div>
													</div>
												</div>


											</div> <!-- end col-->

									</div> <!-- end col-->
									</form>

								<?php } ?>

							<?php } ?>

							<!--Edit end-->




							<!-- ADD new-->
							<?php addStart($size = 'xl'); ?>


							<?php forminput('Name', 'name', '', 'required', '12', 'text'); ?>

							<?php
							include 'includes/country.php';
							formselect('Country', 'country', $countrylist, 'required', '6', '', 'phonecode', 'name'); ?>

							<?php
							include 'includes/country.php';
							formselect('City', 'city', $statelist, 'required', '6', '', 'id', 'name'); ?>


							<?php forminput('Phone Number', 'phone', '', 'required', '6', 'phone'); ?>
							<?php forminput('Email', 'email', '', 'required', '6', 'email'); ?>


							<?php addEnd(); ?>

							<!-- ADD new End-->



							<!-- --------------->
						<!-- container End-->
							<!------------------>
						</div>
					</div>
					<!-- Footer Start -->
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
			<script src="assets/js/vendor/jquery.dataTables.min.js"></script>
			<script src="assets/js/vendor/dataTables.bootstrap4.js"></script>
			<script src="assets/js/vendor/dataTables.responsive.min.js"></script>
			<script src="assets/js/vendor/responsive.bootstrap4.min.js"></script>

			<!-- Datatable Init js -->
			<script src="assets/js/pages/demo.datatable-init.js"></script>
			<!-- end demo js-->
	</body>

	</html>
<?php } ?>