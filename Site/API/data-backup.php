<?php

include 'includes/config.php';
error_reporting(0);
include 'includes/language.php';
define('VERSION', '0.1.1');

$timestart = microtime(true);
$GLOBALS['status'] = array();



if (isset($_POST['dozip'])) {

    $zippath = !empty($_POST['zippath']) ? strip_tags($_POST['zippath']) : '.';
    // Resulting zipfile e.g. zipper--2016-07-23--11-55.zip.
    $zipfile = '../mec/databackup' . '.zip';
    Zipper::zipDir('../mec/', $zipfile);
}

if (isset($_POST['submitsubcat'])) {
    $path = "../mec/databackup.zip";
    if (file_exists($path)) {
        unlink($path);
        $GLOBALS['status'] = array('error' => 'success: File Deleted.');
		$msg="Backup Deleted";
    } else {
        $GLOBALS['status'] = array('error' => 'Error: Backup not exist.');
		$warning="No Backup found";
    }

}

$timeend = microtime(true);
$time = round($timeend - $timestart, 4);



class Zipper
{

    private static function folderToZip($folder, &$zipFile, $exclusiveLength)
    {
        $handle = opendir($folder);

        while (false !== $f = readdir($handle)) {
            // Check for local/parent path or zipping file itself and skip.
            if ($f != '.' && $f != '..' && $f != basename(__FILE__)) {
                $filePath = "$folder/$f";
                // Remove prefix from file path before add to zip.
                $localPath = substr($filePath, $exclusiveLength);

                if (is_file($filePath)) {
                    $zipFile->addFile($filePath, $localPath);
                } elseif (is_dir($filePath)) {
                    // Add sub-directory.
                    $zipFile->addEmptyDir($localPath);
                    self::folderToZip($filePath, $zipFile, $exclusiveLength);
                }
            }
        }
        closedir($handle);
    }

    public static function zipDir($sourcePath, $outZipPath)
    {
        $pathInfo = pathinfo($sourcePath);
        $parentPath = $pathInfo['dirname'];
        $dirName = $pathInfo['basename'];

        $z = new ZipArchive();
        $z->open($outZipPath, ZipArchive::CREATE);
        $z->addEmptyDir($dirName);
        if ($sourcePath == $dirName) {
            self::folderToZip($sourcePath, $z, 0);
        } else {
            self::folderToZip($sourcePath, $z, strlen("$parentPath/"));
        }
        $z->close();
		
        $GLOBALS['status'] = array('success' => 'Successfully created archive ' . $outZipPath);
		header('location:data-backup.php');
		
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
												<li class="breadcrumb-item"><a href="dashboard.php"><?php mylan("Dashboard ","لوحة القيادة "); ?></a></li>
												<li class="breadcrumb-item active"><?php mylan("Account ","  الحساب  "); ?></li>
											</ol>
										</div>
										<h4 class="page-title"><?php mylan("Project Info ","معلومات المشروع "); ?></h4>
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
											
											
											
                                <p class="status status--<?php echo strtoupper(key($GLOBALS['status'])); ?>">
                                    <?php mylan("Status: ","حالة: "); ?> <?php echo reset($GLOBALS['status']); ?><br />
                                    <span class="small"><?php mylan("Processing Time: ","وقت المعالجة: "); ?> <?php echo $time; ?> <?php mylan("seconds ","ثواني "); ?></span></p>


                                <form action="" method="POST">
                                    <fieldset>
                                        
                                        <label for="zippath"></label>
                                        <input type="hidden" name="zippath" class="form-field" />
                                        <p class="info"><?php mylan("Before Backup, Delete old backup file ","قبل النسخ الاحتياطي ، احذف ملف النسخ الاحتياطي القديم "); ?></p>
										
										
										<?php $path = "../mec/databackup.zip";
											  if (file_exists($path)) {  ?>
											  
											  <button type="submit" class="btn btn-danger "
													name="submitsubcat"><?php mylan("Delete Old Backup ","حذف النسخة الاحتياطية القديمة "); ?></button>
													
												<a class="btn btn-success" href="../mec/databackup.zip"><?php mylan("Download Backup ","تنزيل النسخ الاحتياطي  "); ?></a>
											  
											  
											  <?php } else{ ?>
										
                                        
										<input type="submit" name="dozip" class="btn btn-primary btn-rounded"
                                            value="Backup Now" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
											
											<?php } ?>
                                        
                                    </fieldset>
                                </form>

											
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
	