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
		
		$adminid=$_SESSION['userid'];
		
		if(isset($_POST['updatee']))
		{
			
			$menumode = addslashes($_POST['menumode']);
			$bodymode = addslashes($_POST['bodymode']);
			$iconmode = addslashes($_POST['iconmode']);
			$activitylist = addslashes($_POST['activitylist']);
		
			
			$query = mysqli_query($con, "UPDATE admin_theam SET 
			left_menu='$menumode',
			left_menu_icon='$iconmode',
			content_area='$bodymode',
			activity_list='$activitylist'
			WHERE t_id=1");
			
			if ($query) {
				
				$msg = " Updated";
				} else {
				$error = "Something Wrong";
			}
		}
		
		
	?>
	
	<!DOCTYPE html>
	<html lang="en">
		
		<head>
			<?php include 'includes/head.php';?>
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
												<li class="breadcrumb-item"><a href="dashboard.php"><?php mylan("Settings ","إعدادات "); ?></a></li>
												<li class="breadcrumb-item active"><?php mylan("Themes ","ثيمات "); ?></li>
											</ol>
										</div>
										<h4 class="page-title"><?php mylan("Theme Settings","إعدادات الموضوع"); ?></h4>
									</div>
								</div>
							</div>   
							<div class="row"><!-- container Start-->
								<?php include 'includes/warnings.php';?>
								
								<!-- --------------->							
								<!-- container START-->							
								<!------------------>		
								
								<?php
									
									$query1 = mysqli_query($con, "Select * from admin_theam WHERE t_id=1");
									while ($row = mysqli_fetch_array($query1)) {
									?>	
									<form class="form" method="post" enctype="multipart/form-data">
										
										
										<div class="col-lg-12">
											<div class="card">
												<div class="card-body">
													<h4 class="header-title"></h4>
												<div class="row">
													<div class="col-12">
														
														<div class="form-group mb-3">
															<label for="validationCustom01"><?php mylan("Left side Menu Color ","لون قائمة الجانب الأيسر "); ?></label>
															<select class="form-control select2" data-toggle="select2"  name="menumode">
																<option <?php if(strpos($row['left_menu'],'light')!==false)echo"selected";?> value="light"><?php mylan("Light Color ","لون فاتح "); ?></option>
																<option <?php if(strpos($row['left_menu'],'dark')!==false)echo"selected";?> value="dark"><?php mylan("Dark Color ","لون غامق "); ?></option>
																<option <?php if(strpos($row['left_menu'],'default')!==false)echo"selected";?> value="default"><?php mylan(" Blue Color","اللون الأزرق "); ?></option>
																
															</select>
														</div>
													</div>
													
													
													<div class="col-6">
														<div class="form-group mb-3">
															<label for="validationCustom01"><?php mylan("Content Area Color ","لون منطقة المحتوى "); ?></label>
															<select class="form-control select2" data-toggle="select2"  name="bodymode">
																
																<option <?php if(strpos($row['content_area'],'false')!==false)echo"selected";?> value="false"><?php mylan("Light Mode "," وضع الضوء"); ?></option>
																<option <?php if(strpos($row['content_area'],'true')!==false)echo"selected";?> value="true"><?php mylan("Dark Mode ","الوضع الداكن "); ?></option>
																
															</select>
														</div>
													</div>
													
													
													<div class="col-6">
														<div class="form-group mb-3">
															<label for="validationCustom01"><?php mylan("Show Icon Only "," إظهار الأيقونة فقط"); ?></label>
															<select class="form-control select2" data-toggle="select2"  name="iconmode">
																<option <?php if(strpos($row['left_menu_icon'],'true')!==false)echo"selected";?> value="true"><?php mylan("Yes ","نعم "); ?></option>
																<option <?php if(strpos($row['left_menu_icon'],'false')!==false)echo"selected";?> value="false"><?php mylan(" No"," لا"); ?></option>
																
															</select>
														</div>
													</div>
													
													<?php  forminput(  'Activity List Count',  'activitylist',  $row['activity_list'] ,  'required',  '6',  'number'  ); ?>
																								
												</div>
												<button name="updatee" class="btn btn-primary" type="submit"><?php mylan("Save Settings ","احفظ التغييرات"); ?></button>
												
												
											</div> <!-- end card-body-->
										</div> <!-- end card-->
									</div> <!-- end col-->		
									
								</form>   
								
							<?php } ?>	
							
							
							
							
							
							
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
			
			<!-- demo app -->
			<script src="assets/js/pages/demo.dashboard-crm.js"></script>
			<!-- end demo js-->
		</body>
	</html>
<?php } ?>	