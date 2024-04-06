<?php

$uid = $_SESSION['uid'];
$power = $_SESSION['power'];
include('includes/config.php');


?>
<div class="left-side-menu">

	<a href="dashboard.php" class="logo text-center logo-light">
		<span class="logo-lg">
			<img src="assets/images/logo1.png" alt="" height="80">
		</span>
		<span class="logo-sm">
			<img src="assets/images/logo1.png" alt="" height="80">
		</span>
	</a>

	<!-- LOGO -->
	<a href="dashboard.php" class="logo text-center logo-dark">
		<span class="logo-lg">
			<img src="assets/images/logo1.png" alt="" height="80">
		</span>
		<span class="logo-sm">
			<img src="assets/images/logo1.png" alt="" height="80">
		</span>
	</a>



	<div class="h-100" id="left-side-menu-container" data-simplebar>


		<!--- Sidemenu -->
		<ul class="metismenu side-nav">



			<li class="side-nav-title side-nav-item">
				<?php mylan("SETTINGS ", " إعدادات"); ?>
			</li>

			<li class="side-nav-item">
				<a href="javascript: void(0);" class="side-nav-link">
					<i class="mdi mdi-shield-star"></i>
					<span>
						<?php mylan("Administrator ", "مدير "); ?>
					</span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					<li> <a href="myaccount.php">
							<?php mylan(" My Account", "حسابي "); ?>
						</a> </li>
					<li> <a href="account.php">
							<?php mylan("Project Info ", "معلومات المشروع "); ?>
						</a> </li>
					<li> <a href="admin-user.php">
							<?php mylan("User Management ", " إدارةالمستخدم"); ?>
						</a> </li>
					<li> <a href="view-userlog.php">
							<?php mylan("User Logs ", "سجلات المستخدم "); ?>
						</a> </li>
					<li> <a href="theams-settings.php">
							<?php mylan("Themes Settings ", "إعدادات السمات "); ?>
						</a> </li>


				</ul>
			</li>

			<li class="side-nav-item">
				<a href="javascript: void(0);" class="side-nav-link">
					<i class="mdi mdi-cellphone-android"></i>
					<span>
						<?php mylan("Application ", "طلب "); ?>
					</span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					<li> <a href="setting.php">
							<?php mylan("App Functions ", "وظائف التطبيق "); ?>
						</a> </li>

				</ul>
			</li>



			<li class="side-nav-item">
				<a href="javascript: void(0);" class="side-nav-link">
					<i class="mdi mdi-iframe-braces"></i>
					<span>
						<?php mylan("Backup Solution ", "حل احتياطي "); ?>
					</span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					<li> <a href="sql-backup.php">
							<?php mylan("SQL-Backup ", "SQL- النسخ الاحتياطي "); ?>
						</a> </li>
					<li> <a href="data-backup.php">
							<?php mylan("Media Backup ", " وسائط النسخ الاحتياطي"); ?>
						</a> </li>
				</ul>
			</li>

			<li class="side-nav-title side-nav-item">
				<?php mylan(" USER MANAGEMENT", "إدارةالمستخدم "); ?>
			</li>

			<li class="side-nav-item">
				<a href="javascript: void(0);" class="side-nav-link">
					<i class="uil-users-alt"></i>
					<span>
						<?php mylan("Students ", "المستعمل "); ?>
					</span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					<li>
						<a href="students.php">
							<?php mylan("Manage Students", "إدارة المستخدم "); ?>
						</a>
					</li>
				</ul>
			</li>

				<li class="side-nav-item">
				<a href="javascript: void(0);" class="side-nav-link">
					<i class="uil-book-reader"></i>
					<span>
						<?php mylan("Activity", "المستعمل "); ?>
					</span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					<li>
						<a href="activity.php">
							<?php mylan("Manage Activity", "إدارة المستخدم "); ?>
						</a>
					</li>
				</ul>
			</li>

			<li class="side-nav-item">
				<a href="javascript: void(0);" class="side-nav-link">
					<i class="uil-book-reader"></i>
					<span>
						<?php mylan("Faculty", "المستعمل "); ?>
					</span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					<li>
						<a href="faculty.php">
							<?php mylan("Manage Faculty ", "إدارة المستخدم "); ?>
						</a>
					</li>
				</ul>
			</li>



			<li class="side-nav-item">
				<a href="javascript: void(0);" class="side-nav-link">
					<i class="uil-users-alt"></i>
					<span>
						<?php mylan("Blood Donators ", "المستعمل "); ?>
					</span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					<li>
						<a href="blood-donators.php">
							<?php mylan("Manage Blood Donators", "إدارة المستخدم "); ?>
						</a>
					</li>
				</ul>
			</li>

			<li class="side-nav-item">
				<a href="javascript: void(0);" class="side-nav-link">
					<i class="uil-book-reader"></i>
					<span>
						<?php mylan("Attendance", "المستعمل "); ?>
					</span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					<li>
						<a href="attendance.php">
							<?php mylan("Manage Attendance", "إدارة المستخدم "); ?>
						</a>
					</li>
				</ul>
			</li>


		



			<li class="side-nav-title side-nav-item">REPORT MANAGEMENT</li>

			
			<li class="side-nav-item">
				<a href="javascript: void(0);" class="side-nav-link"><i
						class="uil-window"></i>
						<span>Reports</span>
						<span class="menu-arrow"></span>

					</a>
				<ul class="side-nav-second-level" aria-expanded="true">
					<li><a href="report-screen.php">Manage Report</a></li>
				</ul>
			</li>


			<br><br><br><br><br>



		</ul>


		<div class="clearfix"></div>

	</div>
	<!-- Sidebar -left -->

</div>