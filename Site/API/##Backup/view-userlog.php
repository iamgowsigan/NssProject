<?php
	session_start();
	include('includes/config.php');
	error_reporting(0);
	include 'includes/language.php';
	include 'includes/dbhelp.php';
	include 'includes/formdata.php';
	
	if(strlen($_SESSION['login'])==0)
	{ 
		header('location:index.php');
	}
	else{
		
		if(isset($_POST['submit']))
		{
			
			
			$name = addslashes($_POST['name']);
			$detail = addslashes($_POST['detail']);
			$price = addslashes($_POST['price']);
			
			
			$query = mysqli_query($con, "insert into lab(name,detail,price) values('$name','$detail','$price')");
			
			if ($query) {
				$ulog=$_SESSION['login'];
				$query = mysqli_query($con, "insert into adminlog(name,action) values('$ulog',$name.'Lab Added')");
				
				$msg = " Lab Test Added";
				} else {
				$error = "Something Wrong";
			}
			
			
		}
		
		if ($_GET['action'] == 'del' && $_GET['scid']) {
			$id = intval($_GET['scid']);
			$userlog = $_SESSION['login'];
			
			$query = mysqli_query($con, "delete from user where u_id='$id'");
            $msg = "Deleted ";
            $uname = $_SESSION['login'];
            $queryy = mysqli_query($con, "insert into adminlog(name,action) values('$uname','lab item deleted')");
			header("location: ".$_SERVER['PHP_SELF']);
			
		}
		
		
		if ($_GET['action'] == 'act' && $_GET['scid']) {
			$id = intval($_GET['scid']);
			$userlog = $_SESSION['login'];
			
			$query = mysqli_query($con, "UPDATE user SET active='1' WHERE u_id=$id");
			$msg = "Activated ";
			header("location: ".$_SERVER['PHP_SELF']);
			
		}
		
		if ($_GET['action'] == 'dact' && $_GET['scid']) {
			$id = intval($_GET['scid']);
			$userlog = $_SESSION['login'];
			
			$query = mysqli_query($con, "UPDATE user SET active='0' WHERE u_id=$id");
			$msg = "Deactivated ";
			header("location: ".$_SERVER['PHP_SELF']);
			
		}
		
		
		
	?>
	<!DOCTYPE html>
	<html lang="en">
		
		<head>
			<?php include 'includes/head.php';?>
			<link href="assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
			<link href="assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
		</head>	
		<body class="loading" data-layout-config='{"leftSideBarTheme":"<?=$menumode;?>","layoutBoxed":false, "leftSidebarCondensed":<?=$iconmode;?>, "leftSidebarScrollable":false,"darkMode":<?=$bodymode;?>, "showRightSidebarOnStart": false}'>
			<div class="wrapper">		
				<?php include 'includes/left-navigation.php';?>
				<div class="content-page">
					<div class="content">
						<?php include 'includes/top-menu.php';?>
						<div class="container-fluid">
							<div class="row">
								<div class="col-12">
									<div class="page-title-box">
										<div class="page-title-right">
											<ol class="breadcrumb m-0">
												<li class="breadcrumb-item"><a href="javascript: void(0);"><?=$projectname;?></a></li>
												<li class="breadcrumb-item"><a href="javascript: void(0);"><?php mylan("Dashboard ","لوحة القيادة "); ?></a></li>
												<li class="breadcrumb-item active"><?php mylan("User "," المستعمل"); ?></li>
											</ol>
										</div>
										<h4 class="page-title"><?php mylan("User Activities ","أنشطة المستخدم "); ?></h4>
									</div>
								</div>
							</div>   
							<div class="row"><!-- container Start-->
								<?php include 'includes/warnings.php';?>
								
								<!-- --------------->							
								<!-- container START-->							
								<!------------------>		
								
								
								<div class="col-lg-12">
									<div class="card">
										<div class="card-body">
											
											<table data-order='[[ 4, "desc" ]]' id="basic-datatable" class="table dt-responsive nowrap w-100">
												<thead>
													<tr>
														<th><?php mylan("ID ","هوية شخصية "); ?></th>
														<th><?php mylan("Name ","اسم "); ?></th>	
														<th><?php mylan("Action ","عمل "); ?></th>
														<th><?php mylan("Time ","زمن "); ?></th>
													</tr>
												</thead>
												
												
												<tbody>
													<?php
														
														$query = mysqli_query($con, "Select adminlog.*,admin.* FROM adminlog JOIN admin ON admin.id=adminlog.name");
														$cnt = 1;
														$rowcount = mysqli_num_rows($query);
														if ($rowcount == 0) {
														?>
														<tr>
															<td colspan="7" align="center">
																<h3 style="color:black"><?php mylan("No record found ","لا يوجد سجلات "); ?></h3>
															</td>
															<tr>
																<?php
																	} else {
																	while ($row = mysqli_fetch_array($query)) {
																	?>
																	<tr>
																		<td><?php text( $row['id'], $strong=false, $small=false, $badge=false, $lighten=false, $outline=false, '' ); ?></td>
																																				
																		<td><?php text( $row['admin_name'], $strong=true, $small=false, $badge=false, $lighten=false, $outline=false, '' ); ?></td> 
																		
																		<td><?php text( $row['action'], $strong=false, $small=true, $badge=false, $lighten=false, $outline=false, '' ); ?></td> 
																		
																		<td><?php text( $row['time'], $strong=false, $small=true, $badge=true, $lighten=true, $outline=false, 'info' ); ?></td> 
																		
																	</tr>
																<?php } } ?>
														</tbody>
													</table> 
													
												</div> <!-- end card-body-->
											</div> <!-- end card-->
										</div> <!-- end col-->		
										
										
										
										<!-- --------------->							
										<!-- container End-->							
										<!------------------>							
									</div> 
								</div> 
								<!-- Footer Start -->
								<?php include 'includes/footer.php';?>
							</div>
						</div>
						<!-- Right Sidebar -->
						<?php include 'includes/right-bar.php';?>
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