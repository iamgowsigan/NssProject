<?php
session_start();
include('includes/config.php');
error_reporting(0);
include 'includes/language.php';
include 'includes/dbhelp.php';
include 'includes/formdata.php';
include 'includes/country.php';
if (strlen($_SESSION['login']) == 0) {
	header('location:index.php');
} else {



	$screentype = $_GET['type'];
	if ($screentype == 'all') {
		$_SESSION['sid'] = '';
		$_SESSION['sname'] = '';
		$_SESSION['lastpage'] = 'orders.php?type=all';
		$sid = '';
		$sname = 'Store';
	} else {
		$sid = $_SESSION['sid'];
		$sname = $_SESSION['sname'];
	}

	//mysqli_query($con, "UPDATE orders SET adminview='1' WHERE adminview=0");



	if (isset($_POST['viewbutton'])) {
		$orderid = addslashes($_POST['orderid']);
		$_SESSION['orderid'] = $orderid;
		//$msg = "Deleted".$orderid;
		header("location: order-view.php");
	}




	//delete
	if ($_GET['action'] == 'del' && $_GET['scid']) {
		$id = intval($_GET['scid']);
		$res = Deletedata("delete from orders where o_id='$id'");
		//header("location: " . $_SERVER['PHP_SELF']);
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
											<li class="breadcrumb-item active">Orders</li>
										</ol>
									</div>
									<h4 class="page-title">
										<?= $sname; ?>&nbsp;Orders
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
									<?php } ?>


									<div class="card">
										<div class="card-body">
											<table data-order='[[ 0, "desc" ]]' id="basic-datatable" class="table dt-responsive w-100">
												<thead>
													<tr>
														<th>ID</th>
														<th>Product</th>
														<th>User</th>
														<th>Method</th>
														<th>Price</th>
														<th>Order Date</th>
														<th>Address</th>
														<th>Delivery</th>
														<th>Delete</th>
														<th>Action</th>

													</tr>
												</thead>
												<tbody>
													<?php

													$userClass = '';
													if ($sid == '') {
														$userClass = '';
													} else {
														$userClass = 'AND cart.s_id=' . $sid;
													}


													$listdata = Selectdata("SELECT cart.*,orders.*,products.p_id,products.u_id as store_id,products.p_title,products.p_image,user.u_id,user.name,user.phone,address.a_city FROM cart 
													JOIN orders ON orders.o_id=cart.book_id 
													JOIN products ON products.p_id=cart.p_id
													JOIN user ON user.u_id=cart.u_id 
													LEFT JOIN address ON address.a_id=orders.address_id 
													WHERE cart.book_id!=0 $userClass 
													GROUP BY book_id ORDER BY o_id DESC");

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
																<?php text($row['o_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>

															<td><?php
																$bookid = $row['o_id'];
																$cartItem = Selectdata("SELECT cart.*, products.p_image FROM cart JOIN products ON products.p_id=cart.p_id WHERE book_id='$bookid' LIMIT 1");
																image($cartItem[0]['p_image'], '60', '70', 'contain'); ?>
															</td>

															<td>
																<?php text($row['name'], $strong = true, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?><br>
																<?php text($projectcode . 'U0' . $row['u_id'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>



															<td>
																<?php text($row['method'], $strong = false, $small = true, $badge = true, $lighten = false, $outline = false, $row['method'] == 'pay' ? 'success' : 'danger'); ?>
															</td>


															<td>
																<?php text($row['purchase_price'] . $currency, $strong = true, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?><br>
																<?php text($row['quantity'] . ' ' . 'items', $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>


															<td>
																<?php text($row['o_dated'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>

															<td><?php text($row['phone'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = 'mdi mdi-cellphone'); ?>

																<br><?php text(ArrayToName($row['a_city'], $statelist, 'id', 'name'), $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = 'mdi mdi-map-marker-down'); ?>
															</td>


															<td>
																<?php

																text($row['delivery_cost'] . $currency, $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>



															<td>
																<a href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?scid=<?php echo htmlentities($row['o_id']); ?>&&action=del" onclick="return confirm('Are you sure you want to delete?');" class="action-icon"><i class="mdi mdi-delete"></i></a>
															</td>

															<td>

																<form method="post" enctype="multipart/form-data">

																	<input type="hidden" name="orderid" value='<?= $row['o_id']; ?>'>
																	<button class="btn btn-light" type="submit" name="viewbutton"><i class="mdi mdi-arrow-right-bold"></i></button>


																</form>
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