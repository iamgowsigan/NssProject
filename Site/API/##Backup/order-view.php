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

	$adminid = $_SESSION['userid'];
	$orderid = $_SESSION['orderid'];
	$orderaction = $_SESSION['orderaction'];
	$lastpage = $_SESSION['lastpage'];

	$sid = $_SESSION['sid'];


	if ($_GET['act'] == 'act') {
		//require_once('smsservice.php');
		$val = $_GET['val'];
		$note = $_GET['note'];

		$name = $_GET['name'];
		$uid = $_GET['uid'];
		$phone = $_GET['phone'];
		$msgtxt = $_GET['msg'];

		$storeClass = '';
		if ($sid == '') {
			$storeClass = '';
		} else {
			$storeClass = 'AND s_id=' . $sid;
		}

		//orderbill($phone, $name, $uid ,$orderid,$msgtxt);
		if ($val == 'A') {
			$query = mysqli_query($con, "UPDATE cart SET cart_status='$val',accept_date=now() WHERE book_id='$orderid' $storeClass");
			$msg = "Updated ";
		} else if ($val == 'S') {
			$query = mysqli_query($con, "UPDATE cart SET cart_status='$val',sent_date=now() WHERE book_id='$orderid' $storeClass");
			$msg = "Updated ";
		} else if ($val == 'D') {
			$query = mysqli_query($con, "UPDATE cart SET cart_status='$val',delivery_date=now() WHERE book_id='$orderid' $storeClass");
			$msg = "Updated ";
		} else if ($val == 'C') {
			$query = mysqli_query($con, "UPDATE cart SET cart_status='$val',cancel_date=now() WHERE book_id='$orderid' $storeClass");
			$msg = "Updated ";
		} else if ($val == 'R') {
			$query = mysqli_query($con, "UPDATE cart SET cart_status='$val' WHERE book_id='$orderid' $storeClass");
			$msg = "Updated ";
		} else if ($val == 'P') {
			$query = mysqli_query($con, "UPDATE cart SET cart_status='$val',o_accept='',o_sent='',o_deliver='',o_cancel='' WHERE book_id='$orderid' $storeClass");
			$msg = "Updated ";
		}
		//$msg = $name.$uid.$phone.$msgtxt;
		//header("location: order-view.php");


	}


	if (isset($_POST['savenote'])) {

		$delnote = addslashes($_POST['delnote']);
		$oid = addslashes($_POST['oid']);


		$query = mysqli_query($con, "UPDATE orders SET o_reply='$delnote' WHERE o_id=$oid");
		$msg = "Updated ";
	}

	//GET ORDERS

	$orders = array();
	$products = array();
	$address = array();

	$sql3 = "SELECT orders.*,user.* FROM orders LEFT JOIN user ON user.u_id=orders.u_id WHERE o_id=$orderid ORDER by o_id DESC";
	$result3 = $con->query($sql3);
	if ($result3->num_rows > 0) {
		while ($row3 = $result3->fetch_assoc()) {
			$getAddress = $row3['address_id'];
			$joinall = $row3;
			array_push($orders, $joinall);
		}
	}



	$sql4 = "SELECT cart.*,products.*,product_variant.*,products.p_id as productid,user.* FROM cart 
		LEFT JOIN products ON products.p_id=cart.p_id 
		JOIN user ON user.u_id=products.u_id 
		LEFT JOIN product_variant ON product_variant.pv_id=cart.variant 
		WHERE cart.book_id=$orderid ORDER by cart.cart_id DESC";

	// $result4 = mysqli_query($con,$sql4);

	$result4 = $con->query($sql4);
	if ($result4->num_rows > 0) {
		while ($row3 = $result4->fetch_assoc()) {

			$joinall = $row3;
			array_push($products, $joinall);
		}
	}


	$sql4 = "SELECT address.*,user.phone as regphone,user.u_id as userid FROM address LEFT JOIN user ON user.u_id=address.u_id WHERE a_id=$getAddress";
	$result4 = $con->query($sql4);
	if ($result4->num_rows > 0) {
		while ($row3 = $result4->fetch_assoc()) {

			$joinall = $row3;
			array_push($address, $joinall);
		}
	}

	//END GET ORDERS


	if (isset($_POST['submit'])) {

		$counts = addslashes($_POST['counts']);
		$amounts = addslashes($_POST['amounts']);
		$notes = addslashes($_POST['notes']);
		$bank = addslashes($_POST['bank']);

		$couponamount = addslashes($_POST['couponamount']);
		$couponcount = addslashes($_POST['couponcount']);

		$query = mysqli_query($con, "insert into shop_payments(u_id,sent_amount,bank_detail,booking_count,detail,coupon_amount,coupon_count) values('$id','$amounts','$bank','$counts','$notes','$couponamount','$couponcount')");


		if ($query) {

			$last_id = mysqli_insert_id($con);
			mysqli_query($con, "UPDATE cart SET admin_paid='1',admin_paid_id='$last_id',admin_paid_date=now() WHERE admin_paid='0' AND s_id=$id");


			$msg = "Updated";
		} else {
			$error = "Something Wrong";
		}
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
											<li class="breadcrumb-item"><a href="dashboard.php"><?php mylan("Dashboard ", "لوحة القيادة "); ?></a></li>
											<li class="breadcrumb-item active"><?php mylan("Orders ", "طلب "); ?></li>
										</ol>
									</div>
									<h4 class="page-title"><?= $shopname; ?></h4>
								</div>
							</div>
						</div>
						<div class="row"><!-- container Start-->
							<?php include 'includes/warnings.php'; ?>

							<!-- --------------->
							<!-- container START-->
							<!------------------>


							<a href="<?= $lastpage; ?>" class="btn btn-primary m-2 d-print-none"><?php mylan("Back ", "خلف "); ?></a>

							<div class="row">
								<div class="col-12">
									<div class="card">
										<div class="card-body">

											<!-- Invoice Logo-->
											<div class="row">
												<div class="col-sm-4">
													<div class="float-left mb-3">
													</div>
												</div>
												<div class="col-sm-4">
													<div class="float-center" style="text-align: center;">
														<img src="assets/images/logo-light.png" height="50">
													</div>
												</div>
												<div class="col-sm-4">
													<div class="float-right">
														<h4 class="m-0 d-print-none"><?php mylan("Invoice ", "فاتورة "); ?></h4><br>
													</div>
												</div>
											</div>




											<!-- Invoice Detail-->
											<div class="row">
												<div class="col-sm-6">
													<div class="float-left mt-3">
														<h4><b><?= $orders[0]['name']; ?></b></h4>
														<p class="text-muted font-13">#<?= $projectcode; ?>U0<?= $orders[0]['u_id']; ?><br>
															<?= $orders[0]['phone']; ?><br><br>
														</p>
													</div>

												</div><!-- end col -->
												<div class="col-sm-4 offset-sm-2">
													<div class="mt-3 float-sm-right">
														<h3 class="float-right"><?php mylan("ORDER #00", "طلب # 00 "); ?><?= $orders[0]['o_id']; ?></h3><br>


														<p class="float-right"> <?= date("d M, Y", strtotime($orders[0]['o_dated'])); ?></p>
													</div>

												</div><!-- end col -->
											</div>
											<!-- end row -->

											<div class="row">

												<div class="col-sm-5">
													<h6><?php mylan("Shipping Address ", "عنوان الشحن "); ?></h6>
													<address>
														<small><?= $address[0]['a_name']; ?><br>
															<?php echo wordwrap($address[0]['a_address'], 150, "<br>\n"); ?><br>
															<?= ArrayToName($address[0]['a_city'], $statelist, 'id', 'name'); ?><br>

															<?php

															for ($x = 0; $x < sizeof($countrylist); $x++) {
																if (strcmp($countrylist[$x]['phonecode'], $address[0]['a_country']) == 0) {
																	echo htmlentities($countrylist[$x]['name']);
																}
															} ?>
															<br></small>

													</address>
													<h6><?php mylan("Shipping Time ", "وقت الشحن "); ?></h6>

													<p><?php

														if ($orders[0]['delivery_time'] != NULL) {
															echo date("d-m-Y H:i A", strtotime($orders[0]['delivery_time']));
														} else {
															echo "Any Preferred Time";
														}

														?></p>
												</div> <!-- end col-->

												<div class="col-sm-5">
													<address>

														<b><?php mylan("Order Type : ", "نوع الطلب : "); ?></b> <?php if ($orders[0]['method'] == 'pay') {
																													echo 'Online Pay';
																												} else {
																													echo 'Cash On Delivery';
																												} ?><br>
														<b><?php mylan("Transaction ID : ", "رقم المعاملة : "); ?></b> <?= $orders[0]['trans_id']; ?><br>
														<b><?php mylan("Order CODE : ", "رمز الطلب : "); ?></b> <?= $orders[0]['code']; ?><br>
														<b><?php mylan("Order Date : ", "تاريخ الطلب : "); ?></b> <?= date("d M, Y", strtotime($orders[0]['o_dated'])); ?><br>

													</address>
												</div> <!-- end col-->

												<div class="col-sm-2">


													<?php if ($orders[0]['method'] == 'pay') {
														echo '<span class="badge badge-success float-right">Paid Order</span>';
													}  ?>

													<?php if ($orders[0]['method'] == 'cod') {
														echo '<span class="badge badge-warning float-right">Cash on delivery</span>';
													} ?>

													<?php if ($orders[0]['method'] == 'wallet') {
														echo '<span class="badge badge-success float-right">Wallet Paid</span>';
													} ?>

													<?php if ($orders[0]['method'] == 'pickup') {
														echo '<span class="badge badge-info float-right">Store Pickup</span>';
													} ?>


												</div> <!-- end col-->




											</div>
											<!-- end row -->

											<div class="row">
												<div class="col-12">
													<div class="table-responsive">
														<table class="table mt-4">
															<thead>
																<tr>
																	<th>#</th>
																	<th><?php mylan("Item ", "العنصر "); ?></th>
																	<th>Store</th>
																	<th class="d-print-none">Status</th>

																	<th><?php mylan("Quantity ", " كمية"); ?></th>
																	<th><?php mylan("Unit Cost ", "تكلفة الوحدة "); ?></th>
																	<th><?php mylan("Coupon ", "قسيمة "); ?></th>

																	<th class="text-right"><?php mylan("Total ", "مجموع "); ?></th>
																</tr>
															</thead>
															<tbody>



																<?php for ($x = 0; $x < sizeof($products); $x++) { ?>
																	<tr>
																		<td><?= $products[$x]['productid']; ?></td>
																		<td>

																			<div class="media">


																				<div class="media-body">
																					<small><?= $projectcode; ?>P0<?= $products[$x]['productid']; ?></small>
																					<?php if ($products[$x]['u_id'] != $sid && $sid != '') { ?>&nbsp&nbsp<span class="badge badge-danger-lighten d-print-none"> Other Store</span><?php } ?><br>
																				<b><?= $products[$x]['p_title']; ?></b> <br />

																				<?= ($products[$x]['variant'] != null) ? $products[$x]['pv_title'] : '- - -'; ?>&nbsp;&nbsp;
																				<div style="width:20px;height:20px;background-color:#<?= $products[$x]['pv_color']; ?>"></div>



																				</div>
																			</div>


																		</td>


																		<td>

																			<small><?= $products[$x]['shop_name']; ?></small><br>
																			<small><?= $projectcode . 'S0' . $products[$x]['u_id']; ?></small>
																		</td>

																		<td class="d-print-none">
																			<small><?php if ($products[$x]['cart_status'] == 'P') {
																						echo '<span class="badge badge-warning d-print-none">PENDING</span>';
																					} ?>

																				<?php if ($products[$x]['cart_status'] == 'A') {
																					echo '<span class="badge badge-success d-print-none">ACCEPTED</span>';
																				} ?>

																				<?php if ($products[$x]['cart_status'] == 'S') {
																					echo '<span class="badge badge-info d-print-none">Order Sent</span>';
																				} ?>

																				<?php if ($products[$x]['cart_status'] == 'D') {
																					echo '<span class="badge badge-primary d-print-none">Delivered</span>';
																				} ?>

																				<?php if ($products[$x]['cart_status'] == 'C') {
																					echo '<span class="badge badge-danger d-print-none">Cancelled</span>';
																				} ?>

																				<?php if ($products[$x]['cart_status'] == 'R') {
																					echo '<span class="badge badge-secondary d-print-none">Returned</span>';
																				} ?>
																			</small>
																		</td>

																		<td><?= $products[$x]['quantity']; ?></td>
																		<td><?= $products[$x]['purchase_price'] . ' ' . $currency; ?></td>
																		<td><?= ($products[$x]['coupon_price'] != 0) ? ($products[$x]['coupon_price'] . ' ' . $currency) : ''; ?></td>

																		<td class="text-right"><b><?= ($products[$x]['purchase_price'] * $products[$x]['quantity']) . ' ' . $currency; ?></b></td>
																	</tr>

																<?php } ?>




															</tbody>
														</table>
													</div> <!-- end table-responsive-->
												</div> <!-- end col -->
											</div>
											<!-- end row -->

											<div class="row">
												<div class="col-6">
													<div class="clearfix pt-3">



														<form method="post" enctype="multipart/form-data">
															<div class="row">
																<div class="col-9">
																	<input type="hidden" class="form-control" name="oid" value='<?= $orders[0]['o_id']; ?>'>
																	<input type="text" class="form-control" name="delnote" value='<?= $orders[0]['o_reply']; ?>'>

																</div>
																<div class="col-3 d-print-none">
																	<button class="btn btn-light" type="submit" name="savenote">Save Delivery Note</button>

																</div>
															</div>
														</form>



														<small>


															<h6 class="text-muted ml-2">Stores</h6>
															<div class="row m-2">

																<?php

																$storeList = Selectdata("SELECT cart.*,products.*,user.* FROM cart JOIN products ON products.p_id=cart.p_id JOIN user ON user.u_id=products.u_id WHERE cart.book_id='$orderid' GROUP BY products.u_id");
																for ($x = 0; $x < sizeof($storeList); $x++) {
																?>

																	<div class="media-body mr-3">

																		<small><?= $projectcode . 'S0' . $storeList[$x]['u_id']; ?></small><br>
																		<b><?= $storeList[$x]['shop_name']; ?></b> <br />
																		<small><?= $storeList[$x]['shop_address']; ?></small>,
																		<small><?= ArrayToName($storeList[$x]['city'], $statelist, 'id', 'name'); ?></small>



																	</div>


																<?php

																}

																?>



															</div>



														</small>
													</div>
												</div> <!-- end col -->
												<div class="col-6">
													<div class="mt-3 mt-sm-0">

														<div class='row'>
															<div class='col-8'><b class="float-right"><?php mylan("Total Items : ", " إجمالي العناصر:"); ?></b></div>
															<div class='col-4 '><span class="float-right"><?= $orders[0]['quantity']; ?> <?php mylan("Item (s) ", "بند "); ?></span></div>
														</div>

														<div class='row'>
															<div class='col-8'><b class="float-right"><?php mylan(" Product Price :", "سعر المنتج : "); ?></b></div>
															<div class='col-4 '><span class="float-right"><?= ($orders[0]['product_price'] - $orders[0]['offer_price']) . ' ' . $currency; ?></span></div>
														</div>


														<div class='row'>
															<div class='col-8'><b class="float-right"><?php mylan(" Delivery :", "توصيل : "); ?></b></div>
															<div class='col-4 '><span class="float-right"><?= $orders[0]['delivery_cost'] . ' ' . $currency; ?></span></div>
														</div>

														<?php if ($orders[0]['coupon_id'] != '0') { ?>
															<div class='row'>
																<div class='col-8'><b class="float-right"><?php mylan("COUPON ", "قسيمة "); ?> ( <?= $orders[0]['coupon_code']; ?> ) :</b></div>
																<div class='col-4 '><span class="float-right" style="color:red;">- <?= $orders[0]['coupon_offer'] . ' ' . $currency; ?></span></div>
															</div>
														<?php } ?>

														<hr>
														</hr>
														<div class='row'>
															<div class='col-8'><b class="float-right"><?php mylan("Tax ", " ضريبة"); ?> ( <?= $orders[0]['tax_percentage'] ?> % ) :</b></div>
															<div class='col-4 '><span class="float-right"><?= $orders[0]['tax_cost'] . ' ' . $currency; ?></span></div>
														</div>

														<hr>
														</hr>



														<h3 class="float-right mt-3 mt-sm-0"><?= round($orders[0]['purchase_price']) . ' ' . $currency; ?></h3>

													</div>
													<div class="clearfix"></div>
												</div> <!-- end col -->
											</div>
											<div class="d-print-none mt-4">
												<div class="text-right">

													<?php

													if ($msg == '' && $orderaction == 'D') {
														echo '<a href="order-view.php?act=act" class="btn btn-primary"> Make as Delivered</a>';
													}

													if ($msg == '' && $orderaction == 'S') {
														echo '<a href="order-view.php?act=act" class="btn btn-primary"> Make as Pending</a>';
													}

													?>
													<a href="order-view.php?act=act&val=A&name=<?= $orders[0]['name']; ?>&uid=<?= $orders[0]['u_id']; ?>&phone=<?= $orders[0]['country'] . $orders[0]['phone']; ?>&msg=Your order is accepted. We will send your order soon" class="btn btn-success"> <?php mylan(" Accept Order", "قبول الطلب "); ?></a>
													<a href="order-view.php?act=act&val=S&name=<?= $orders[0]['name']; ?>&uid=<?= $orders[0]['u_id']; ?>&phone=<?= $orders[0]['country'] . $orders[0]['phone']; ?>&msg=Your order is accepted. We had sent your order" class="btn btn-info"> <?php mylan(" Send Order", " ارسال الطلب"); ?></a>
													<a href="order-view.php?act=act&val=D&name=<?= $orders[0]['name']; ?>&uid=<?= $orders[0]['u_id']; ?>&phone=<?= $orders[0]['country'] . $orders[0]['phone']; ?>&msg=Your order is delivered" class="btn btn-primary"> <?php mylan(" Make as Delivered", "جعل كما تم تسليمها "); ?></a>
													<a href="order-view.php?act=act&val=C&name=<?= $orders[0]['name']; ?>&uid=<?= $orders[0]['u_id']; ?>&phone=<?= $orders[0]['country'] . $orders[0]['phone']; ?>&msg=Your request is accepted. We will cancel your order" class="btn btn-danger"> <?php mylan(" Cancel Order", "الغاء الطلب "); ?></a>
													<a href="order-view.php?act=act&val=R&name=<?= $orders[0]['name']; ?>&uid=<?= $orders[0]['u_id']; ?>&phone=<?= $orders[0]['country'] . $orders[0]['phone']; ?>&msg=Your request accepted. You can return our product. Let you know the process of return" class="btn btn-secondary"><?php mylan("Return ", "يعود "); ?> </a>
													<a href="order-view.php?act=act&val=P&name=<?= $orders[0]['name']; ?>&uid=<?= $orders[0]['u_id']; ?>&phone=<?= $orders[0]['country'] . $orders[0]['phone']; ?>&msg= We will process your order soon" class="btn btn-warning"> <?php mylan(" Reset", "إعادة ضبط "); ?></a>
													<a href="javascript:window.print()" class="btn btn-primary"><i class="mdi mdi-printer"></i><?php mylan("Print ", "مطبعة "); ?> </a>

												</div>
											</div>
											<!-- end buttons -->

										</div> <!-- end card-body-->
									</div> <!-- end card -->
								</div> <!-- end col-->
							</div>
							<!-- end row -->


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

			<!-- demo app -->
			<script src="assets/js/pages/demo.dashboard-crm.js"></script>
			<!-- end demo js-->

			<!-- Apex js -->
			<script src="assets/js/vendor/apexcharts.min.js"></script>

			<!-- Todo js -->
			<script src="assets/js/vendor/jquery.dataTables.min.js"></script>
			<script src="assets/js/vendor/dataTables.bootstrap4.js"></script>
			<script src="assets/js/vendor/dataTables.responsive.min.js"></script>
			<script src="assets/js/vendor/responsive.bootstrap4.min.js"></script>

			<!-- Datatable Init js -->
			<script src="assets/js/pages/demo.datatable-init.js"></script>


	</body>

	</html>
<?php } ?>