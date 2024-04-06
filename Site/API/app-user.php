<?php
	session_start();
	include('includes/config.php');
	error_reporting(0);
	include 'includes/language.php';
	
	if(strlen($_SESSION['login'])==0)
	{ 
		header('location:index.php');
	}
	else{
		
         if(isset($_POST['save']))
		{
			$uid = addslashes($_POST['uid']);
						
			$_SESSION['uid']=$uid;
		
			header("location: user-page.php");
			
		}
		
		if ($_GET['action'] == 'del' && $_GET['scid']) {
			$id = intval($_GET['scid']);
			$userlog = $_SESSION['login'];
			
			$query = mysqli_query($con, "delete from user where u_id='$id'");
            $msg = "Deleted ";
            $uname = $_SESSION['login'];
            $queryy = mysqli_query($con, "insert into adminlog(name,action) values('$uname','user deleted')");
			header("location: ".$_SERVER['PHP_SELF']);
			
		}
		
		
		if ($_GET['action'] == 'act' && $_GET['scid']) {
			$id = intval($_GET['scid']);
			$userlog = $_SESSION['login'];
			
			$query = mysqli_query($con, "UPDATE user SET u_active='1' WHERE u_id=$id");
			$msg = "Activated ";
			header("location: ".$_SERVER['PHP_SELF']);
			
		}
		
		if ($_GET['action'] == 'dact' && $_GET['scid']) {
			$id = intval($_GET['scid']);
			$userlog = $_SESSION['login'];
			
			$query = mysqli_query($con, "UPDATE user SET u_active='0' WHERE u_id=$id");
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
												<li class="breadcrumb-item"><a href="javascript: void(0);"><?php mylan("Dashboard "," لوحة القيادة"); ?></a></li>
												<li class="breadcrumb-item active"><?php mylan("User ","مستخدم"); ?></li>
											</ol>
										</div>
										<h4 class="page-title"><?php mylan("User Management ","إدارةالمستخدم"); ?></h4>
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
											
											<table id="basic-datatable" class="table dt-responsive nowrap w-100">
												<thead>
													<tr>
														<th>ID</th>
														<th><?php mylan("Name","اسم"); ?></th>
														<th><?php mylan("Image ","صورة "); ?></th>
														<th><?php mylan("Contact"," اتصال"); ?></th>
														<th><?php mylan("Active ","نشيط "); ?></th>
														<th><?php mylan("Action ","عمل "); ?></th>
													</tr>
												</thead>
												
												
												
												<tbody>
													<?php
														
														$query = mysqli_query($con, "Select * FROM user");
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
																		<td><?php echo htmlentities($row['u_id']); ?></td>
																		
																		<td><strong><?php echo htmlentities($row['name']); ?></strong></td>
																		
																		
																		<td>
																			<img src="<?php echo htmlentities($imgloc.$row['profile_pic']); ?>" alt="image" width="40"  class="rounded" onerror="this.src='assets/images/users/avatar-1.jpg'">
																		</td>
																		
																		<td>
																			<small>
																			<a href="tell:<?php echo htmlentities($row['phone']); ?>"><i class="mdi mdi-cellphone"></i>&nbsp;&nbsp;<?php echo htmlentities($row['phone']); ?></a><br>
																			
																			<i class="mdi mdi-map-marker-down"></i>&nbsp;&nbsp;
																			<?php 
																			include 'includes/country.php';
																	for($x=0;$x<sizeof($statelist);$x++)
																	{    
                                                                      if(strcmp($statelist[$x]['id'],$row['city'])==0){
																			echo htmlentities($statelist[$x]['name']); 

                                                                          }
																			}?>
																			</small>
																		</td>
																		<td>
																			<?php if($row['u_active']){ ?>
																				<a href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?scid=<?php echo htmlentities($row['u_id']); ?>&&action=dact" onclick="return confirm('Are you sure you want to deactivate?');"  ><span class="badge badge-outline-success"><?php mylan("Active ","نشيط "); ?></span></a>
																				<?php }else{ ?>
																				
																				<a href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?scid=<?php echo htmlentities($row['u_id']); ?>&&action=act" onclick="return confirm('Are you sure you want to activate?');"  ><span class="badge badge-outline-danger"><?php mylan("Deactive ","معطل"); ?></span></a>
																				
																			<?php } ?>
																		</td>
																		<td>
																		
																			<div class="btn-group">
																			<button type="button" class="btn btn-light dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false"> <?php mylan("More ","أكثر "); ?> <span class="caret"></span> </button>
																			<div class="dropdown-menu">
																				
																				
																				
																				<form  method="post" enctype="multipart/form-data">
																					
																					<input type="hidden" name="uid" value='<?=$row['u_id']; ?>'>
																				
																					<button style="border:none;background-color:white" type="submit" name="save"><a class="dropdown-item"><?php mylan("View / Edit ","رأي /تعديل "); ?></a></button>
																		
																				</form>
																				
																				
																				
																				<a class="dropdown-item" href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?scid=<?php echo htmlentities($row['u_id']); ?>&&action=del" onclick="return confirm('Are you sure you want to delete?');"  ><?php mylan("Delete ","حذف "); ?></a>
																				
																			</div>
																		</div>
																	
																			
																		</td>
																		
																	</tr>
																<?php } } ?>
														</tbody>
													</table> 
													
												</div> <!-- end card-body-->
											</div> <!-- end card-->
										</div> <!-- end col-->		
										
										
										
										
										<!-- Form Model-->		
										<div id="signup-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog  modal-lg modal-dialog-centered">
												<div class="modal-content">
													
													<div class="modal-body">
														<div class="text-center mt-2 mb-4">
															<a href="index.html" class="text-success">
																<span><img src="assets/images/logo-dark.png" alt="" height="18"></span>
															</a>
														</div>
														
														<form class="pl-3 pr-3"  method="post" enctype="multipart/form-data">
															<div class="row">
																
																
																<div class="col-6">
																	<div class="form-group mb-3">
																		<label for="validationCustom01"><?php mylan("Category Name  ","اسم التصنيف"); ?></label>
																		<input type="text" class="form-control" name="title" required>
																	</div>
																</div>
																
																
																
																
																<div class="col-6">
																	<div class="form-group mb-3">
																		<label for="validationCustom01"><?php mylan("Name in Arabic ","الاسم بالعربي"); ?> </label>
																		<input type="text" class="form-control" name="titlearab" required>
																	</div>
																</div>
																
																
																<div class="col-6">
																	<div class="form-group mb-3">
																		<label for="validationCustom03"><?php mylan("Image ","صورة "); ?></label>
																		<input type="file" class="form-control-file" id="exampleInputFile" name="image" required><br><br>
																	</div>
																</div>
																
																<div class="col-6">
																	<div class="form-group mb-3">
																		<label for="validationCustom03"><?php mylan("Banner ","لافتة "); ?></label>
																		<input type="file" class="form-control-file" id="exampleInputFile" name="image2"><br><br>
																	</div>
																</div>
																
															</div>
															
															<div class="form-group text-center">
																<button class="btn btn-primary" type="submit" name="submit"><?php mylan("Add Category ","إضافة فئة "); ?></button>
															</div>
															
														</form>
														
													</div>
												</div>
											</div>
										</div>
										
										<!--  Form modal end -->
										
										
										
										
										
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