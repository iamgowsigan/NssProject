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


		$field['u_id'] = $sid;
		$field['p_id'] = $pid;
		$field['pv_title'] = addslashes($_POST['pv_title']);
		$field['pv_mrp'] = addslashes($_POST['pv_mrp']);
		$field['pv_sell'] = addslashes($_POST['pv_sell']);
		$field['pv_color'] = str_replace('#', '', $_POST['pv_color']=='#ffffff'?'':$_POST['pv_color']);


		$res = Insertdata("product_variant", $field);
		if ($res != 0) {
			$msg = "Success ";
		} else {
			$error = "Deleted ";
		}
	}

	//Update
	if (isset($_POST['update'])) {

		$eid = intval($_GET['eid']);

		$field['pv_title'] = addslashes($_POST['pv_title']);
		$field['pv_mrp'] = addslashes($_POST['pv_mrp']);
		$field['pv_sell'] = addslashes($_POST['pv_sell']);
		$field['pv_color'] = str_replace('#', '', $_POST['pv_color']=='#ffffff'?'':$_POST['pv_color']);

		$res = Updatedata("product_variant", $field, "pv_id =$eid");
		if ($res != 0) {

			$msg = "Success ";
		} else {
			$error = "something error ";
		}
	}


	//delete
	if ($_GET['action'] == 'del' && $_GET['scid']) {
		$id = intval($_GET['scid']);
		$res = Deletedata("delete from product_variant where pv_id='$id'");
		header("location: " . $_SERVER['PHP_SELF']);
		$msg = "Deleted";
	}


	
	//Active
	if ($_GET['action'] == 'update' && $_GET['scid']) {
		$scid = intval($_GET['scid']);
		$val = $_GET['val'];
		$field['pv_status'] = $val;
		$res = Updatedata("product_variant", $field, "pv_id=$scid");
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
											<li class="breadcrumb-item active">Variants</li>
										</ol>
									</div>
									<h4 class="page-title"><?= $pname; ?> Variants</h4>
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
									<a href="products.php?eid=<?= $pid; ?>&&edit=1" class="btn btn-primary m-2"><?php mylan("Back ", "خلف "); ?></a>
									<button type="button" class="btn btn-primary m-2 float-right" data-toggle="modal" data-target="#signup-modal">Add New</button>

									<div class="card">
										<div class="card-body">
											<table data-order='[[ 0, "desc" ]]' id="basic-datatable" class="table dt-responsive nowrap w-100">
												<thead>
													<tr>
														<th>ID</th>
														<th>Color</th>
														<th>Title</th>
														<th>Price</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php

													$listdata = Selectdata("Select * FROM product_variant WHERE p_id=$pid AND pv_status!='E'");

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

															<td><?php text($row['pv_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?></td>

															<td>
																<?php

																if ($row['pv_color'] != '') echo '<div style="width:30px;height:30px;background-color:#'.$row['pv_color'].';"></div>';


																?>
	 
															</td>

															<td>
																 

																<?php text($row['pv_title'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>

															</td>



															<td>


																<?php text($row['pv_sell'] . $currency, $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?><br>

																<strike><?php if ($row['pv_mrp'] > $row['pv_sell']) text($row['pv_mrp'] . $currency, $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, 'danger'); ?></strike><br>

															</td>

															<td><?php statusactive($row['pv_id'], $row['pv_status'], 'A,D,O,E,M'); ?></td>

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
											$listdata = Selectdata("Select * FROM product_variant WHERE pv_id=$eid");
											foreach ($listdata as $row) {
											?>
												<form class="form" method="post" enctype="multipart/form-data">
													<div class="row">


														<?php forminput('Title', 'pv_title', $row['pv_title'], '', '6', 'text'); ?>
														<?php forminput('Color Variant', 'pv_color', '#ffffff' , '', '6', 'color'); ?>

														<?php forminput('Selling Price', 'pv_sell', $row['pv_sell'], 'required', '6', 'number'); ?>
														<?php forminput('Original Price', 'pv_mrp', $row['pv_mrp'], 'required', '6', 'number'); ?>
														<div class="col-12">
															<span class="text-success font-weight-bold mt-0" id="smsg"></span>
															<span class="text-danger font-weight-bold mt-0" id="msg"></span>
														</div><br><br>




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
							<?php addStart($size = 'xl'); ?>

							<?php forminput('Title', 'pv_title', '', '', '6', 'text'); ?>
							<?php forminput('Color Variant', 'pv_color', '#ffffff', '', '6', 'color'); ?>

							<?php forminput('Selling Price', 'pv_sell', 'required', '', '6', 'number'); ?>
							<?php forminput('Original Price', 'pv_mrp', 'required', '', '6', 'number'); ?>
							<div class="col-12">
								<span class="text-success font-weight-bold mt-0" id="smsg"></span>
								<span class="text-danger font-weight-bold mt-0" id="msg"></span>
							</div>



							<?php addEnd(); ?>

							<!-- ADD new End-->






							<!-- --------------->
							<!-- container End-->
							<!------------------>
						</div>
					</div>
					<!-- Footer Start -->

					<script>
						$(document).ready(function() {
							refreshprice();
						});



						$('#pv_sell').change(function() {
							refreshprice();

						});

						$('#pv_mrp').change(function() {
							refreshprice();

						});
					</script>

					<script>
						function refreshprice() {


							var sell = document.getElementById("pv_sell").value;
							var mrp = document.getElementById("pv_mrp").value;

							var discount = ((mrp - sell) / mrp) * 100;

							if (isNaN(discount) || (!isFinite(discount))) {

								discount = '0';
							}


							if (discount > '0') {
								document.getElementById("smsg").style.display = "block";
								document.getElementById("smsg").innerHTML = Math.round(discount) + "% discount";
								document.getElementById("msg").style.display = "none";
							} else {
								document.getElementById("msg").style.display = "block";
								document.getElementById("msg").innerHTML = Math.round(discount) + "% discount";
								document.getElementById("smsg").style.display = "none";

							}


						}
					</script>


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