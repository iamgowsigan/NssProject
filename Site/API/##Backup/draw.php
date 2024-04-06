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
		
		
		
		//ADD
		if (isset($_POST['submit'])) {
			
			$field['d_title'] = addslashes($_POST['d_title']);
			$field['d_title_arab'] = addslashes($_POST['d_title_arab']);
			$field['d_detail'] = addslashes($_POST['d_detail']);
			$field['d_detail_arab'] = addslashes($_POST['d_detail_arab']);
			$field['d_time'] = addslashes($_POST['d_time']);
			
			
			$res = Insertdata("draw", $field);
			if ($res != 0) {
				$msg = "Success ";
				} else {
				$error = "Deleted ";
			}
		}
		
		//Update
		if (isset($_POST['update'])) {
			
			$eid = intval($_GET['eid']);
			
			
			
			
			$field['d_title'] = addslashes($_POST['d_title']);
			$field['d_title_arab'] = addslashes($_POST['d_title_arab']);
			$field['d_detail'] = addslashes($_POST['d_detail']);
			$field['d_detail_arab'] = addslashes($_POST['d_detail_arab']);
			$field['d_time'] = addslashes($_POST['d_time']);
			
			$res = Updatedata("draw", $field, "d_id =$eid");
			if ($res != 0) {
				
				$msg = "Success ";
				} else {
				$error = "something error ";
			}
		}
		
		
		//delete
		if ($_GET['action'] == 'del' && $_GET['scid']) {
			$id = intval($_GET['scid']);
			$res = Deletedata("delete from draw where d_id='$id'");
			header("location: " . $_SERVER['PHP_SELF']);
			$msg = "Deleted";
		}
		
		
		
		if(isset($_POST['drawproducts']))
		{
			
			header("location: draw-products.php");
			
		}
		
			if(isset($_POST['drawuser']))
		{
			
			header("location: draw-user.php");
			
		}
		
		
		//Active
		if ($_GET['action'] == 'status' && $_GET['scid']) {
			$scid = intval($_GET['scid']);
			$val = $_GET['val'];
			$field['d_active'] = $val;
			$res = Updatedata("draw", $field, "d_id=$scid");
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
												<li class="breadcrumb-item active">Draw</li>
											</ol>
										</div>
										<h4 class="page-title">
											Draw
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
									
									
									
									<button type="button" class="btn btn-primary m-2" data-toggle="modal" data-target="#signup-modal">Add New</button>
									
									<div class="col-lg-12">
										<div class="card">
											<div class="card-body">
												<table data-order='[[ 0, "desc" ]]' id="basic-datatable" class="table dt-responsive nowrap w-100">
													<thead>
														<tr>
															<th>ID</th>
															<th>Title</th>
															<th>Time</th>
															<th>Products</th>
															<th>Participation</th> 
															<th>
																Completed
															</th>
															<th>
																<?php mylan("Action ", "عمل"); ?>
															</th>
														</tr>
													</thead>
													<tbody>
														<?php
															
															$listdata = Selectdata("Select * FROM draw");
															
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
																				<?php text($row['d_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
																			</td>
																			
																			
																			
																			<td>
																				<?php text($row['d_title'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
																			</td>
																			
																			
																			<td>
																				<?php text($row['d_time'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
																			</td>
																			
																			<td>
																				<?php 
																					$did=$row['d_id'];
																					$countProduct=Countdata("SELECT * FROM draw_products WHERE d_id='$did'");
																					text($countProduct.' '.'Items', $strong = false, $small = true, $badge = true, $lighten = true, $outline = false, 'success'); ?>
																			</td>
																			
																			<td>
																				<?php 
																					$did=$row['d_id'];
																					$countProduct=Countdata("SELECT * FROM draw_user WHERE d_id='$did'");
																					text($countProduct.' '.'User', $strong = false, $small = true, $badge = true, $lighten = true, $outline = false, 'info'); ?>
																			</td>
																			
																			
																			<td>
																				<?php active($row['d_active'], $row['d_id']); ?>
																			</td>
																			
																			<td>
																				<?php action($row['d_id'], 'E,D'); ?>
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
													$listdata = Selectdata("Select * FROM draw WHERE d_id=$eid");
													foreach ($listdata as $row) {
														
														$_SESSION['sname'] = $row['d_title'.$mydb];
														$_SESSION['sid'] = $row['d_id'];
													?>
													
													<div class="row ml-1 mr-1">
														
														
														<div class="col-xl-4 col-lg-4">
															<div class="card widget-flat">
																<div class="card-body ">
																	
																	<div class="float-right">
																		<i class="mdi mdi-shopping widget-icon bg-success rounded-circle text-white"></i>
																	</div>
																	<h5 class="text-dark font-weight-normal mt-0" title="Number of Customers">Draw Products</h5>
																	
																	<h3 class="text-dark mt-3 mb-2"><?= mysqli_num_rows(mysqli_query($con, "SELECT * from draw_products WHERE d_id='$eid'")); ?>&nbsp;Items</h3>
																	<p class=" text-muted mb-0">
																		
																		<form id="drawproducts" method="post" enctype="multipart/form-data">
																			
																			<button style="border:none;background-color:white" type="submit" name="drawproducts"><span class="text-success mr-2"><i class="mdi mdi-dresser-outline"></i>&nbsp;&nbsp; Add / Manage</span>
																			</button>
																		</form>
																	</p>
																</div> <!-- end card-body-->
															</div> <!-- end card-->
															
															
														</div> <!-- end col-->
														
														
														
														
														
														<div class="col-xl-4 col-lg-4">
															<div class="card widget-flat">
																<div class="card-body ">
																	
																	<div class="float-right">
																		<i class="mdi mdi-ticket-account widget-icon bg-success rounded-circle text-white"></i>
																	</div>
																	<h5 class="text-dark font-weight-normal mt-0" title="Number of Customers">Draw Participation</h5>
																	
																	<h3 class="text-dark mt-3 mb-2"><?= mysqli_num_rows(mysqli_query($con, "SELECT * from draw_user WHERE d_id='$eid'")); ?>&nbsp;Items</h3>
																	<p class=" text-muted mb-0">
																		
																		<form id="drawuser" method="post" enctype="multipart/form-data">
																			
																			<button style="border:none;background-color:white" type="submit" name="drawuser"><span class="text-success mr-2"><i class="mdi mdi-dresser-outline"></i>&nbsp;&nbsp; Add / Manage</span>
																			</button>
																		</form>
																	</p>
																</div> <!-- end card-body-->
															</div> <!-- end card-->
															
															
														</div> <!-- end col-->
													</div>
													
													
													
													
													
													
													
													
													
													<form class="form" method="post" enctype="multipart/form-data">
														<div class="col-lg-12">
															<div class="card">
																<div class="card-body">
																	<div class="row">
																		
																		<?php forminput('Title', 'd_title', $row['d_title'], 'required', '6', 'text'); ?>
																		<?php forminput('Title Arabic', 'd_title_arab', $row['d_title_arab'], 'required', '6', 'text'); ?>
																		<?php formtextarea('Detail', 'd_detail', $row['d_detail'], 'required', '6', 'text'); ?>
																		<?php formtextarea('Detail Arab', 'd_detail_arab', $row['d_detail_arab'], 'required', '6', 'text'); ?>
																		<?php forminput('Time', 'd_time', $row['d_time'], '', '6', 'datetime-local'); ?>
																		
																		
																	</div>
																	<button name="update" class="btn btn-primary" type="submit">Update</button>
																	
																</div> <!-- end card-body-->
															</div> <!-- end card-->
														</div> <!-- end col-->
														
													</div> <!-- end col-->
												</form>
												
											<?php } ?>
											
										<?php } ?>
										
										<!--Edit end-->
										
										
										
										
										
										
										
										<!-- ADD new-->
										<?php addStart($size = 'xl'); ?>
										
										
		
										
										<?php forminput('Title', 'd_title', '', 'required', '6', 'text'); ?>
										<?php forminput('Title Arabic', 'd_title_arab', '', 'required', '6', 'text'); ?>
										<?php formtextarea('Detail', 'd_detail', '', 'required', '6', 'text'); ?>
										<?php formtextarea('Detail Arab', 'd_detail_arab','', 'required', '6', 'text'); ?>
										<?php forminput('Time', 'd_time', '', '', '6', 'datetime-local'); ?>
										
										
										<?php addEnd(); ?>
										
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