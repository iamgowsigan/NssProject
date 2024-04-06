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
	$lastpage = $_SESSION['lastpage'];

	if ($sid != '') {
		$_SESSION['sid'] = $sid;
		$_SESSION['sname'] = $sname;
		$_SESSION['pastscreen'] = '';
	} else {

		$sid = $_SESSION['sid'];
		$sname = $_SESSION['sname'];
	}

	$userdetail = array();

	$sql = "Select *  FROM user WHERE u_id='$sid'";
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
											<li class="breadcrumb-item active">Shop</li>
										</ol>
									</div>
									<h4 class="page-title">Shop Management</h4>
									<a href="<?=$lastpage;?>" class="btn btn-primary m-2"><?php mylan("Back ", "خلف "); ?></a>

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
												<img src="<?php echo htmlentities($imgloc . $userdetail[0]['profile_pic']); ?>" style="width:100px;height:100px;object-fit:cover" class="rounded-circle img-thumbnail" onerror="this.src='assets/images/bg-auth.jpg'">
											</div> <!-- end col-->

											<div class="col-sm-4">

												<small>
													<h4><?= $userdetail[0]['shop_name']; ?></h4>
													<b><?= $projectcode; ?>S0<?= $userdetail[0]['u_id']; ?></b><br>
													<i class="mdi mdi-map-marker-alert"></i>&nbsp;&nbsp;<?= ArrayToName($userdetail[0]['city'], $statelist, 'id', 'name'); ?> <br>

												</small>

											</div> <!-- end col-->

											<div class="col-sm-4">


												<small>

													<a href="tell:<?= $userdetail[0]['phone']; ?>"><i class="mdi mdi-cellphone"></i>&nbsp;&nbsp;<?php echo htmlentities($userdetail[0]['phone']); ?></a><br>
													<i class="mdi mdi-email-multiple"></i>&nbsp;&nbsp;<?= $userdetail[0]['email']; ?><br>
													<i class="mdi mdi-map-marker-alert"></i>&nbsp;&nbsp;<?= ArrayToName($userdetail[0]['country'], $countrylist, 'phonecode', 'name'); ?> <br>


												</small>

											</div> <!-- end col-->



											<div class="col-sm-2">

												<small>

													<b>Bank Account</b> <br>
													<?= $userdetail[0]['bank_name']; ?> <br>

													<?= $userdetail[0]['bank_holder']; ?><br>
													<?= $userdetail[0]['bank_ac']; ?><br>
													<?= $userdetail[0]['bank_code']; ?><br>

												</small>

											</div> <!-- end col-->

										</div> <!-- end row -->


									</div> <!-- end card-body-->
								</div> <!-- end card-->
							</div> <!-- end col-->


							<?php

							cardMenu($lable = 'Products', $number = Countdata("Select * FROM products WHERE u_id=$sid") . ' Items', $url = 'products.php', $icon = 'mdi mdi-basket-outline', $size = '3', 'info');

							cardMenu($lable = 'Coupons', $number = Countdata("Select * FROM coupons WHERE u_id=$sid") . ' Items', $url = "coupons.php", $icon = 'mdi mdi-tag', $size = '3', 'danger');

							cardMenu($lable = 'Orders', $number = Countdata("SELECT cart.*,orders.*,products.p_id,products.u_id as store_id,products.p_title,products.p_image FROM cart 
									JOIN orders ON orders.o_id=cart.book_id JOIN products ON products.p_id=cart.p_id WHERE cart.book_id!=0 AND cart.s_id='$sid' GROUP BY cart.book_id") . ' Orders', $url = 'orders.php', $icon = 'mdi mdi-cart', $size = '3', 'success');

							cardMenu($lable = 'Payouts', $number = Countdata("Select * FROM payouts WHERE u_id=$sid") . ' Items', $url = "payouts.php", $icon = 'mdi mdi-wallet-outline', $size = '3', 'primary');

							//	cardMenu($lable='Payouts' ,$number=Countdata("SELECT payouts.*, user.* FROM payouts JOIN user ON user.u_id=payouts.u_id WHERE payouts.u_id=$sid").' Items', $url='payouts.php',$icon='mdi mdi-wallet-outline' ,$size='3' ,'primary' );

							//	cardMenu($lable='Images' ,$number=Countdata("Select * FROM images WHERE u_id=$sid").' Items', $url='club-images.php',$icon='mdi mdi-folder-image' ,$size='3' ,'warning' );

							//	cardMenu($lable='Lock Slots' ,$number=Countdata("Select * FROM ground WHERE u_id=$sid").' Courts', $url='court-lock.php',$icon='mdi mdi-shield-lock-outline' ,$size='3' ,'info' );


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