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


	$screentype = $_GET['type'];
	if ($screentype == 'all') {
		$_SESSION['sid'] = '';
		$_SESSION['sname'] = '';
		$_SESSION['lastpage'] = 'coupons.php?type=all';
		$sid = '';
		$sname = 'Store';
	} else {
		$sid = $_SESSION['sid'];
		$sname = $_SESSION['sname'];
	}





	//ADD
	if (isset($_POST['submit'])) {

		$field['coupon_code'] = addslashes($_POST['coupon_code']);
		$field['coupon_detail'] = addslashes($_POST['coupon_detail']);
		$field['coupon_price'] = addslashes($_POST['coupon_price']);
		$field['coupon_percentage'] = addslashes($_POST['coupon_percentage']);
		$field['coupon_products'] =  addslashes(implode(',', $_POST['coupon_products']));
		$field['u_id'] = $sid;

		$res = Insertdata("coupons", $field);
		if ($res != 0) {
			$msg = "Success ";
			//header("location: " . $_SERVER['PHP_SELF'] . "?eid=" . $res . "&&edit=1");
		} else {
			$error = "Deleted ";
		}
	}

	//Update
	if (isset($_POST['update'])) {

		$eid = intval($_GET['eid']);

		$field['coupon_code'] = addslashes($_POST['coupon_code']);
		$field['coupon_detail'] = addslashes($_POST['coupon_detail']);
		$field['coupon_price'] = addslashes($_POST['coupon_price']);
		$field['coupon_percentage'] = addslashes($_POST['coupon_percentage']);
		$field['coupon_products'] =  addslashes(implode(',', $_POST['coupon_products']));



		$res = Updatedata("coupons", $field, "coupon_id =$eid");
		if ($res != 0) {

			$msg = "Success ";
		} else {
			$error = "something error ";
		}
	}


	//delete
	if ($_GET['action'] == 'del' && $_GET['scid']) {
		$id = intval($_GET['scid']);
		$res = Deletedata("delete from coupons where coupon_id='$id'");
		header("location: " . $_SERVER['PHP_SELF']);
		$msg = "Deleted";
	}



	if (isset($_POST['subcat'])) {

		header("location: sub-category.php");
	}


	//Active
	if ($_GET['action'] == 'update' && $_GET['scid']) {
		$scid = intval($_GET['scid']);
		$val = $_GET['val'];
		$field['c_status'] = $val;
		$res = Updatedata("coupons", $field, "coupon_id=$scid");
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
											<li class="breadcrumb-item"><a href="javascript: void(0);">
													<?= $projectname; ?>
												</a></li>
											<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
											<li class="breadcrumb-item active">Coupons</li>
										</ol>
									</div>
									<h4 class="page-title">
										<?= $sname; ?>&nbsp;Coupons
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




								<div class="col-lg-12">

									<?php if ($sid != '') { ?>
										<a href="view-shop.php?eid=<?= $sid; ?>&&edit=1" class="btn btn-primary mb-2"><?php mylan("Back ", "خلف "); ?></a>
										<button type="button" class="btn btn-primary mb-2 float-right" data-toggle="modal" data-target="#signup-modal">Add New</button>
									<?php } ?>

									<div class="card">
										<div class="card-body">
											<table data-order='[[ 0, "desc" ]]' id="basic-datatable" class="table dt-responsive nowrap w-100">
												<thead>
													<tr>
														<th>ID</th>
														<th>Store</th>
														<th>Code</th>
														<th>Title</th>
														
														<th>Offer</th>
														<th>Products</th>
														<th>Usage</th>
														<th>
															<?php mylan("Action ", "عمل"); ?>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php

													$userClass = '';
													if ($sid == '') {
														$userClass = '';
													} else {
														$userClass = 'WHERE coupons.u_id=' . $sid;
													}



													$listdata = Selectdata("SELECT coupons.*,user.* FROM coupons JOIN user ON user.u_id=coupons.u_id $userClass");

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
																<?php text($row['coupon_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>
															<td>
																<?php text($row['shop_name'], $strong = true, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?><br>
																<?php text($projectcode.'S0'.$row['u_id'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>

															<td>

																<?php text($row['coupon_code' . $mydb], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, 'success'); ?>
															</td>


															<td width="30%">
																<?php text(wordwrap($row['coupon_detail' . $mydb], 30, '\n\r'), $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>

															


															<td>
																<?php

																text(($row['coupon_price'] == '0') ? ($row['coupon_percentage'] . '% OFF') : ($row['coupon_price'] . $currency), $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>


															<td>
																<?php
																$countProd = explode(',', $row['coupon_products']);
																text(sizeof($countProd) . ' ' . 'Items', $strong = false, $small = true, $badge = true, $lighten = true, $outline = false, 'secondary'); ?>
															</td>


															<td>
																<?php text($row['coupon_use'] . ' ' . 'Count', $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>



															<td>
																<?php 
																if ($sid != '') statusactive($row['coupon_id'], $row['c_status'], 'A,D,E,M'); 
																if ($sid == '') statusactive($row['coupon_id'], $row['c_status'], 'A,D,E'); 
																?>
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
									$listdata = Selectdata("Select * FROM coupons WHERE coupon_id='$eid'");
									foreach ($listdata as $row) {


									?>

										<div class="row ml-1 mr-1">
											<?php
											$cid = $row['coupon_id'];

											cardMenu($lable = 'Usage', $number = Countdata("Select * FROM coupon_used WHERE coupon_id='$cid'") . ' Items', $url = 'coupon-user.php?id=' . $cid, $icon = 'mdi mdi-tag', $size = '3', 'danger');

											?>

										</div>
										<form class="form" method="post" enctype="multipart/form-data">
											<div class="col-lg-12">

												<!--Card Start-->
												<div class="card">
													<div class="card-body">
														<h4 class="header-title mb-2">Coupon Details</h4>
														<div class="row">


															<?php forminput('CODE', 'coupon_code',  $row['coupon_code'], 'required', '12', 'text'); ?>

															<?php forminput('Price Offer (if applicable)', 'coupon_price',  $row['coupon_price'], 'required', '6', 'number'); ?>
															<?php forminput('Percentage Offer (if applicable)', 'coupon_percentage',  $row['coupon_percentage'], 'required', '6', 'number'); ?>


															<?php
															$productList = Selectdata("SELECT * FROM products WHERE u_id='$sid' ORDER BY p_id ASC");
															formmulti('Products',  'coupon_products', $productList, '', '6',  $row['coupon_products'], 'p_id', 'p_title'); ?>

															<?php formtextarea('Detail', 'coupon_detail',  $row['coupon_detail'], 'required', '6', 'text'); ?>



														</div>
														<button name="update" class="btn btn-primary" type="submit">Update</button>
													</div> <!-- end card-body-->
												</div> <!-- end card-->


											</div> <!-- end col-->


										</form>








									<?php } ?>

								<?php } ?>

								<!--Edit end-->



								<!-- ADD new-->
								<?php addStart($size = 'xl'); ?>


								<?php forminput('CODE', 'coupon_code', '', 'required', '12', 'text'); ?>

								<?php forminput('Price Offer (if applicable)', 'coupon_price', '0', 'required', '6', 'number'); ?>
								<?php forminput('Percentage Offer (if applicable)', 'coupon_percentage', '0', 'required', '6', 'number'); ?>


								<?php
								$productList = Selectdata("SELECT * FROM products WHERE u_id='$sid' ORDER BY p_id ASC");
								formmulti('Products',  'coupon_products', $productList, '', '6', '', 'p_id', 'p_title'); ?>

								<?php formtextarea('Detail', 'coupon_detail', '', 'required', '6', 'text'); ?>

								<?php addEnd(); ?>

								<!-- ADD new End-->
								<!-- --------------->
								<!-- container End-->
								<!------------------>
								</div>
						</div>

						<script>
							$('#coupon_price').change(function() {
								document.getElementById("coupon_percentage").value = 0;

							});

							$('#coupon_percentage').change(function() {
								document.getElementById("coupon_price").value = 0;

							});
						</script>


						<!-- Footer Start -->
						<?php // include 'includes/footer.php'; 
						?>
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