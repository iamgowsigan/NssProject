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
											<li class="breadcrumb-item active">Delivery</li>
										</ol>
									</div>
									<h4 class="page-title">
										Delivery Management
									</h4>
								</div>
							</div>
						</div>
						<div class="row"><!-- container Start-->
							<?php include 'includes/warnings.php'; ?>

							<!-- --------------->
							<!-- container START-->
							<!------------------>

							<?php include 'delivery-calculation.php';?>

							<div class="col-xl-4 col-lg-12">
									<div class="card">
										<div class="card-body">
											<h4 class="header-title mt-1"><?php mylan("Cash on Delivery ","الدفع عند الاستلام "); ?></h4>
											
											<div class="table-responsive">
												<table class="table table-sm table-centered mb-0 font-14">
													<thead class="thead-light">
														<tr>
															<th><?php mylan("Delivered List ","قائمة تسليمها "); ?></th>
															<th style="width: 30%;"></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td><?php mylan("Delivery Charge ","رسوم التوصيل "); ?></td>
															<td><?=$coddeliverycost.' '.$currency;?></td>
														</tr>
														<tr>
															<td><?php mylan("Delivery Count ","عدد التسليم "); ?></td>
															<td><?=$coddeliverycount.' Bookings';?></td>
														</tr>
														<tr>
															<td><?php mylan("Product Cost ","تكلفة المنتج "); ?></td>
															<td><?=$coddeliveryproductcost.' '.$currency;?></td>
														</tr>
														
													</tbody>
												</table>
											</div> <!-- end table-responsive-->
											
											<div class="table-responsive">
												<table class="table table-sm table-centered mb-0 font-14">
													<thead class="thead-light">
														<tr>
															<th><?php mylan("Pending List ","قائمة معلقة "); ?></th>
															<th style="width: 30%;"></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td><?php mylan("Delivery Charge ","رسوم التوصيل "); ?></td>
															<td><?=$codpendingcost.' '.$currency;?></td>
														</tr>
														<tr>
															<td><?php mylan("Delivery Count ","عدد التسليم "); ?></td>
															<td><?=$codpendingcount.' Bookings';?></td>
														</tr>
														<tr>
															<td><?php mylan("Product Cost ","تكلفة المنتج "); ?></td>
															<td><?=$codpendingproductcost.' '.$currency;?></td>
														</tr>
													</tbody>
												</table>
											</div> <!-- end table-responsive-->
											
										</div> <!-- end card-body-->
									</div> <!-- end card-->
								</div>
								
								
								<div class="col-xl-4 col-lg-12">
									<div class="card">
										<div class="card-body">
											
											<h4 class="header-title mt-1"><?php mylan("Online Pay Delivery ","تسليم الدفع عبر الإنترنت "); ?></h4>
											
											<div class="table-responsive">
												<table class="table table-sm table-centered mb-0 font-14">
													<thead class="thead-light">
														<tr>
															<th><?php mylan("Delivered List ","قائمة تسليمها "); ?></th>
															<th style="width: 30%;"></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td><?php mylan("Delivery Charge ","رسوم التوصيل "); ?></td>
															<td><?=$paydeliverycost.' '.$currency;?></td>
														</tr>
														<tr>
															<td><?php mylan("Delivery Count ","عدد التسليم "); ?></td>
															<td><?=$paydeliverycount.' Bookings';?></td>
														</tr>
														<tr>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
														</tr>
														
													</tbody>
												</table>
											</div> <!-- end table-responsive-->
											
											<div class="table-responsive">
												<table class="table table-sm table-centered mb-0 font-14">
													<thead class="thead-light">
														<tr>
															<th><?php mylan("Pending List ","قائمة معلقة "); ?></th>
															<th style="width: 30%;"></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td><?php mylan("Delivery Charge ","رسوم التوصيل "); ?></td>
															<td><?=$paypendingcost.' '.$currency;?></td>
														</tr>
														<tr>
															<td><?php mylan("Delivery Count ","عدد التسليم "); ?></td>
															<td><?=$paypendingcount.' Bookings';?></td>
														</tr>
														<tr>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
														</tr>
													</tbody>
												</table>
											</div> <!-- end table-responsive-->
											
										</div> <!-- end card-body-->
									</div> <!-- end card-->
								</div>
								
								<div class="col-xl-4 col-lg-12">
									<div class="card">
										<div class="card-body">
											
											<h4 class="header-title mt-1"><?php mylan("Overall Summary ","ملخص شامل "); ?></h4>
											
											<div class="table-responsive">
												<table class="table table-sm table-centered mb-0 font-14">
													<thead style="color:white;">
														<tr style="background-color:#FF6464;">
															<th><?php mylan("Delivered List "," قائمة تسليمها"); ?></th>
															<th style="width: 30%;"></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td><?php mylan("Delivery Charge ","رسوم التوصيل "); ?></td>
															<td><?=$totalDeliveredDeliveryCharge.' '.$currency;?></td>
														</tr>
														<tr>
															<td><?php mylan("Delivery Count ","عدد التسليم "); ?></td>
															<td><?=$totalDeliveredCont.' Bookings';?></td>
														</tr>
														<tr>
															<td><?php mylan("Product Cost ","تكلفة المنتج "); ?></td>
															<td><?=$totalDeliveredProductCost.' '.$currency;?></td>
														</tr>
														
													</tbody>
												</table>
											</div> <!-- end table-responsive-->
											
											<div class="table-responsive">
												<table class="table table-sm table-centered mb-0 font-14">
													<thead style="color:white;">
														<tr style="background-color:#8DEE68;">
															<th><?php mylan("Pending List ","قائمة معلقة "); ?></th>
															<th style="width: 30%;"></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td><?php mylan("Delivery Charge ","رسوم التوصيل "); ?></td>
															<td><?=$totalPendingDeliveryCharge.' '.$currency;?></td>
														</tr>
														<tr>
															<td><?php mylan("Delivery Count ","عدد التسليم "); ?></td>
															<td><?=$totalPendingCont.' Bookings';?></td>
														</tr>
														<tr>
															<td><?php mylan("Product Cost ","تكلفة المنتج "); ?></td>
															<td><?=$totalPendingProductCost.' '.$currency;?></td>
														</tr>
														
													</tbody>
												</table>
											</div> <!-- end table-responsive-->
											
										</div> <!-- end card-body-->
									</div> <!-- end card-->
								</div>
								
								
					
								
								<div class="col-lg-4"><a href="delivery-pending.php">
									<div class="card text-white bg-primary overflow-hidden">
										<div class="card-body">
											<div class="toll-free-box text-center">
												<h4> <i class="mdi mdi-truck-check"></i> <b><?=$totalPendingCont;?></b> <?php mylan("Pending Order ","طلب معلق "); ?> </h4>
											</div>
										</div> <!-- end card-body-->
									</div></a>
								</div> <!-- end col-->
								
								
								
								<div class="col-lg-4"><a href="delivery-delivered.php">
									<div class="card text-white bg-primary overflow-hidden">
										<div class="card-body">
											<div class="toll-free-box text-center">
												<h4> <i class="mdi mdi-door-closed"></i><b><?=$totalDeliveredCont;?></b> <?php mylan("Delivered Order ","طلب تسليم "); ?> </h4>
											</div>
										</div> <!-- end card-body-->
									</div></a>
								</div> <!-- end col-->
								
						


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
			<script src="assets/js/vendor/summernote-bs4.min.js"></script>
			<!-- Summernote demo -->
			<script src="assets/js/pages/demo.summernote.js"></script>
	</body>

	</html>
<?php } ?>