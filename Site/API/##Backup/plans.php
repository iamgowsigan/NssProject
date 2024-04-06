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

		$field['plan_name'] = addslashes($_POST['plan_name']);
		$field['plan_name_arab'] = addslashes($_POST['plan_name_arab']);
		$field['plan_cost'] = addslashes($_POST['plan_cost']);
		$field['plan_days'] = addslashes($_POST['plan_days']);

		$res = Insertdata("plan", $field);
		if ($res != 0) {
			$msg = "Success ";
		} else {
			$error = "Deleted ";
		}
	}

	//Update
	if (isset($_POST['update'])) {

		$eid = intval($_GET['eid']);

		$field['plan_name'] = addslashes($_POST['plan_name']);
		$field['plan_name_arab'] = addslashes($_POST['plan_name_arab']);
		$field['plan_cost'] = addslashes($_POST['plan_cost']);
		$field['plan_days'] = addslashes($_POST['plan_days']);

		$res = Updatedata("plan", $field, "plan_id =$eid");
		if ($res != 0) {

			$msg = "Success ";
		} else {
			$error = "something error ";
		}
	}


	//delete
	if ($_GET['action'] == 'del' && $_GET['scid']) {
		$id = intval($_GET['scid']);
		$res = Deletedata("delete from plan where plan_id ='$id'");
		header("location: " . $_SERVER['PHP_SELF']);
		$msg = "Deleted";
	}

	//Active
	if ($_GET['action'] == 'status' && $_GET['scid']) {
		$scid = intval($_GET['scid']);
		$val = $_GET['val'];
		$field['plan_active'] = $val;
		$res = Updatedata("plan", $field, "plan_id =$scid");
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
											<li class="breadcrumb-item active">Plan</li>
										</ol>
									</div>
									<h4 class="page-title">Manage Plans</h4>
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

								<button type="button" class="btn btn-primary m-2" data-toggle="modal" data-target="#signup-modal">Add New</button>
								<div class="col-lg-12">
									<div class="card">
										<div class="card-body">
											<table data-order='[[ 0, "desc" ]]' id="basic-datatable" class="table dt-responsive nowrap w-100">
												<thead>
													<tr>
														<th>ID</th>
														<th>Title</th>
														<th>Days</th>
														<th>Cost</th>
														<th>Status</th>
														<th><?php mylan("Action ", "عمل "); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php

													$listdata = Selectdata("Select * FROM plan");

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

															<td><?php text($row['plan_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?></td>

															<td><?php text($row['plan_name'.$mydb], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?></td>

															

															<td><?php text($row['plan_days'].' Days', $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?></td>

															<td><?php text($row['plan_cost'].$currency, $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, 'info'); ?></td>

															

															<td>
																<?php active($row['plan_active'], $row['plan_id']); ?>
															</td>
														 
															<td><?php action($row['plan_id'], 'E,D'); ?></td>

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


							<!--EDIT VIEW-->
							<?php if ($edit == 1) { ?>

								<div class="col-lg-12">
									<a class="btn btn-primary m-2" href="<?= $_SERVER['PHP_SELF']; ?>?edit=0">Back</a>


									<?php
									$listdata = Selectdata("Select * FROM plan WHERE plan_id=$eid");
									foreach ($listdata as $row) {
									?>

										<form class="form" method="post" enctype="multipart/form-data">
											<div class="col-lg-12">
												<div class="card">
													<div class="card-body">
														<div class="row">
															<?php forminput('Title',  'plan_name',  $row['plan_name'],  'required',  '6',  'text'); ?>
															<?php forminput('Title Arab',  'plan_name_arab',  $row['plan_name_arab'],  'required',  '6',  'text'); ?>
															<?php forminput('Plan Cost',  'plan_cost',  $row['plan_cost'],  'required',  '6',  'number'); ?>
															<?php forminput('Plan Days',  'plan_days',  $row['plan_days'],  'required',  '6',  'number'); ?>

														</div>
														<button name="update" class="btn btn-primary" type="submit">Update</button>
													</div> <!-- end card-body-->
												</div> <!-- end card-->
											</div> <!-- end col-->

								</div> <!-- end col-->
								</form>

							<?php }  ?>

						<?php  } ?>

						<!--Edit end-->


						<!-- ADD new-->
						<?php addStart($size = 'xl'); ?>
						<?php forminput('Title',  'plan_name',  '',  'required',  '6',  'text'); ?>
						<?php forminput('Title Arab',  'plan_name_arab',  '',  'required',  '6',  'text'); ?>
						<?php forminput('Plan Cost',  'plan_cost',  '',  'required',  '6',  'number'); ?>
						<?php forminput('Plan Days',  'plan_days',  '',  'required',  '6',  'number'); ?>

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