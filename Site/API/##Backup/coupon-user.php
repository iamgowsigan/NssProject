<?php
	session_start();
	include('includes/config.php');
	//error_reporting(0);
	include 'includes/language.php';
	include 'includes/dbhelp.php';
	include 'includes/formdata.php';
	include 'includes/country.php';
	if(strlen($_SESSION['login'])==0)
	{ 
		header('location:index.php');
	}
	else{
		
 
		$couponid = intval($_GET['id']);

 

		if(isset($_POST['save']))
		{
			$uid = addslashes($_POST['uid']);
			
			$_SESSION['uid']=$uid;
			
			header("location: user-page.php");
			
		}
		
		


		
		
		//delete
		if ($_GET['action'] == 'del' && $_GET['scid']) {
			$id = intval($_GET['scid']);
			$res=Deletedata("delete from coupon_used where cu_id='$id'");
			header("location: ".$_SERVER['PHP_SELF']);
			$msg = "Deleted";
		}
		
		

		
		
		//Screen Operations
		$edit=0;
		$eid='';
		if ($_GET['edit'] == 1) {
			$edit=1;
			$eid=intval($_GET['eid']);
			$_SESSION['pid']=$eid;
		}
		
		if ($_GET['edit'] == 0) {
			$edit=0;
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
												<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
												<li class="breadcrumb-item active">Usage</li>
											</ol>
										</div>
										<h4 class="page-title"> Coupon Usage</h4>
									</div>
								</div>
							</div>   
							<div class="row"><!-- container Start-->
								<?php include 'includes/warnings.php';?>
								
								<!-- --------------->							
								<!-- container START-->							
								<!------------------>		
								
								
								
								<!--LIST VIEW-->
								<?php if($edit==0){ ?> 
									
									
									<div class="col-lg-12">
									<a href="coupons.php?eid=<?= $couponid; ?>&&edit=1" class="btn btn-primary m-2"><?php mylan("Back ", "خلف "); ?></a>
										<div class="card">
											<div class="card-body">
												<table data-order='[[ 0, "desc" ]]' id="basic-datatable" class="table dt-responsive nowrap w-100">
													<thead>
														<tr>
															<th>ID</th>
															<th>Image</th>
															<th>Name</th>
															<th>Contact</th>
															<th>Dated</th>	
														</tr>
													</thead>
													<tbody>
														<?php
															
															$listdata=Selectdata("Select coupon_used.*,user.* FROM coupon_used JOIN user ON user.u_id=coupon_used.u_id");
															
															if (sizeof($listdata) == 0) {
															?>
															<tr>
																<td colspan="7" align="center">
																	<h3 style="color:black"><?php mylan("No record found ","لا يوجد سجلات "); ?></h3>
																</td>
																<tr>
																	<?php
																		} else {
																		foreach ($listdata as $row) {
																			
																		?>
																		<tr>
																			
																			<td><?php text( $row['u_id'], $strong=false, $small=false, $badge=false, $lighten=false, $outline=false, '' ); ?></td>
																			
																			<td><?php image( $row['profile_pic'],'60','60','cover' ); ?></td>
																			
																			<td><?php text( $row['name'], $strong=true, $small=false, $badge=false, $lighten=false, $outline=false, '' ); ?>
																			<br><?php text( ArrayToName($row['city'],$statelist,'id','name'), $strong=false, $small=true, $badge=false, $lighten=false, $outline=false, '' ,$icon='mdi mdi-map-marker-down'); ?>
																			</td>
																			
																			<td><?php text( $row['phone'], $strong=false, $small=true, $badge=false, $lighten=false, $outline=false, '',$icon='mdi mdi-cellphone' ); ?>
																				<br><?php text( $row['email'], $strong=false, $small=true, $badge=false, $lighten=false, $outline=false, '',$icon='mdi mdi-email' ); ?>
																			</td>
																			
																			<td><?php text( $row['cu_dated'], $strong=false, $small=true, $badge=false, $lighten=false, $outline=false, '' ); ?></td>
																			
																		 
																			
																		</tr>
																	<?php } } ?>
															</tbody>
														</table> 
													</div> <!-- end card-body-->
												</div> <!-- end card-->
											</div> <!-- end col-->		
										<?php  } ?>
										<!--LIST VIEW END-->
										
										
										<!--EDIT VIEW-->
										<?php if($edit==1){ ?>
											
											<div class="col-lg-12">
												<a class="btn btn-primary m-2" href="<?=$_SERVER['PHP_SELF']; ?>?edit=0" >Back</a>
												
												
												<?php
													$listdata=Selectdata("Select * FROM user WHERE u_id=$eid");
													foreach ($listdata as $row) {
													?>
													
													<form class="form" method="post" enctype="multipart/form-data">
														<div class="col-lg-12">
															<div class="card">
																<div class="card-body">
																	<h4>User Details</h4>
																	<div class="row">
																		
																		<?php  forminput(  'Name',  'name',  $row['name'],  'required',  '6',  'text'  ); ?>
																		<?php  forminput(  'Phone number',  'phone',  $row['phone'],  'required',  '6',  'text'  ); ?>
																		<?php  forminput(  'Email',  'email',  $row['email'],  'required',  '6',  'text'  ); ?>
																		
																		<div class="col-6">
																		<div class="form-group mb-3">
																			<label for="validationCustom01"><?php mylan("Gender"," جنس"); ?></label>
																			<select class="form-control select2" data-toggle="select2" name="gender">
																				<option <?php if($userdetail[0]['gender'] == 'male') {echo 'selected';}?> value="male"><?php mylan(" "," "); ?>Male</option>
																				<option <?php if($userdetail[0]['gender'] == 'female') {echo 'selected';}?> value="female"><?php mylan(" "," "); ?>Female</option>
																			</select>
																		</div>
																		</div>
																		
																		<?php  
																			include 'includes/country.php';
																		formselect(  'Country',  'country', $countrylist, 'required','6', $row['country'], 'phonecode', 'name' ); ?>
																		
																		<?php  
																			include 'includes/country.php';
																		formselect(  'City',  'city', $statelist, 'required','6', $row['city'], 'id', 'name' ); ?>
																		
																		
																		<?php  formimage(  'Profile',  'image',  $row['profile_pic'],  '',  '6',  'oldimage',  '80',  '50',  'cover');?>
																		
																		
																	</div>
																	<button name="update" class="btn btn-primary" type="submit">Update</button>
																</div> <!-- end card-body-->
															</div> <!-- end card-->
														</div> <!-- end col-->		
														
													</div> <!-- end col-->		
												</form>    
												
											<?php }  ?>
											
										<?php  } ?>
										
										<!--Edit end-->
										
										
										
										
										
										
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