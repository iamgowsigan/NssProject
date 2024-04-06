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
	if (isset($_POST['submittt'])) {

		$file_name = Uploadimage('category', 'image');

		$name = addslashes($_POST['name']);
		$phone = addslashes($_POST['phone']);
		$password = addslashes($_POST['password']);

		$user = addslashes($_POST['user']);
		$email = $user . "@admin.com";

		$options = ['cost' => 12];
		$newhashedpass = password_hash($password, PASSWORD_BCRYPT, $options);

		$field['admin_image'] = $file_name;

		$res = Insertdata("admin", $field);
		if ($res != 0) {
			$msg = "Success ";
		} else {
			$error = "Deleted ";
		}


	}


	//ADD
	if (isset($_POST['submit'])) {

		$file_name = Uploadimage('admin', 'image');

		$field['username'] = addslashes($_POST['username']);
		$password = addslashes($_POST['password']);
		$field['admin_name'] = addslashes($_POST['admin_name']);
		$field['admin_email'] = addslashes($_POST['admin_email']);
		$field['admin_phone'] = addslashes($_POST['admin_phone']);
		$field['role'] = addslashes($_POST['role']);
		$field['admin_image'] = $file_name;

		$options = ['cost' => 12];
		$newhashedpass = password_hash($password, PASSWORD_BCRYPT, $options);
		$field['password'] = $newhashedpass;


		$res = Insertdata("admin", $field);
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
		if ($_FILES['image']['name'] == '') {
			$file_name = $oldimage;
		} else {
			$file_name = Uploadimage('admin', 'image');
		}


		$field['username'] = addslashes($_POST['username']);
		$password = addslashes($_POST['password']);
		$field['admin_name'] = addslashes($_POST['admin_name']);
		$field['admin_email'] = addslashes($_POST['admin_email']);
		$field['admin_phone'] = addslashes($_POST['admin_phone']);
		$field['admin_about'] = addslashes($_POST['admin_about']);
		$field['role'] = addslashes($_POST['role']);
		$field['admin_image'] = $file_name;

		$options = ['cost' => 12];
		$newhashedpass = password_hash($password, PASSWORD_BCRYPT, $options);
		$field['password'] = $newhashedpass;

		$res = Updatedata("admin", $field, "id =$eid");
		if ($res != 0) {

			$msg = "Success ";
		} else {
			$error = "something error ";
		}
	}



	if ($_GET['action'] == 'del' && $_GET['scid']) {
		$id = intval($_GET['scid']);
		$userlog = $_SESSION['login'];

		$query = mysqli_query($con, "delete from admin  where id='$id'");
		$msg = "Deleted ";
		$uname = $_SESSION['login'];
		$queryy = mysqli_query($con, "insert into adminlog(name,action) values('$uname','User deleted')");
		header("location: " . $_SERVER['PHP_SELF']);

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
											<li class="breadcrumb-item"><a href="javascript: void(0);">
													<?php mylan("Dashboard ", " لوحة القيادة"); ?>
												</a></li>
											<li class="breadcrumb-item active">
												<?php mylan(" User", "المستعمل "); ?>
											</li>
										</ol>
									</div>
									<h4 class="page-title">
										<?php mylan("User Management ", " إدارةالمستخدم"); ?>
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

								<button type="button" class="btn btn-primary m-2" data-toggle="modal"
									data-target="#signup-modal">
									<?php mylan("Add User ", "إضافة مستخدم "); ?>
								</button>
								<div class="col-lg-12">
									<div class="card">
										<div class="card-body">

											<table id="basic-datatable" class="table dt-responsive nowrap w-100">
												<thead>
													<tr>
														<th>
															<?php mylan(" User ID", " معرف المستخدم"); ?>
														</th>
														<th>
															<?php mylan(" Profile", "الملف الشخصي "); ?>
														</th>
														<th>
															<?php mylan(" Name", " اسم"); ?>
														</th>

														<th>
															<?php mylan(" Phone", "هاتف "); ?>
														</th>
														<th>
															<?php mylan(" Email", "بريد إلكتروني "); ?>
														</th>
														<th>
															Role
														</th>
														<th>
															<?php mylan(" Action", " عمل"); ?>
														</th>
													</tr>
												</thead>


												<tbody>
													<?php

													$query = mysqli_query($con, "Select * FROM admin WHERE power=0");
													$cnt = 1;
													$rowcount = mysqli_num_rows($query);
													if ($rowcount == 0) {
														?>
														<tr>
															<td colspan="7" align="center">
																<h3 style="color:black">
																	<?php mylan("No record found ", " لا يوجد سجلات"); ?>
																</h3>
															</td>
														<tr>
															<?php
													} else {
														while ($row = mysqli_fetch_array($query)) {
															?>
															<tr>

																<td>
																	<?php text($row['id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
																</td>

																<td>
																	<?php image($row['admin_image'], '50', '60', 'cover'); ?>
																</td>

																<td>
																	<?php text($row['admin_name'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
																</td>

																<td>
																	<?php text($row['admin_phone'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
																</td>

																<td>
																	<?php text($row['admin_email'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
																</td>

																<td>
																	<?php text(ArrayToName($row['role'],$rolelist,'id','lable'), $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?>
																</td>

																<td>
																	<?php action($row['id'], 'E,D'); ?>
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


								<a class="btn btn-primary m-2" href="<?= $_SERVER['PHP_SELF']; ?>?edit=0">Back</a>

								<div class="col-lg-12">
									<div class="card">
										<div class="card-body">

											<?php
											$listdata = Selectdata("Select * FROM admin WHERE id=$eid");
											foreach ($listdata as $row) {
												?>
												<form class="form" method="post" enctype="multipart/form-data">
													<div class="row">

														<?php forminput('Name', 'admin_name', $row['admin_name'], 'required', '6', 'text'); ?>
														<?php forminput('Phone Number', 'admin_phone', $row['admin_phone'], 'required', '6', 'text'); ?>
														<?php forminput('Username', 'username', $row['username'], 'required', '6', 'text'); ?>
														<?php forminput('Password', 'password', $row['password'], 'required', '6', 'password'); ?>
														<?php forminput('Email', 'admin_email', $row['admin_email'], 'required', '6', 'text'); ?>
														<?php formselect('Role',  'role', $rolelist, 'required','6', $row['role'], 'id', 'lable' ); ?>


														<?php formtextarea('About', 'admin_about', $row['admin_about'], 'required', '12', 'text'); ?>
														
														<?php formimage('Image', 'image', $row['admin_image'], '', '6', 'oldimage', '60', '80', 'cover'); ?>

													</div>
													<button name="update" class="btn btn-primary" type="submit">Update</button>
												</form>
											<?php } ?>
										</div> <!-- end card-body-->
									</div> <!-- end card-->
								</div> <!-- end col-->

							<?php } ?>

							<!--Edit end-->



							<!-- ADD new-->
							<?php addStart($size='xl'); ?>


													<?php forminput('Name', 'username', '', 'required', '6', 'text'); ?>
													<?php forminput('Password', 'password', '', 'required', '6', 'text'); ?>
													<?php forminput('Name', 'admin_name', '', 'required', '6', 'text'); ?>
													<?php forminput('Email', 'admin_email', '', 'required', '6', 'text'); ?>
													<?php forminput('Phone', 'admin_phone', '', 'required', '6', 'text'); ?>
													<?php formselect('Role',  'role', $rolelist, 'required','6', '', 'id', 'lable' ); ?>
													<?php formtextarea('About User', 'admin_about', '', 'required', '6', 'text'); ?>

													<?php formimage('Image', 'image', '', 'required', '6', '', '', '', 'cover'); ?>


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
	</body>

	</html>
<?php } ?>