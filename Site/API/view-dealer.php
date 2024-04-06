<?php
session_start();
include('includes/config.php');
error_reporting(0);
include 'includes/language.php';
include 'includes/country.php';
include 'includes/dbhelp.php';
include 'includes/formdata.php';

if (strlen($_SESSION['login']) == 0) {
	header('location:index.php');
} else {

	$adminid = $_SESSION['userid'];
	$sid = $_GET['sid'];
	$sname = $_GET['sname'];
	$shopscreen = $_SESSION['shopscreen'];
	$lastpage = $_SESSION['lastpage'];

	if ($sid != '') {
		$_SESSION['sid'] = $sid;
		$_SESSION['sname'] = $sname;
	} else {
		$sid = $_SESSION['sid'];
		$sname = $_SESSION['sname'];
	}

	$userdetail = array();

	$sql = "Select * FROM user WHERE u_id='$sid'";
	$result1 = $con->query($sql);
	while ($row1 = $result1->fetch_assoc()) {
		array_push($userdetail, $row1);
	}
	?>

	<!DOCTYPE html>
	<html lang="en">

	<head>
		<?php include 'includes/head.php'; ?>
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
											<li class="breadcrumb-item"><a href="dashboard.php">
													<?php mylan("Dashboard ", "لوحة القيادة "); ?>
												</a></li>
											<li class="breadcrumb-item active">View Dealer</li>
										</ol>
									</div>
									<h4 class="page-title">View Dealer</h4>
									<a href=<?= $lastpage; ?> class="btn btn-primary m-2">
										<?php mylan("Back ", "خلف "); ?>
									</a>

								</div>
							</div>
						</div>
						<div class="row"><!-- container Start-->
							<?php include 'includes/warnings.php'; ?>

							<!-- --------------->
						<!-- container START-->
							<!------------------>

							<div class="col-xl-12 col-lg-12">

								<div class="card widget-flat h-75">
									<div class="card-body">
										<div class="row mb-2">

											<div class="col-sm-2">
												<img src="<?php echo htmlentities($imgloc . $userdetail[0]['shop_banner']); ?>"
													style="width:100px;height:100px;object-fit:cover"
													class="rounded-circle img-thumbnail"
													onerror="this.src='assets/images/bg-auth.jpg'">
											</div> <!-- end col-->

											<div class="col-sm-2">

												<small>
													<h4>
														<?= $userdetail[0]['shop_name']; ?>
													</h4>
													<!-- <?php
													$vendorcategory = Selectdata('SELECT * FROM vendor_category');
													echo ArrayIdToName($userdetail[0]['shop_service'], $vendorcategory, 'v_id', 'v_title'); ?><br> -->
													<b>
														<?= $projectcode; ?>D0<?= $userdetail[0]['u_id']; ?>
													</b><br>



												</small>

											</div> <!-- end col-->


											<div class="col-sm-2">

												<small>

													<a href="tell:<?= $userdetail[0]['phone']; ?>"><i
															class="mdi mdi-cellphone"></i>&nbsp;&nbsp;
														<?= $userdetail[0]['phone']; ?>
													</a><br>
													<i class="mdi mdi-account"></i>&nbsp;&nbsp;
													<?= $userdetail[0]['name']; ?><br>
													<i class="mdi mdi-email-multiple"></i>&nbsp;&nbsp;
													<?= $userdetail[0]['email']; ?><br>
													<?= $userdetail[0]['shop_address']; ?><br>

												</small>

											</div> <!-- end col-->


											<div class="col-sm-2">

												<small>

													<i class="mdi mdi-map-marker-alert"></i>&nbsp;&nbsp;
													<?= ArrayToName($userdetail[0]['country'], $countrylist, 'id', 'name'); ?>
													<br>

													<!-- <i class="mdi mdi-map-marker-down"></i>&nbsp;&nbsp;
													<?php $stateCategory = Selectdata('SELECT * FROM state'); ?>
													<?= ArrayToName($userdetail[0]['state'], $stateCategory, 'state_id', 'state_title'); ?>
													<br> -->

													<i class="uil-sign-right"></i>&nbsp;&nbsp;
													<?php $districtCategory = Selectdata('SELECT * FROM district'); ?>
													<?= ArrayToName($userdetail[0]['district'], $districtCategory, 'district_id', 'district_title'); ?>
													<br>

													<i class="uil-map-pin"></i>&nbsp;&nbsp;
													<?php $cityCategory = Selectdata('SELECT * FROM city'); ?>
													<?= ArrayToName($userdetail[0]['city'], $cityCategory, 'city_id', 'city_title'); ?>
													<br>


												</small>

											</div> <!-- end col-->



											<div class="col-sm-2">

												<small>

													GST :
													<?= $userdetail[0]['shop_gst']; ?> <br>

													Landline :
													<?= $userdetail[0]['shop_landline']; ?> <br>

												</small>

											</div> <!-- end col-->



											<div class="col-sm-2">

												<small>

													<!-- <?php

													$getdbDate = $userdetail[0]['valid'];
													$getisSubscribe = $userdetail[0]['subscribe'];
													$getSubscribeDate = $userdetail[0]['subscribe_date'];
													$today = date('Ymd');
													$finaldays = strtotime($getdbDate) - strtotime($today);
													$daysdiff = abs(round($finaldays / 86400));

													if ($getisSubscribe == '0') {
														text('Not Subscribed', $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, 'info');
													} else {

														if ($daysdiff > 0) {
															text($daysdiff . ' Days', $strong = true, $small = true, $badge = false, $lighten = false, $outline = false, 'success');
														} else {
															text('Expired', $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, 'danger');

														}
													}

													// if($getisSubscribe!='0'){
													// 	$subPlans = Selectdata("SELECT * FROM plan");
													// 	text('<BR>'.ArrayToName($userdetail[0]['subscribe'], $subPlans, 'plan_id', 'plan_name' . $mydb).'<br>from '.date('Y-m-d', strtotime($userdetail[0]['subscribe_date'])), $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); 
													// }
													?> -->


												</small>

											</div> <!-- end col-->

										</div> <!-- end row -->


									</div> <!-- end card-body-->
								</div> <!-- end card-->
							</div> <!-- end col-->


							<?php

							//cardMenu($lable = 'Address', $number = Countdata("Select * FROM address WHERE u_id=$sid") . ' Items', $url = 'address.php', $icon = 'uil-location-point', $size = //'3', 'success');
						
							// cardMenu($lable = 'Catalogue', $number = Countdata("Select * FROM menu WHERE u_id=$sid") . ' Items', $url = 'menu.php', $icon = 'mdi mdi-room-service', $size = '3', 'info');
						
							 cardMenu($lable = 'Products', $number = Countdata("Select * FROM product_link WHERE u_id=$sid") . ' Items', $url = "dealers-product.php", $icon = 'mdi mdi-widgets', $size = '3', 'danger');
						
							// cardMenu($lable = 'Coupons', $number = Countdata("Select * FROM coupons WHERE u_id=$sid") . ' Items', $url = 'coupons.php', $icon = 'mdi mdi-ticket-percent', $size = '3', 'success');
						
							// cardMenu($lable = 'Orders', $number = Countdata("SELECT cart.*,orders.*,products.p_id,products.u_id as store_id,products.p_title,products.p_image FROM cart 
							// JOIN orders ON orders.o_id=cart.book_id JOIN products ON products.p_id=cart.p_id WHERE cart.book_id!=0 AND cart.s_id='$sid' GROUP BY cart.book_id") . ' Orders', $url = 'orders.php', $icon = 'mdi mdi-cart', $size = '3', 'danger');
						
							// /*	cardMenu($lable = 'Orders', $number = Countdata("Select * FROM products WHERE u_id=$sid") . ' Items', $url = 'club-images.php', $icon = 'mdi mdi-cart-outline', $size = '3', 'warning');*/
						
							// cardMenu($lable = 'Viewers', $number = Countdata("Select * FROM viewer WHERE u_id='$sid' AND screen='$shopscreen'") . ' Items', $url = 'viewers.php', $icon = 'mdi mdi-account-multiple', $size = '3', 'info');
						
							// cardMenu($lable = 'Likers', $number = Countdata("Select * FROM liker WHERE u_id='$sid' AND screen='$shopscreen'") . ' Items', $url = 'likers.php', $icon = 'mdi mdi-thumb-up', $size = '3', 'secondary');
						
							// cardMenu($lable = 'Reviews', $number = Countdata("select * FROM reviews WHERE u_id='$sid' AND screen='STORE'") . ' Items', $url = 'reviews.php', $icon = 'mdi mdi-basket-outline', $size = '3', 'warning');
						
							// cardMenu($lable = 'Chats', $number = Countdata("select * FROM chat_history WHERE sender_id='$sid' or receiver_id = '$sid' ") . ' Items', $url = 'chat-history.php', $icon = 'uil-comments', $size = '3', 'success');
						

							?>

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
	</body>

	</html>
<?php } ?>