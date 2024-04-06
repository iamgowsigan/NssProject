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
		$_SESSION['lastpage'] = 'products.php?type=all';
		$sid = '';
		$sname = 'Store';
	} else {
		$sid = $_SESSION['sid'];
		$sname = $_SESSION['sname'];
	}






	//ADD
	if (isset($_POST['submit'])) {

		$file_name = Uploadimage('product', 'image');
		$field['c_id'] = addslashes($_POST['c_id']);
		$field['p_title'] = addslashes($_POST['p_title']);
		$field['p_image'] = $file_name;
		$field['u_id'] = $sid;

		$res = Insertdata("products", $field);
		if ($res != 0) {
			$msg = "Success ";
			header("location: " . $_SERVER['PHP_SELF'] . "?eid=" . $res . "&&edit=1");
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
			$file_name = Uploadimage('product', 'image');
		}



		$field['p_image'] = $file_name;



		$field['p_title'] = addslashes($_POST['p_title']);
		$field['sc_id'] = addslashes($_POST['sc_id']);
		$field['p_mrp'] = addslashes($_POST['p_mrp']);
		$field['p_sell'] = addslashes($_POST['p_sell']);
		$field['p_quantity'] = addslashes($_POST['p_quantity']);
		$field['p_unit'] = addslashes($_POST['p_unit']);
		$field['p_lable'] = addslashes($_POST['p_lable']);
		$field['p_detail'] = addslashes($_POST['p_detail']);
		$field['f1'] = addslashes($_POST['f1']);
		$field['f2'] = addslashes($_POST['f2']);
		$field['f3'] = addslashes($_POST['f3']);
		$field['p_time'] = addslashes($_POST['p_time']);
		$field['complete'] = '1';


		$res = Updatedata("products", $field, "p_id =$eid");
		if ($res != 0) {

			$ddd = $field['aaaaa'];
			$msg = "Success " . $ddd;
		} else {
			$error = "something error ";
		}
	}


	//delete
	if ($_GET['action'] == 'del' && $_GET['scid']) {
		$id = intval($_GET['scid']);
		$res = Deletedata("delete from products where p_id='$id'");
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
		$field['p_status'] = $val;
		$res = Updatedata("products", $field, "p_id=$scid");
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
											<li class="breadcrumb-item active">Products</li>
										</ol>
									</div>
									<h4 class="page-title">
										<?= $sname; ?>&nbsp;Products
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
														<th>Image</th>
														<th>Title</th>
														<th>Category</th>
														<th>Price</th>
														<th>Store</th>
														<th>Insights</th>
														<?php if ($sid == '') { ?>
															<th>View Shop</th>
														<?php } ?>

														<th><?php mylan("Action ", "عمل"); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php

													$userClass = '';
													if ($sid == '') {
														$userClass = '';
													} else {
														$userClass = 'AND products.u_id=' . $sid;
													}

													$listdata = Selectdata("Select products.*,category.*,sub_category.*,user.* FROM products JOIN category ON category.c_id=products.c_id JOIN sub_category ON sub_category.sc_id=products.sc_id JOIN user ON user.u_id=products.u_id WHERE p_status!='E' $userClass");

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
																<?php text($row['p_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>

															<td>
																<?php image($row['p_image'], '80', '80', 'cover'); ?>

															</td>

															<td>
																<?php text(substr($row['p_title' . $mydb], 0, 40), $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?><br>
																<?php text($projectcode . 'P0' . $row['p_id'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>

															</td>




															<td>
																<?php text($row['c_title' . $mydb], $strong = true, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?><br>
																<?php text($row['sc_title' . $mydb], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>

															<td>
																<?php text($row['p_quantity'] . ' ' . ArrayToName($row['p_unit'], $unitlist, 'id', 'label' . $mydb), $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?><br>

																<?php text($row['p_sell'] . $currency, $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?><br>

																<strike><?php if ($row['p_mrp'] > $row['p_sell']) text($row['p_mrp'] . $currency, $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, 'danger'); ?></strike><br>

															</td>

															<td>
																<?php text($row['shop_name'], $strong = true, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?><br>
																<?php text($projectcode . 'S0' . $row['u_id'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>


															<td>
																<?php text($row['p_view'] . ' views', $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = "uil-eye"); ?><br>
																<?php text($row['p_like'] . ' likes', $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = "uil-thumbs-up"); ?><br>
																<?php ratingBar($row['p_star'], $row['p_count']); ?>
															</td>

															<?php if ($sid == '') { ?>
																<td>
																	<a href="view-shop.php?sid=<?=$row['u_id'];?>&sname=<?=$row['shop_name'];?>"><button class="btn btn-light" type="submit" name="viewbutton"><i class="mdi mdi-arrow-right-bold"></i></button></a>
																</td>
															<?php } ?>

															<td>
																<?php
																if ($sid != '') statusactive($row['p_id'], $row['p_status'], 'A,D,O,E,M');
																if ($sid == '') statusactive($row['p_id'], $row['p_status'], 'A,D,O,E');
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
									$listdata = Selectdata("Select * FROM products WHERE p_id=$eid");
									foreach ($listdata as $row) {

										$_SESSION['pname'] = $row['p_title' . $mydb];
										$_SESSION['pid'] = $row['p_id'];
									?>

										<div class="row ml-1 mr-1">
											<?php
											$pid = $row['p_id'];

											cardMenu($lable = 'Images', $number = Countdata("Select * FROM product_images WHERE p_id=$pid") . ' Items', $url = 'images.php', $icon = 'mdi mdi-folder-image', $size = '2', 'info');

											cardMenu($lable = 'Variants', $number = Countdata("Select * FROM product_variant WHERE p_id=$pid") . ' Items', $url = 'variants.php', $icon = 'mdi mdi-shape-plus', $size = '2', 'danger');

											cardMenu($lable = 'Viewers', $number = Countdata("Select * FROM viewer WHERE f_id='$pid' AND screen='PRODUCT'") . ' Items', $url = 'viewers.php', $icon = 'mdi mdi-account-multiple', $size = '2', 'info');


											cardMenu($lable = 'Likers', $number = Countdata("Select * FROM liker WHERE f_id='$pid' AND screen='PRODUCT'") . ' Items', $url = 'likers.php', $icon = 'mdi mdi-thumb-up', $size = '2', 'secondary');

											cardMenu($lable = 'Reviews', $number = Countdata("select * FROM reviews WHERE f_id='$pid' AND screen='PRODUCT'") . ' Items', $url = 'reviews.php', $icon = 'mdi mdi-basket-outline', $size = '2', 'info');




											?>

										</div>
										<form class="form" method="post" enctype="multipart/form-data">
											<div class="col-lg-12">

												<!--Card Start-->
												<div class="card">
													<div class="card-body">
														<h4 class="header-title mb-2">Basic Details</h4>
														<div class="row">

															<?php forminput('Title', 'p_title', $row['p_title'], 'required', '6', 'text'); ?>

															<?php
															$getcid = $row['c_id'];
															$subcategoryList = Selectdata("SELECT * FROM sub_category WHERE c_id='$getcid' ORDER BY sc_id ASC");
															formselect('Product Classification',  'sc_id', $subcategoryList, 'required', '6',  $row['sc_id'], 'sc_id', 'sc_title' . $mydb); ?>

															<?php formtextarea('Detail', 'p_detail', $row['p_detail'], '', '12', 'text'); ?>

															<?php formimage('Image', 'image', $row['p_image'], 'required', '6', 'oldimage', '70', '70', 'cover'); ?>

														</div>

													</div> <!-- end card-body-->
												</div> <!-- end card-->


												<!--Card Start-->
												<div class="card">
													<div class="card-body">
														<h4 class="header-title mb-2">Price & Units</h4>
														<div class="row">

															<?php forminput('Selling Price', 'p_sell', $row['p_sell'], 'required', '6', 'number'); ?>
															<?php forminput('Original Price', 'p_mrp', $row['p_mrp'], 'required', '6', 'number'); ?>

															<div class="col-12">
																<span class="text-success font-weight-bold mt-0" id="smsg"></span>
																<span class="text-danger font-weight-bold mt-0" id="msg"></span>
															</div>

															<?php forminput('Quantity', 'p_quantity', $row['p_quantity'], 'required', '6', 'number'); ?>

															<?php
															formselect('Unit',  'p_unit', $unitlist, 'required', '6',  $row['p_unit'], 'id', 'label' . $mydb); ?>
														</div>

													</div> <!-- end card-body-->
												</div> <!-- end card-->


												<!--Card Start-->
												<div class="card">
													<div class="card-body">
														<h4 class="header-title mb-2">Filters & Labels</h4>
														<div class="row">

															<?php
															$filterList = Selectdata("SELECT * FROM filter WHERE screen='F1' AND f_category LIKE '%$getcid%'");
															formselect('Brand',  'f1', $filterList, '', '4',  $row['f1'], 'f_id', 'f_title' . $mydb); ?>

															<?php
															$filterList = Selectdata("SELECT * FROM filter WHERE screen='F2' AND f_category LIKE '%$getcid%'");
															formselect('Material',  'f2', $filterList, '', '4',  $row['f2'], 'f_id', 'f_title' . $mydb); ?>

															<?php
															$filterList = Selectdata("SELECT * FROM filter WHERE screen='F3' AND f_category LIKE '%$getcid%'");
															formselect('Make',  'f3', $filterList, '', '4',  $row['f3'], 'f_id', 'f_title' . $mydb); ?>



															<?php
															$offerLabelList = Selectdata("SELECT * FROM lable");
															formselect('Offer Label',  'p_lable', $offerLabelList, '', '6',  $row['p_lable'], 'l_id', 'l_name' . $mydb); ?>



															<?php forminput('Time Based Offer (if any)', 'p_time', $row['p_time'], '', '6', 'datetime-local'); ?>

														</div>

													</div> <!-- end card-body-->
												</div> <!-- end card-->




												<div class="col-md-12 pb-4">
													<div class="float-right" style="position:fixed;right:0;left:0;bottom:0;width:100%;height: 60px;background:white;">
														<div class="float-right" style="position:fixed;right:30px;bottom:10px;">
															<button name="update" class="btn btn-primary " type="submit">Update
																Changes</button>
														</div>
													</div>
												</div>

											</div> <!-- end col-->


										</form>








									<?php } ?>

								<?php } ?>

								<!--Edit end-->



								<!-- ADD new-->
								<?php addStart($size = 'xl'); ?>


								<?php forminput('Title', 'p_title', '', 'required', '6', 'text'); ?>


								<?php
								$categoryList = Selectdata("SELECT * FROM category ORDER BY c_id ASC");
								formselect('Category',  'c_id', $categoryList, 'required', '6', '', 'c_id', 'c_title' . $mydb); ?>

								<?php formimage('Image', 'image', '', '', '6', '', '', '', 'cover'); ?>


								<?php addEnd(); ?>

								<!-- ADD new End-->
								<!-- --------------->
								<!-- container End-->
								<!------------------>
								</div>
						</div>

						<script>
							$(document).ready(function() {
								refreshprice();
							});



							$('#p_sell').change(function() {
								refreshprice();

							});

							$('#p_mrp').change(function() {
								refreshprice();

							});
						</script>

						<script>
							function refreshprice() {


								var sell = document.getElementById("p_sell").value;
								var mrp = document.getElementById("p_mrp").value;

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