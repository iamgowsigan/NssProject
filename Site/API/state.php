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

	
	//$stateid = $_SESSION['stateid'];

	//ADD
	if (isset($_POST['submit'])) {


		//$field['state_id'] = $stateid;
		$field['state_title'] = addslashes($_POST['state_title']);

		$res = Insertdata("state", $field);
		if ($res != 0) {
			$msg = "Success ";
		} else {
			$error = "Deleted ";
		}


	}

	//Update
	if (isset($_POST['update'])) {

		$eid = intval($_GET['eid']);

		$field['state_title'] = addslashes($_POST['state_title']);

		$res = Updatedata("state", $field, "state_id =$eid");
		if ($res != 0) {

			$msg = "Success ";
		} else {
			$error = "something error ";
		}
	}


	//delete
	if ($_GET['action'] == 'del' && $_GET['scid']) {
		$id = intval($_GET['scid']);
		$res = Deletedata("delete from state where state_id='$id'");
		header("location: " . $_SERVER['PHP_SELF']);
		$msg = "Deleted";
	}


	// //Active
	// if ($_GET['action'] == 'status' && $_GET['scid']) {
	// 	$scid = intval($_GET['scid']);
	// 	$val = $_GET['val'];
	// 	$field['c_active'] = $val;
	// 	$res = Updatedata("state", $field, "state_id=$scid");
	// 	$msg = "Success ";
	// 	//	header("location: ".$_SERVER['PHP_SELF']);
	// }



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
											<li class="breadcrumb-item active">State</li>
										</ol>
									</div>
									<h4 class="page-title">
										Manage State
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
														<th>State</th>
														<th>
															<?php mylan("Action ", "عمل"); ?>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php

													$listdata = Selectdata("Select * FROM state");

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
																	<?php text($row['state_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
																</td>

																<td>
																	<?php text($row['state_title'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
																</td>

																<td>
																	<?php action($row['state_id'], 'E,D'); ?>
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

								<a class="btn btn-primary m-2" href="<?= $_SERVER['PHP_SELF']; ?>?edit=0">Back</a>

								<div class="col-lg-12">
									
									<div class="row">
										<?php cardMenu($lable = 'District', $number = Countdata("Select * FROM district WHERE state_id ='$eid'") . ' Districts', $url = 'district.php', $icon = 'uil-sign-right', $size = '3', 'info'); ?>
									</div>


									<?php
									$listdata = Selectdata("Select * FROM state WHERE state_id=$eid");
									foreach ($listdata as $row) {

										$_SESSION['stateid'] = $row['state_id'];
										$_SESSION['stitle'] = $row['state_title'];
										$passid = $row['state_id'];
										$_SESSION['lastpage'] = 'state.php?eid='.$passid.'&&edit=1';

										?>

										<form class="form" method="post" enctype="multipart/form-data">
											<div class="col-lg-12">
												<div class="card">	
													<div class="card-body">
														<div class="row">

															<?php forminput('State', 'state_title', $row['state_title'], 'required', '12', 'text'); ?>

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


							<?php forminput('State', 'state_title', '', 'required', '6', 'text'); ?>


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