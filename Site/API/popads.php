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

		$file_name = Uploadimage('popads', 'image');

		$field['pa_title'] = addslashes($_POST['pa_title']);
		$field['pa_title_arab'] = addslashes($_POST['pa_title_arab']);
		$field['p_url'] = addslashes($_POST['p_url']);
		$field['p_category'] = addslashes($_POST['p_category']);
		$field['p_user'] = addslashes($_POST['p_user']);
		$field['pa_image'] = $file_name;

		$res = Insertdata("popads", $field);
		if ($res != 0) {
			$msg = "Success";
		} else {
			$error = "Deleted ";
		}
	}

	//Update
	if (isset($_POST['update'])) {

		$eid = intval($_GET['eid']);
		$oldimage = $_POST['oldimage'];
		if ($_FILES['image']['name'] == '') {
			$file_name = $oldimage;
		} else {
			$file_name = Uploadimage('popads', 'image');
		}

		$field['pa_title'] = addslashes($_POST['pa_title']);
		$field['c_name'] = addslashes($_POST['c_name']);
		$field['pa_title_arab'] = addslashes($_POST['pa_title_arab']);
		$field['p_category'] = addslashes($_POST['p_category']);
		$field['p_user'] = addslashes($_POST['p_user']);
		$field['p_url'] = addslashes($_POST['p_url']);
		$field['pa_image'] = $file_name;

		$res = Updatedata("popads", $field, "pa_id =$eid");
		if ($res != 0) {

			$msg = "Success ";
		} else {
			$error = "something error ";
		}
	}


	//delete
	if ($_GET['action'] == 'del' && $_GET['scid']) {
		$id = intval($_GET['scid']);
		$res = Deletedata("delete from popads where pa_id='$id'");
		header("location: " . $_SERVER['PHP_SELF']);
		$msg = "Deleted";
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
											<li class="breadcrumb-item active">Pop Ads</li>
										</ol>
									</div>
									<h4 class="page-title">Pop Ads Management</h4>
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

								<button type="button" class="btn btn-primary m-2" data-toggle="modal" data-target="#signup-modal"><?php mylan("Add Pop Ad ", "أضف إعلان البوب "); ?></button>
								<div class="col-lg-12">
									<div class="card">
										<div class="card-body">
											<table data-order='[[ 0, "desc" ]]' id="basic-datatable" class="table dt-responsive nowrap w-100">
												<thead>
													<tr>
														<th><?php mylan("ID ", "ID "); ?></th>
														<th><?php mylan("Image ", "صورة "); ?></th>
														<th><?php mylan("Title ", "عنوان "); ?></th>
														<th><?php mylan("Category ", " فئة"); ?></th>
														<th><?php mylan("Action ", "عمل "); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php

													$listdata = Selectdata("SELECT popads.*, category.*,user.* FROM popads LEFT JOIN category ON category.c_id=popads.p_category
													Left JOIN user ON user.u_id=popads.p_user");

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

															<td><?php text($row['pa_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?></td>

															<td><?php image($row['pa_image'], '80', '80', 'cover'); ?></td>

															<td><?php text(wordwrap($row['pa_title'], 20, "<br>\n"), $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?></td>

															<td>
																<?php

																if ($row['c_title'] != null) text($row['c_title'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, '');
																if ($row['shop_name'] != null) text($row['shop_name'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, '');
																if ($row['b_url'] != null) text($row['p_url'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, '');

																?>

															</td>

															<td><?php action($row['pa_id'], 'D'); ?></td>

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


							<?php forminput('Title',  'pa_title',  '',  'required',  '6',  'text'); ?>
							<?php forminput('Title Arabic',  'pa_title_arab',  '',  'required',  '6',  'text'); ?>
							<?php
							$menudata = Selectdata("SELECT * FROM category Order BY c_title ASC");
							formselect('Category',  'c_title', $menudata, '', '6', '', 'c_id', 'c_title'); ?>

							<?php
							$menudata = Selectdata("SELECT * FROM user WHERE shop_name IS NOT NULL Order BY shop_name ASC");
							formselect('Store',  'p_user', $menudata, '', '6', '', 'u_id', 'shop_name'); ?>

							<?php formimage('Image',  'image',  '',  '',  '6',  'oldimage',  '80',  '50',  'cover'); ?>

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