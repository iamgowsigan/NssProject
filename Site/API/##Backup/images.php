<?php
session_start();
include('includes/config.php');
//error_reporting(0);
include 'includes/language.php';
include 'includes/dbhelp.php';
include 'includes/formdata.php';
if (strlen($_SESSION['login']) == 0) {
	header('location:index.php');
} else {
	//Get ID from past page
	$sid = $_SESSION['sid'];
	$pid = $_SESSION['pid'];
	$pname = $_SESSION['pname'];
	//ADD
	if (isset($_POST['submit'])) {

		$file_name = Uploadimage('productimage', 'pi_image');

		$field['u_id'] = $sid;
		$field['p_id'] = $pid;
		$field['pi_image'] = $file_name;

		$res = Insertdata("product_images", $field);
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
		if ($_FILES['i_image']['name'] == '') {
			$file_name = $oldimage;
		} else {
			$file_name = Uploadimage('productimage', 'pi_image');
		}
		$field['pi_image'] = $file_name;

		$res = Updatedata("product_images", $field, "pi_id =$eid");
		if ($res != 0) {

			$msg = "Success ";
		} else {
			$error = "something error ";
		}
	}


	//delete
	if ($_GET['action'] == 'del' && $_GET['scid']) {
		$id = intval($_GET['scid']);
		$res = Deletedata("delete from product_images where pi_id='$id'");
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
											<li class="breadcrumb-item active">Images</li>
										</ol>
									</div>
									<h4 class="page-title"><?= $pname; ?> Images</h4>
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
									<a href="products.php?eid=<?=$pid;?>&&edit=1" class="btn btn-primary m-2"><?php mylan("Back ", "خلف "); ?></a>
									<button type="button" class="btn btn-primary m-2 float-right" data-toggle="modal" data-target="#signup-modal">Add New</button>

									<div class="card">
										<div class="card-body">
											<table data-order='[[ 0, "desc" ]]' id="basic-datatable" class="table dt-responsive nowrap w-100">
												<thead>
													<tr>
														<th>ID</th>
														<th>Image</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php

													$listdata = Selectdata("Select * FROM product_images WHERE p_id=$pid");

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

															<td><?php text($row['pi_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?></td>

															<td><?php image($row['pi_image'], '70', '50', 'cover'); ?></td>

															<td><?php action($row['pi_id'], 'E,D'); ?></td>

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


								<a class="btn btn-primary m-2" href="<?= $_SERVER['PHP_SELF'] ?>?edit=0">Back</a>

								<div class="col-lg-12">
									<div class="card">
										<div class="card-body">

											<?php
											$listdata = Selectdata("Select * FROM images WHERE i_id=$eid");
											foreach ($listdata as $row) {
											?>
												<form class="form" method="post" enctype="multipart/form-data">
													<div class="row">

														<?php formimage('Image',  'pi_image',  $row['pi_image'],  '',  '6',  'oldimage',  '80',  '50',  'cover'); ?>

													</div>
													<button name="update" class="btn btn-primary" type="submit">Update</button>
												</form>
											<?php }  ?>
										</div> <!-- end card-body-->
									</div> <!-- end card-->
								</div> <!-- end col-->

							<?php  } ?>

							<!--Edit end-->







							<!-- ADD new-->
							<?php addStart($size = 'l'); ?>

							<?php formimage('Image',  'pi_image', '',  'required',  '6',  '',  '',  '',  'cover'); ?>

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