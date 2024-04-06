<?php
session_start();
include ('includes/config.php');
error_reporting(0);
include 'includes/language.php';
include 'includes/dbhelp.php';
include 'includes/formdata.php';
if (strlen($_SESSION['login']) == 0) {
	header('location:index.php');
} else {



	//ADD
	if (isset($_POST['submit'])) {

		$file_name = Uploadimage('activity', 'image');
		$file_name1 = Uploadimage('activity', 'image1');
		$file_name2 = Uploadimage('activity', 'image2');
		$file_name3 = Uploadimage('activity', 'image3');
		$field['a_name'] = addslashes($_POST['a_name']);
		$field['a_desc'] = addslashes($_POST['a_desc']);
		$field['a_location'] = addslashes($_POST['a_location']);
		$field['a_date'] = addslashes($_POST['a_date']);
		$field['a_image'] = $file_name;
		$field['image1'] = $file_name1;
		$field['image2'] = $file_name2;
		$field['image3'] = $file_name3;

		$res = Insertdata("activity", $field);
		if ($res != 0) {
			$msg = "Success ";
		} else {
			$error = "Deleted ";
		}
	}

	//Update
	if (isset($_POST['update'])) {

		$eid = intval($_GET['eid']);
		$oldimage = $_POST['oldimage'];
		$oldimage1 = $_POST['oldimage1'];
		$oldimage2 = $_POST['oldimage2'];
		$oldimage3 = $_POST['oldimage3'];

		if ($_FILES['image']['name'] == '') {
			$file_name = $oldimage;
		} else {
			$file_name = Uploadimage('activity', 'image');
		}


		if ($_FILES['image1']['name'] == '') {
			$file_name1 = $oldimage1;
		} else {
			$file_name1 = Uploadimage('activity', 'image1');
		}

		if ($_FILES['image2']['name'] == '') {
			$file_name2 = $oldimage2;
		} else {
			$file_name2 = Uploadimage('activity', 'image2');
		}

		if ($_FILES['image3']['name'] == '') {
			$file_name3 = $oldimage3;
		} else {
			$file_name3 = Uploadimage('activity', 'image3');
		}


		$field['a_name'] = addslashes($_POST['a_name']);
		$field['a_desc'] = addslashes($_POST['a_desc']);
		$field['a_location'] = addslashes($_POST['a_location']);
		$field['a_date'] = addslashes($_POST['a_date']);
		$field['a_image'] = $file_name;
		$field['image1'] = $file_name1;
		$field['image2'] = $file_name2;
		$field['image3'] = $file_name3;

		$res = Updatedata("activity", $field, "ac_id =$eid");
		if ($res != 0) {

			$msg = "Success ";
		} else {
			$error = "something error ";
		}
	}


	//delete
	if ($_GET['action'] == 'del' && $_GET['scid']) {
		$id = intval($_GET['scid']);
		$res = Deletedata("delete from activity where ac_id='$id'");
		header("location: " . $_SERVER['PHP_SELF']);
		$msg = "Deleted";
	}


	//Active
	if ($_GET['action'] == 'status' && $_GET['scid']) {
		$scid = intval($_GET['scid']);
		$val = $_GET['val'];
		$field['ac_active'] = $val;
		$res = Updatedata("activity", $field, "ac_id=$scid");
		$msg = "Success ";
		//	header("location: ".$_SERVER['PHP_SELF']);
	}



	//Screen Operations
	$edit = 0;
	$eid = '';
	if ($_GET['edit'] == 1) {
		$edit = 1;
		$eid = intval($_GET['eid']);
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
		<link href="assets/css/vendor/summernote-bs4.css" rel="stylesheet" type="text/css" />
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
											<li class="breadcrumb-item active">Activity</li>
										</ol>
									</div>
									<h4 class="page-title">
										Manage Activity
									</h4>
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



								<button type="button" class="btn btn-primary m-2" data-toggle="modal"
									data-target="#signup-modal">Add New</button>

								<div class="col-lg-12">
									<div class="card">
										<div class="card-body">
											<table data-order='[[ 0, "desc" ]]' id="basic-datatable"
												class="table dt-responsive nowrap w-100">
												<thead>
													<tr>
														<th>ID</th>
														<th>Image</th>
														<th>Title</th>
														<th>Gallery</th>
														<th>Date & Location</th>
														<th>
															<?php mylan("Action ", "عمل"); ?>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php

													$listdata = Selectdata("Select * FROM activity");

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
																	<?php text($row['ac_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
																</td>

																<td>
																	<?php image($row['a_image'], '100', '70', 'cover'); ?>

																</td>

																<td>
																	<?php text($row['a_name'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
																</td>

																<td>
																	<?php image($row['image2'], '100', '60', 'cover'); ?>

																</td>

																<td>
																	<?php text($row['a_location'], $strong = false, $small = true, $badge = true, $lighten = true, $outline = false, 'info', $icon = 'mdi mdi-map-marker-down'); ?>
																	<br>
																	<?php text($row['a_date'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = 'mdi mdi-calendar'); ?>
																</td>

																<td>
																	<?php action($row['ac_id'], 'E,D'); ?>
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
									$listdata = Selectdata("Select * FROM activity WHERE ac_id=$eid");
									foreach ($listdata as $row) {
										?>


										<form class="form" method="post" enctype="multipart/form-data">
											<div class="col-lg-12">

												<div class="card">
													<div class="card-body">
														<h4 class="header-title mb-2">Activity Details</h4>
														<div class="row">

															<?php forminput('Activity Name', 'a_name', $row['a_name'], 'required', '6', 'text'); ?>

															<?php forminput('Location', 'a_location', $row['a_location'], 'required', '6', 'text'); ?>

															<?php forminput('Date', 'a_date', $row['a_date'], 'required', '6', 'date'); ?>

															<?php formtextarea('Description', 'a_desc', $row['a_desc'], 'required', '12', 'text'); ?>


														</div>

													</div> <!-- end card-body-->
												</div> <!-- end card-->
											

											<!--Card Start-->
											<div class="card">
												<div class="card-body">

													<h4 class="header-title mb-2">Image & Gallery</h4>
													<div class="row">

														<?php formimage('Image', 'a_image', $row['a_image'], '', '6', 'oldimage', '80', '60', 'cover'); ?>

														<?php formimage('Gallery Photo 1', 'image1', $row['image1'], '', '6', 'oldimage1', '80', '60', 'cover'); ?>
														<?php formimage('Gallery Photo 2', 'image2', $row['image2'], '', '6', 'oldimage2', '80', '60', 'cover'); ?>
														<?php formimage('Gallery Photo 3', 'image3', $row['image3'], '', '6', 'oldimage3', '80', '60', 'cover'); ?>


													</div>

													<button name="update" class="btn btn-primary" type="submit">Update</button>

												</div> <!-- end card-body-->
											</div> <!-- end card-->

											</div> <!-- end col-->

									</div> <!-- end col-->
									</form>

								<?php } ?>

							<?php } ?>

							<!--Edit end-->







							<!-- ADD new-->
							<?php addStart($size = 'xl'); ?>


							<?php forminput('Activity Name', 'a_name', '', 'required', '6', 'text'); ?>
							<?php formtextarea('Description', 'a_desc', '', 'required', '6', 'text'); ?>

							<?php forminput('Location', 'a_location', '', 'required', '6', 'text'); ?>

							<?php forminput('Date', 'a_date', '', 'required', '6', 'date'); ?>


							<?php formimage('Image', 'a_image', '', '', '6', 'oldimage', '50', '50', 'cover'); ?>
							<?php formimage('Gallery Photo 1', 'image1', '', '', '6', 'oldimage1', '80', '30', 'cover'); ?>
							<?php formimage('Gallery Photo 2', 'image2', '', '', '6', 'oldimage2', '80', '30', 'cover'); ?>
							<?php formimage('Gallery Photo 3', 'image3', '', '', '6', 'oldimage3', '80', '30', 'cover'); ?>


							<?php addEnd(); ?>

							<!-- ADD new End-->






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
			<script src="assets/js/vendor/jquery.dataTables.min.js"></script>
			<script src="assets/js/vendor/dataTables.bootstrap4.js"></script>
			<script src="assets/js/vendor/dataTables.responsive.min.js"></script>
			<script src="assets/js/vendor/responsive.bootstrap4.min.js"></script>

			<!-- Datatable Init js -->
			<script src="assets/js/pages/demo.datatable-init.js"></script>
			<!-- end demo js-->
			<script src="assets/js/vendor/summernote-bs4.min.js"></script>
			<!-- Summernote demo -->
			<script src="assets/js/pages/demo.summernote.js"></script>
	</body>

	</html>
<?php } ?>