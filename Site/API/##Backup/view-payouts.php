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


	$bid = $_GET['sid'];

	$sid = $_SESSION['sid'];
	$sname = $_SESSION['sname'];






?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<?php include 'includes/head.php'; ?>
		<link href="assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="assets/calender/css/rescalendar.css">

		<style>




		</style>
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
											<li class="breadcrumb-item active">Payouts</li>
										</ol>
									</div>
									<h4 class="page-title"><?= $sname; ?> Paid Bookings</small></h4>
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
									<div class="row">

										<div class="col-1">
											<a href="payouts.php" class="btn btn-primary m-2"><?php mylan("Back ", "خلف "); ?></a>
										</div>





									</div>


									<div class="card">

										<div class="card-body">

											<table data-order='[[ 0, "desc" ]]' id="basic-datatable" class="table dt-responsive nowrap w-100">
												<thead>
													<tr>
														<th>ID</th>
														<th>Image</th>
														<th>Title</th>
														<th>User</th>

														<th>Cost</th>
														<th>Coupon</th>
														<th>Payment</th>
														<th>Share</th>
														<th>Dated</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$userclass = '';
													if ($getpage == 'all') {
														$userclass = '';
													}
													if ($getpage == "user") {
														$userclass = 'AND booking.u_id=' . $sid;
													}

													$listdata = Selectdata("SELECT cart.*, products.*,user.name,user.phone,user.city FROM cart JOIN products ON products.p_id=cart.p_id JOIN user ON user.u_id=cart.u_id WHERE cart.admin_paid='$bid'");

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
																<?php text($row['c_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>

															<td>
																<?php
																image($row['p_image'], '70', '80', 'cover'); ?>
															</td>

															<td>
																<?php text($row['p_title'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?><br>
																<?php text($projectcode . 'P0' . $row['p_id'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?><br>

															</td>

															<td>
																<?php text($row['name'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>

																<br>
																<?php text($row['country'] . $row['phone'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
																<br>
																<?php text(ArrayToName($row['city'], $statelist, 'id', 'name'), $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>

															<td>
																<small>
																	<?php text(($row['purchase_price'] * $row['quantity']) . $currency, $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>&nbsp;
																	<br>
																	<?php text($row['quantity'] . ' Items', $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?><br>

																</small>
															</td>

															<td>
																<small>
																	<?php if($row['coupon_price']!=0)text( '-'.$row['coupon_price']  . $currency, $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, 'danger'); ?>
																</small>
															</td>

															<td>
																<small>
																	<?php text(($row['shop_share']+$row['admin_share']). $currency, $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
																</small>
															</td>

															<td>
																<small>
																Shop : <?php text($row['shop_share']. $currency, $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?><br>
																Admin : <?php text($row['admin_share']. $currency, $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?><br>
																Percent : <?php text($row['shop_percentage'].'%', $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
																</small>
															</td>

															<td>
																<small>
																	<?php text($row['c_dated'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
																</small>
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

			<script>
				function courtClick() {
					document.getElementById('dated').value = '';
					document.getElementById('duration').value = '';

					document.getElementById('Show').style.display = 'none';
					document.getElementById('summary').style.display = 'none';



				}
			</script>




			<script>
				function dateClick() {

					var dated = $("#dated").val();
					var courtid = $("#courtid").val();
					document.getElementById('duration').value = '';
					document.getElementById('summary').style.display = 'none';

					if (dated != '' && courtid != '' && courtid != null) {
						document.getElementById('Show').style.display = 'inline';

						$.ajax({
							url: "drop-booked.php?book_date=" + dated + "&gid=" + courtid,
							cache: true,
							success: function(result) {
								$("#Show").html(result);

							}
						});

					}

				}
			</script>


			<script>
				function timeClick() {





					$('#duration').val('');
					document.getElementById('summary').style.display = 'none';
					document.getElementById('duration').style.display = 'none';
					document.getElementById('duration').style.display = 'inline';



				}
			</script>


			<script>
				function showSummary() {

					var duration = $("#duration").val();
					var dated = $("#dated").val();
					var courtid = $("#courtid").val();
					var timeslot = $("#timed").val();
					var tax = $('#applytax').is(":checked");

					//alert("--!"+tax);

					if (duration != '' && dated != '' && courtid != '' && timeslot != '' && timeslot != null && dated != null) {
						document.getElementById('summary').style.display = 'inline';
						$.ajax({
							url: "drop-summary.php?gid=" + courtid + "&duration=" + duration + "&book_date=" + dated + "&timeslot=" + timeslot + "&tax=" + tax,
							cache: true,
							success: function(result) {
								$("#summary").html(result);

							}
						});

					}
				}
			</script>

			<script src="assets/calender/js/rescalendar.js"></script>

			<script>
				$(function() {



					$('#my_calendar_en').rescalendar({
						id: 'my_calendar_en',
						format: 'YYYY-MM-DD',
						refDate: '<?= $getDate; ?>',
						jumpSize: 10,
						disabledDays: ['2019-01-01', '2019-01-07'],
						//disabledWeekDays: [5,6],

						dataKeyField: 'name',
						dataKeyValues: ['item1', 'item2', 'item3', 'item4', 'item5']

					});








				});
			</script>



			<script>
				$('.book_date').change(function() {

					var book_date = $('.book_date').val();

					$.ajax({
						url: "drop.php?book_date=" + book_date,
						cache: true,
						success: function(result) {
							$("#Show").html(result);

						}
					});
				});
			</script>

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