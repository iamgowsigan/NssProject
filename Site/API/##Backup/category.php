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

		$file_name = Uploadimage('category', 'image');
		$file_name2 = Uploadimage('categorybanner', 'image2');
		$field['c_title'] = addslashes($_POST['c_title']);
		$field['c_title_arab'] = addslashes($_POST['c_title_arab']);
		$field['c_image'] = $file_name;
		$field['c_banner'] = $file_name2;
		$field['u_id'] = '0';

		$res = Insertdata("category", $field);
		if ($res != 0) {
			$msg = "Success ";
		} else {
			$error = "Deleted ";
		}
	}

	//Update
	if (isset($_POST['update'])) {

		$eid = intval($_GET['eid']);
		$oldimage = $_POST['oldimage'];
		$oldimage2 = $_POST['oldimage2'];

		if ($_FILES['image']['name'] == '') {
			$file_name = $oldimage;
		} else {
			$file_name = Uploadimage('category', 'image');
		}


		if ($_FILES['image2']['name'] == '') {
            $file_name2 = $oldimage2;
        } else {
            $file_name2 = Uploadimage('categorybanner', 'image2');
        }

		$field['c_image'] = $file_name;
		$field['c_banner'] = $file_name2;



		$field['c_title'] = addslashes($_POST['c_title']);
		$field['c_title_arab'] = addslashes($_POST['c_title_arab']);
		$field['show_home'] = addslashes($_POST['show_home']);
		$field['c_image'] = $file_name;

		$res = Updatedata("category", $field, "c_id =$eid");
		if ($res != 0) {

			$msg = "Success ";
		} else {
			$error = "something error ";
		}
	}


	//delete
	if ($_GET['action'] == 'del' && $_GET['scid']) {
		$id = intval($_GET['scid']);
		$res = Deletedata("delete from category where c_id='$id'");
		header("location: " . $_SERVER['PHP_SELF']);
		$msg = "Deleted";
	}



	if(isset($_POST['subcat']))
	{

		header("location: sub-category.php");
		
	}

	
	//Active
	if ($_GET['action'] == 'status' && $_GET['scid']) {
		$scid = intval($_GET['scid']);
		$val = $_GET['val'];
		$field['c_active'] = $val;
		$res = Updatedata("category", $field, "c_id=$scid");
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
											<li class="breadcrumb-item active">Category</li>
										</ol>
									</div>
									<h4 class="page-title">
										Category
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
														<th>Image</th>
														<th>Title</th>
														<th>Subcategory</th>
														<th>Banner</th>
														<th>
															<?php mylan("Active ", "نشيط  "); ?>
														</th>
														<th>
															<?php mylan("Action ", "عمل"); ?>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php

													$listdata = Selectdata("Select * FROM category");

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
																<?php image($row['c_image'], '80', '70', 'contain'); ?>

															</td>

															<td>
																<?php text($row['c_title'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
															</td>


															<td>
																<?php
																$cid=$row['c_id'];
																$countsub = Countdata("Select * FROM sub_category WHERE c_id='$cid'");
																text($countsub.' Items', $strong = false, $small = true, $badge = true, $lighten = true, $outline = false, 'info'); ?>
															</td>

															<td>
																<?php image($row['c_banner'], '80', '30', 'cover'); ?>

															</td>



															<td>
																<?php active($row['c_active'], $row['c_id']); ?>
															</td>

															<td>
																<?php action($row['c_id'], 'E,D'); ?>
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
									$listdata = Selectdata("Select * FROM category WHERE C_id=$eid");
									foreach ($listdata as $row) {

										$_SESSION['sname'] = $row['c_title'.$mydb];
										$_SESSION['sid'] = $row['c_id'];
									?>

									<div class="row ml-1 mr-1">
										<div class="col-xl-4 col-lg-4">

											<div class="card widget-flat">
												<div class="card-body">
													<span class="float-left mr-3 mb-3 mt-3">

														<img src="<?php echo htmlentities($imgloc . $row['c_image']); ?>" style="width:70px;height:70px;object-fit:cover" class="rounded img-thumbnail" onerror="this.src='assets/images/bg-auth.jpg'">

													</span>
													<div class="media-body mt-4">

														<h4 class="mt-1 mb-1"><?= $row['c_title'.$mydb]; ?></h4>
														<p class="font-13"> CATID: <?= $row['c_id']; ?></p>

													</div>
													<!-- end media-body-->
												</div>
											</div> <!-- end card-->
										</div> <!-- end col-->

				 
										

										<div class="col-xl-4 col-lg-4">
											<div class="card widget-flat">
												<div class="card-body ">

													<div class="float-right">
														<i class="mdi mdi-book-outline widget-icon bg-success rounded-circle text-white"></i>
													</div>
													<h5 class="text-dark font-weight-normal mt-0" title="Number of Customers"><?php mylan("   Sub Category", "تصنيف فرعي "); ?></h5>

													<h3 class="text-dark mt-3 mb-2"><?= mysqli_num_rows(mysqli_query($con, "SELECT * from sub_category WHERE c_id='$eid'")); ?>&nbsp;Items</h3>
													<p class=" text-muted mb-0">

													<form id="subcat" method="post" enctype="multipart/form-data">

														<button style="border:none;background-color:white" type="submit" name="subcat"><span class="text-success mr-2"><i class="mdi mdi-dresser-outline"></i>&nbsp;&nbsp; Add / Manage</span>
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

															<?php forminput('Title', 'c_title', $row['c_title'], 'required', '6', 'text'); ?>
															<?php forminput('Title Arabic', 'c_title_arab', $row['c_title_arab'], 'required', '6', 'text'); ?>

															<?php formselect('Show in Home', 'show_home', $yesNolist, 'required', '8', $row['show_home'], 'id', 'lable' . $mydb); ?>



															<?php formimage('Image', 'image', $row['c_image'], '', '6', 'oldimage', '50', '50', 'cover'); ?>
															<?php formimage('Banner', 'image2', $row['c_banner'], '', '6', 'oldimage2', '80', '30', 'cover'); ?>

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


						<?php forminput('Name', 'c_title', '', 'required', '6', 'text'); ?>
						<?php forminput('Arab', 'c_title_arab', '', 'required', '6', 'text'); ?>

						<?php formimage('Image', 'image', '', 'required', '6', '', '', '', 'cover'); ?>
						<?php formimage('Banner', 'image2', '', 'required', '6', '', '', '', 'cover'); ?>


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