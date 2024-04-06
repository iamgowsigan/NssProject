<?php 
	$name=$_SESSION['adminname'];	
	$profile=$_SESSION['profile'];	
	
	if (isset($_POST['submitsearch'])) {
		
		
		$searchkey = $_POST['searchkey'];
		
		if($searchkey!=""){
			$_SESSION['searchkey']=$searchkey;
			echo "<script type='text/javascript'> document.location = 'search.php?key=$searchkey'; </script>";
			}else{
			echo "<script>alert('Please enter some key to search');</script>";
		}
		
	}
	

	
?>
<div class="navbar-custom">
	<ul class="list-unstyled topbar-right-menu float-right mb-0">
		
		<li class="notification-list">
			<a class="nav-link " href="theams-settings.php">
				<i class="dripicons-gear noti-icon"></i>
			</a>
		</li>
		
		<li class="dropdown notification-list topbar-dropdown">
			<a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
				<img src="assets/images/flags/<?php echo htmlentities($mylangImg); ?>.jpg" alt="user-image" class="mr-0 mr-sm-1" height="12"> 
				<span class="align-middle d-none d-sm-inline-block"><?php echo htmlentities($mylangLable); ?></span> <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
			</a>
			<div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu">
				
				<!-- item-->
				<a href="change-lang.php?lan=En&mydb=&lable=English&limg=us&page=<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?<?php echo htmlentities($_SERVER['QUERY_STRING']); ?>" class="dropdown-item notify-item">
					<img src="assets/images/flags/us.jpg" alt="user-image" class="mr-1" height="12"> <span class="align-middle">English</span>
				</a>
				
				<a href="change-lang.php?lan=Ar&mydb=_arab&lable=Arabic&limg=uae&page=<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?<?php echo htmlentities($_SERVER['QUERY_STRING']); ?>" class="dropdown-item notify-item">
					<img src="assets/images/flags/uae.jpg" alt="user-image" class="mr-1" height="12"> <span class="align-middle">Arabic</span>
				</a>

				
			</div>
		</li>
		
		
		<li class="dropdown notification-list">
			<a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false"
			aria-expanded="false">
				<span class="account-user-avatar"> 
					
					<?php if($row['admin_image']){ ?>
						<img src="<?php echo htmlentities($imgloc.$row['admin_image']); ?>" alt="image"  class="rounded-circle">
						<?php } else{ ?>
						<img src="assets/images/avatar-1.jpg" alt="image"  class="rounded-circle">
					<?php } ?>
				</span>
				<span>
					<span class="account-user-name"><?php echo htmlentities($name); ?></span>
					<span class="account-position"><?php mylan(" Active"," نشيط"); ?></span>
				</span>
			</a>
			<div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
				<!-- item-->
				<div class=" dropdown-header noti-title">
					<h6 class="text-overflow m-0"><?php mylan(" Welcome","مرحبا "); ?> !</h6>
				</div>
				
				<!-- item-->
				<a href="myaccount.php" class="dropdown-item notify-item">
					<i class="mdi mdi-account-circle mr-1"></i>
					<span><?php mylan("My Account ","حسابي "); ?></span>
				</a>
				
				
				
				<!-- item-->
				<a href="http://www.trendsresearch.org" class="dropdown-item notify-item">
					<i class="mdi mdi-lifebuoy mr-1"></i>
					<span><?php mylan("Support ","الدعم "); ?></span>
				</a>
				
				
				
				<!-- item-->
				<a href="logout.php" class="dropdown-item notify-item">
					<i class="mdi mdi-logout mr-1"></i>
					<span><?php mylan("Logout "," تسجيل خروج"); ?></span>
				</a>
				
			</div>
		</li>
		
	</ul>
	<button class="button-menu-mobile open-left disable-btn">
		<i class="mdi mdi-menu"></i>
	</button>
	<div class="app-search">
		<form  method="post" enctype="multipart/form-data">
			<div class="input-group">
				<input type="text" class="form-control" name="searchkey" placeholder="Search...">
				<span class="mdi mdi-magnify"></span>
				<div class="input-group-append">
					<button class="btn btn-primary" type="submit" name="submitsearch"><?php mylan("Search ","يبحث "); ?></button>
				</div>
			</div>
		</form>
	</div>
</div>