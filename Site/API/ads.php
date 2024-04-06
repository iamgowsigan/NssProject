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


	//ADD
	if (isset($_POST['submit'])) {

		$file_name = Uploadimage('ads', 'image');

		//$field['a_url'] = addslashes($_POST['a_url']);
		//$field['a_user'] = addslashes($_POST['a_user']);
		$field['a_category'] = addslashes($_POST['a_category']);
		$field['a_size'] = addslashes($_POST['a_size']);
		$field['a_image'] = $file_name;

		$res = Insertdata("ads", $field);
		if ($res != 0) {
			$msg = "Success ";
		} else {
			$error = "Error ";
		}
	}

	//Update
	if (isset($_POST['update'])) {

		$eid = intval($_GET['eid']);
		$oldimage = $_POST['oldimage'];
		if ($_FILES['image']['name'] == '') {
			$file_name = $oldimage;
		} else {
			$file_name = Uploadimage('ads', 'image');
		}

		$field['a_url'] = addslashes($_POST['a_url']);
		$field['a_user'] = addslashes($_POST['a_user']);
		$field['a_category'] = addslashes($_POST['a_category']);
		$field['a_size'] = addslashes($_POST['a_size']);
		$field['a_image'] = $file_name;

		$res = Updatedata("ads", $field, "a_id =$eid");
		if ($res != 0) {

			$msg = "Success ";
		} else {
			$error = "something error ";
		}
	}


	//delete
	if ($_GET['action'] == 'del' && $_GET['scid']) {
		$id = intval($_GET['scid']);
		$res = Deletedata("delete from ads where a_id='$id'");
		header("location: " . $_SERVER['PHP_SELF']);
		$msg = "Deleted";
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
											<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
											<li class="breadcrumb-item active">Ads</li>
										</ol>
									</div>
									<h4 class="page-title">Manage Ads</h4>
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

									<div class="row mb-2">
										<div class="col-sm-8">
											<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#signup-modal"><?php mylan("Add New ", "اضف جديد "); ?></button>
										</div> <!-- end col-->
									</div> <!-- end row -->

									<div class="card">
										<div class="card-body">

											<table data-order='[[ 0, "desc" ]]' id="basic-datatable" class="table dt-responsive w-100">
												<thead>
													<tr>
														<th>ID</th>
														<th><?php mylan("Image ", "صورة"); ?></th>
														<th>Size</th>
														<th>Page</th>
														<th><?php mylan("Action ", "عمل "); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php

													$listdata = Selectdata("SELECT ads.*, category.*,user.* FROM ads LEFT JOIN category ON category.c_id=ads.a_category
															Left JOIN user ON user.u_id=ads.a_user");

													if (sizeof($listdata) == 0) {
													?>
														<tr>
															<td colspan="7" align="center">
																<h3 style="color:black"><?php mylan("No record found ", "لا يوجد سجلات "); ?></h3>
															</td>
														<tr>
															<?php
														} else {
															foreach ($listdata as $row) {

															?>
														<tr>

															<td><?php text($row['a_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?></td>

															<td>
																<?php 
																
																if($row['a_size']=='SQUARE')image($row['a_image'], '80', '80', 'cover'); 
																if($row['a_size']=='PORTRAIT')image($row['a_image'], '60', '80', 'cover'); 
																if($row['a_size']=='LANDSCAPE')image($row['a_image'], '90', '50', 'cover'); 
																
																?>
															
															</td>

															<td><?php text($row['a_size'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?></td>

															<td>
																<?php

																if ($row['c_title'] != null) text($row['c_title'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, '');
																if ($row['shop_name'] != null) text($row['shop_name'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, '');
																if ($row['a_url'] != null) text($row['a_url'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, '');

																?>

															</td>

															<td><?php action($row['a_id'], 'D'); ?></td>

														</tr>
												<?php }
														} ?>
												</tbody>
											</table>
										</div> <!-- end card-body-->
									</div> <!-- end card-->
								</div> <!-- end col-->
							<?php  } ?>
							<!--LIST VIEW END-->



							<!-- ADD new-->
							<?php addStart($size = 'xl'); ?>


							<p class="col-12">Select any one of the following option. Category or Store or URL</p>

							<?php

							formselect('Size',  'a_size', $adSize, 'required', '6', '', 'id', 'lable'); ?>

							<?php formimage('Image',  'image', '',  'required',  '6',  'required',  '',  '',  'cover'); ?>


							<?php
							$menudata = Selectdata("SELECT * FROM category Order BY c_title ASC");
							formselect('Category',  'a_category', $menudata, '', '4', '', 'c_id', 'c_title' . $mydb); ?>

				 


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
	</body>

	</html>
<?php } ?>