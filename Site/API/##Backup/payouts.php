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

 

	$sid = $_SESSION['sid'];
	$lastpage = $_SESSION['lastpage'];

	$userdetail = Selectdata("SELECT * FROM user WHERE u_id=$sid");
	$bookingList = Selectdata("SELECT cart.* FROM cart WHERE book_id!='0' AND cart.s_id='$sid'");
	$shopshare=0;
	$adminshare=0;
	$bookingcount=0;
	$bookingcost=0;
	$bookingtax=0;
	$sharepercentage=0;
	$coupon=0;
	$couponcount=0;


	$shopshare_past=0;
	$adminshare_past=0;
	$bookingcount_past=0;
	$bookingcost_past=0;
	$bookingtax_past=0;
	$sharepercentage_past=0;
	$coupon_past=0;
	$couponcount_past=0;



	for($x=0;$x<sizeof($bookingList);$x++){

		if($bookingList[$x]['admin_paid']==0){
			$bookingcount=$bookingcount+1;
			$shopshare=$shopshare+doubleval($bookingList[$x]['shop_share']);
			$adminshare=$adminshare+doubleval($bookingList[$x]['admin_share']);
			$bookingcost=$bookingcost+doubleval($bookingList[$x]['shop_share'])+doubleval($bookingList[$x]['admin_share']);
			$bookingtax=0;
			$sharepercentage=$bookingList[$x]['shop_percentage'];
			$coupon=$coupon+doubleval($bookingList[$x]['coupon_price']);
			if($bookingList[$x]['coupon_price']!=0)$couponcount=$couponcount+1;

		}else{

			$bookingcount_past=$bookingcount_past+1;
			$shopshare_past=$shopshare_past+doubleval($bookingList[$x]['shop_share']);
			$adminshare_past=$adminshare_past+doubleval($bookingList[$x]['admin_share']);
			$bookingcost_past=$bookingcost_past+doubleval($bookingList[$x]['shop_share'])+doubleval($bookingList[$x]['admin_share']);
			$bookingtax_past=0;
			$sharepercentage_past=$bookingList[$x]['shop_percentage'];
			$coupon_past=$coupon_past+doubleval($bookingList[$x]['coupon_price']);
			if($bookingList[$x]['coupon_price']!=0)$couponcount_past=$couponcount_past+1;
		}
	
	}




	//ADD
 


	if(isset($_POST['savenote']))
	{
		


		$field['u_id'] = $sid;
		$field['booking_count'] = addslashes($_POST['booking_count']);
		$field['total_amount'] = addslashes($_POST['total_amount']);
		$field['admin_share'] = addslashes($_POST['admin_share']);
		$field['vendor_share'] = addslashes($_POST['vendor_share']);
		$field['share_percentage'] = addslashes($_POST['share_percentage']);
		$field['amount_sent'] = addslashes($_POST['amount_sent']);
		$field['po_detail'] = addslashes($_POST['po_detail']);
		$field['payment_mode'] = 'MANUAL';
		$field['coupon_count'] = addslashes($_POST['coupon_count']);
		$field['coupon_price'] = addslashes($_POST['coupon_price']);

		$res = Insertdata("payouts", $field);
		if ($res != 0) {
			//mysqli_query($con, "UPDATE booking JOIN ground ON ground.g_id=booking.g_id SET b_admindone='$res' WHERE ground.u_id='$sid'");
			mysqli_query($con, "UPDATE cart SET admin_paid='$res', admin_paid_date=NOW() WHERE cart.s_id='$sid'");
			$msg = "Success ";
			header("location: " . $_SERVER['PHP_SELF']);
		} else {
			$error = "Deleted ";
		}
		
	}

 


	//delete
	if ($_GET['action'] == 'del' && $_GET['scid']) {
		$id = intval($_GET['scid']);
		$res = Deletedata("delete from payouts where po_id='$id'");
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
											<li class="breadcrumb-item active">Payouts</li>
										</ol>
									</div>
									<div>
										<h4 class="page-title">Manage Payouts</h4>

									</div>
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

								<div class="col-xl-12 col-lg-12">

								<?php if($lastpage=='payout-shop.php'){ ?>
									<a href="payout-shop.php" class="btn btn-primary m-2"><?php mylan("Back ", "خلف "); ?></a>
									<?php }else{ ?>
										<a href="view-shop.php?eid=<?= $sid; ?>&&edit=1" class="btn btn-primary m-2"><?php mylan("Back ", "خلف "); ?></a>
									<?php } ?>
					
							 

									<div class="card widget-flat h-75">
										<div class="card-body">
											<div class="row mb-2">

								 

												<div class="col-sm-2">

													<small>
														<h4>
															<?= $userdetail[0]['shop_name']; ?>
														</h4>
														<b>
															<?= $projectcode; ?>S0
															<?= $userdetail[0]['u_id']; ?>
														</b><br>
														<small>
														
														<a href="tell:<?=$userdetail[0]['phone']; ?>"><i class="mdi mdi-cellphone"></i>&nbsp;&nbsp;<?php echo htmlentities($userdetail[0]['phone']); ?></a><br>
														<i class="mdi mdi-email-multiple"></i>&nbsp;&nbsp;<?=$userdetail[0]['email']; ?><br>	
														
														
													</small>

													</small>

												</div> <!-- end col-->

												<div class="col-sm-2">


													<small>
													<h5>Bank Account</h5>
														<?=$userdetail[0]['bank_holder']; ?><br>
														<b><?=$userdetail[0]['bank_ac']; ?></b><br>
														<?=$userdetail[0]['bank_name']; ?><br>
														<?=$userdetail[0]['bank_code']; ?><br>
														


													</small>

												</div> <!-- end col-->



												<div class="col-sm-2">

 



												<small>
													<h5 class="text-success">Payout History</h5>
													
														<table>
															<tr><td>Bookings Count</td><td>&nbsp;:&nbsp;</td><td><b><?=$bookingcount_past.' Bookings';?></b></td></tr>
															<tr><td>Booking Cost</td><td>&nbsp;:&nbsp;</td><td><b><?=$bookingcost_past.$currency;?></b></td></tr>
															<tr><td>Coupon Cost</td><td>&nbsp;:&nbsp;</td><td><b><?=$coupon_past.$currency;?></b></td></tr>
															<tr><td>Coupon Count</td><td>&nbsp;:&nbsp;</td><td><b><?=$couponcount_past.' Coupons';?></b></td></tr>
															<tr><td>Admin Share</td><td>&nbsp;:&nbsp;</td><td><b><?=$adminshare_past.$currency;?></b></td></tr>
															<tr><td>Vendor Share</td><td>&nbsp;:&nbsp;</td><td><b><?=$shopshare_past.$currency;?></b></td></tr>
															<tr><td>TAX Collected</td><td>&nbsp;:&nbsp;</td><td><b><?=$bookingtax_past.$currency;?></b></td></tr>
 
														</table>
														
														


													</small>

												</div> <!-- end col-->

												<div class="col-sm-2">

												<small >
													<h5 class="text-danger">Pending Payout</h5>
													
													<table>
															<tr><td>Bookings Count</td><td>&nbsp;:&nbsp;</td><td><b><?=$bookingcount.' Bookings';?></b></td></tr>
															<tr><td>Booking Cost</td><td>&nbsp;:&nbsp;</td><td><b><?=$bookingcost.$currency;?></b></td></tr>
															<tr><td>Coupon Cost</td><td>&nbsp;:&nbsp;</td><td><b><?=$coupon.$currency;?></b></td></tr>
															<tr><td>Coupon Count</td><td>&nbsp;:&nbsp;</td><td><b><?=$couponcount.' Coupons';?></b></td></tr>
															<tr><td>Admin Share</td><td>&nbsp;:&nbsp;</td><td><b><?=$adminshare.$currency;?></b></td></tr>
															<tr><td>Vendor Share</td><td>&nbsp;:&nbsp;</td><td><b><?=$shopshare.$currency;?></b></td></tr>
															<tr><td>TAX Collected</td><td>&nbsp;:&nbsp;</td><td><b><?=$bookingtax.$currency;?></b></td></tr>
														</table> 

													</small>

													

												</div> <!-- end col-->


												
												<div class="col-sm-4">

												<?php if($bookingcount!=0){ ?>
												<small>
													<h5  >Make Payment</h5>
												 
														<form  method="post" enctype="multipart/form-data">
													 
																		<input type="hidden" class="form-control"  name="booking_count" value='<?=$bookingcount; ?>'>
																		<input type="hidden" class="form-control"  name="total_amount" value='<?=$bookingcost; ?>'>
																		<input type="hidden" class="form-control"  name="admin_share" value='<?=$adminshare; ?>'>
																		<input type="hidden" class="form-control"  name="vendor_share" value='<?=$shopshare; ?>'>
																		<input type="hidden" class="form-control"  name="share_percentage" value='<?=$sharepercentage; ?>'>
																		<input type="hidden" class="form-control"  name="amount_sent" value='<?=$shopshare; ?>'>
																		<input type="hidden" class="form-control"  name="coupon_count" value='<?=$couponcount; ?>'>
																		<input type="hidden" class="form-control"  name="coupon_price" value='<?=$coupon; ?>'>

																		<textarea class="form-control"  name="po_detail" placeholder="Your payment summary"></textarea>
																		
																	 
																		<button class="btn btn-light mt-2" type="submit" name="savenote">Make as Paid</button>
																		
																 
															</form>

													</small>

													<?php } ?>

												</div> <!-- end col-->

												

											</div> <!-- end row -->


										</div> <!-- end card-body-->
									</div> <!-- end card-->
								</div> <!-- end col-->

								<div class="col-lg-12">


									<?php // echo print_r($bookdetail);?>
									<div class="card">
										<div class="card-body">
											<table data-order='[[ 0, "desc" ]]' id="basic-datatable"
												class="table dt-responsive nowrap w-100">
												<thead>
													<tr>
														<th>ID</th>
														<th>Paid</th>
														<th>Bookings</th>
														<th>Coupons</th>
														<th>Total</th>
														<th>Vendor</th>
														<th>Admin</th>
														<th>Detail</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php

													$listdata = Selectdata("SELECT payouts.* FROM payouts  where u_id=$sid");

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

																<td><?php text($row['po_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?></td>

																<td><?php text($row['vendor_share'].$currency, $strong = true, $small = false, $badge = true, $lighten = false, $outline = false, 'success'); ?></td>

																<td><?php text($row['booking_count'].' Bookings', $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?></td>

																<td>
																	<?php if($row['coupon_price']!=0 && $row['coupon_price']!='')text($row['coupon_price'].$currency.' ('.$row['coupon_count'].' '.'Time )', $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
																
																</td>


																<td><?php text($row['total_amount'].$currency, $strong = true, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?></td>
																<td><?php text($row['vendor_share'].$currency, $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?></td>
																<td><?php text($row['admin_share'].$currency, $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?></td>
																<td><?php text($row['po_detail'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?><br>
																<?php text($row['po_dated'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
															
															</td>


												
																<td>
																	<?php action($row['po_id'],"V"); ?>
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