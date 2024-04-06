<?php
	session_start();
	include('includes/config.php');
	error_reporting(0);
	include 'includes/language.php';
	include 'includes/formdata.php';
	include 'includes/dbhelp.php';
	
	if(strlen($_SESSION['login'])==0)
	{ 
		header('location:index.php');
	}
	else{
		$adminid=$_SESSION['userid'];
		
		
		if(isset($_POST['submit']))
		{
			$password=$_POST['password'];
			$options = ['cost' => 12];
			$hashedpass=password_hash($password, PASSWORD_BCRYPT, $options);
			// new password hashing 
			$newpassword=$_POST['newpassword'];
			$newhashedpass=password_hash($newpassword, PASSWORD_BCRYPT, $options);
			
			date_default_timezone_set('Asia/Kolkata');// change according timezone
			$currentTime = date( 'd-m-Y h:i:s A', time () );
			$sql=mysqli_query($con,"SELECT password FROM  admin where id='$adminid'");
			$num=mysqli_fetch_array($sql);
			if($num>0)
			{
				
				$dbpassword=$num['password'];
				if (password_verify($password, $dbpassword)) {
					$conqq=mysqli_query($con,"update admin set password='$newhashedpass' where id='$adminid'");
					
					$msg="Password Changed Successfully";
					
				}
				else{
					$error="password not match !!";
				}
			}
			else
			{
				$error="Old Password not match !!";
			}
			
			
		}
		
		
		if(isset($_POST['updatee']))
		{
			
			$name = addslashes($_POST['name']);
			$phone = addslashes($_POST['phone']);
			$work = addslashes($_POST['work']);
			$about = addslashes($_POST['about']);
			
			$oldimage = $_POST['oldimage'];
			$file_name = $_FILES['image']['name'];
			
			if($file_name=="")
			{
				$file_name=$oldimage;
				
				}else{
				
				$file_name = $_FILES['image']['name'];
				$file_tmp = $_FILES['image']['tmp_name'];
				$temp = explode(".", $_FILES["image"]["name"]);
				$file_name = 'admin' . round(microtime(true)) . '.' . end($temp);
				
				
				if($production=="1"){
					$file_type=$_FILES['image']['type'];
					$result = $s3->putObject([
					'Bucket' => $bucketName,
					'Key'    => $file_name,
					'ContentType'    => $file_type,
					'SourceFile' => $file_tmp			
					]);
					
				}
				else{
					
					move_uploaded_file($file_tmp, $imgloc . $file_name);
				}
				
			}
			
			
			$query = mysqli_query($con, "UPDATE admin SET 
			admin_name='$name',
			admin_phone='$phone',
			admin_about='$about',
			admin_image='$file_name' WHERE id=$adminid");
			
			if ($query) {
				$ulog=$_SESSION['login'];
				$query = mysqli_query($con, "insert into adminlog(name,action) values('$ulog','City Updated')");
				
				$msg = " Profile Updated";
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
												<li class="breadcrumb-item"><a href="dashboard.php"><?php mylan("Dashboard "," لوحة القيادة"); ?></a></li>
												<li class="breadcrumb-item active"><?php mylan("Account ","الحساب "); ?></li>
											</ol>
										</div>
										<h4 class="page-title"><?php mylan("My Account ","حسابي "); ?></h4>
									</div>
								</div>
							</div>   
							<div class="row"><!-- container Start-->
								<?php include 'includes/warnings.php';?>
								
								<!-- --------------->							
								<!-- container START-->							
								<!------------------>		
								
								<div class="col-lg-6">
									<div class="card">
										<div class="card-body">
											<h4 class="header-title"><?php mylan("Change Password ","تغيير كلمة المرور "); ?></h4>
											<p class="text-muted font-14"><?php mylan("Change password for your profile ","تغيير كلمة المرور لملف التعريف الخاص بك "); ?>
												
											</p>
											<form class="form" method="post" name="chngpwd" enctype="multipart/form-data">
												
												<?php  forminput(  'Old Password',  'password',  '' ,  'required',  '12',  'text'  ); ?>
												<?php  forminput(  'New Password',  'newpassword',  '' ,  'required',  '12',  'text'  ); ?>
												<?php  forminput(  'Confirm Password',  'confirmpassword',  '' ,  'required',  '12',  'password'  ); ?>
													
												<button name="submit" class="btn btn-primary" type="submit"><?php mylan(" Change Password","تغيير كلمة المرور "); ?></button>
											</form>    
											
										</div> <!-- end card-body-->
									</div> <!-- end card-->
								</div> <!-- end col-->	
								
								
								<div class="col-lg-6">
									<div class="card">
										<div class="card-body">
											<h4 class="header-title"><?php mylan(" My Profile","ملفي "); ?></h4>
											<p class="text-muted font-14"><?php mylan("Update your profile for other reference "," تحديث ملف التعريف الخاص بك للرجوع اليها أخرى"); ?><?php echo htmlentities($adminid); ?>
												
											</p>
											<?php
												
												$query1 = mysqli_query($con, "Select * from admin WHERE id=$adminid");
												while ($row = mysqli_fetch_array($query1)) {
												?>							
												
												<form class="form" method="post" enctype="multipart/form-data">
													
													<?php  forminput(  'Name',  'name',  $row['admin_name'] ,  'required',  '12',  'text'  ); ?>
													<?php  forminput(  'Phone',  'phone',  $row['admin_phone'],  'required',  '12',  'text'  ); ?>
													<?php  formtextarea(  'About you',  'about',  $row['admin_about'],  'required',  '12',  'text'  ); ?>
													<?php  formimage(  'Profile Image',  'image',  $row['admin_image'],  '',  '6',  'oldimage',  '60',  '80',  'cover'  ); ?>
													
													
													<button name="updatee" class="btn btn-primary" type="submit"><?php mylan(" Update Profile"," تحديث الملف"); ?></button>
												</form>    
											<?php } ?>	
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
				
				<!-- demo app -->
				<script src="assets/js/pages/demo.dashboard-crm.js"></script>
				<!-- end demo js-->
			</body>
		</html>
	<?php } ?>																