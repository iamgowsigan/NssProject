<?php
session_start();
include('includes/config.php');
error_reporting(0);
include 'includes/language.php';
include 'includes/dbhelp.php';
include 'includes/formdata.php';
include 'includes/country.php';
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {

    $sid = $_SESSION['sid'];
    $sname = $_SESSION['sname'];
    //$lastpage = 'view-dealer.php';


    //ADD
    if (isset($_POST['submit'])) {

        //$file_name = Uploadimage('product_link', 'image');
        $field['p_price'] = addslashes($_POST['p_price']);
        $field['p_id'] = addslashes($_POST['p_id']);
        //$field['p_quantity'] = addslashes($_POST['p_quantity']);
        //$field['p_price'] = addslashes($_POST['p_price']);
        $field['u_id'] = $sid;

        $res = Insertdata("product_link", $field);
        if ($res != 0) {
            $msg = "Success ";
            header("location: " . $_SERVER['PHP_SELF'] . "?eid=" . $res . "&&edit=1");
        } else {
            $error = "Deleted ";
        }


    }

    //Update
    if (isset($_POST['update'])) {

        $eid = intval($_GET['eid']);
        //$oldimage = $_POST['oldimage'];
        // if ($_FILES['image']['name'] == '') {
        // 	$file_name = $oldimage;
        // } else {
        // 	$file_name = Uploadimage('products', 'image');
        // }

        $field['p_id'] = addslashes($_POST['p_id']);
        $field['p_quantity'] = addslashes($_POST['p_quantity']);
        $field['p_price'] = addslashes($_POST['p_price']);


        $res = Updatedata("product_link", $field, "pl_id =$eid");
        if ($res != 0) {

            $msg = "Success ";
        } else {
            $error = "something error ";
        }
    }


    //delete
    if ($_GET['action'] == 'del' && $_GET['scid']) {
        $id = intval($_GET['scid']);
        $res = Deletedata("delete from product_link where pl_id='$id'");
        header("location: " . $_SERVER['PHP_SELF']);
        $msg = "Deleted";
    }


    //statusactive
    if ($_GET['action'] == 'update' && $_GET['scid']) {
        $scid = intval($_GET['scid']);
        $val = $_GET['val'];
        $field['p_status'] = $val;
        $res = Updatedata("product_link", $field, "pl_id=$scid");
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Dealer</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">
                                        <?= $sname; ?> Products
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


                                <div class="col-lg-12">

                                    <a href="view-dealer.php?eid=<?= $sid; ?>&&edit=1" class="btn btn-primary mb-2">
                                        <?php mylan("Back ", "خلف "); ?>
                                    </a>

                                    <button type="button" class="btn btn-primary mb-2 float-right" data-toggle="modal"
                                        data-target="#signup-modal">Add New</button>

                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <table data-order='[[ 0, "desc" ]]' id="basic-datatable"
                                                class="table dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Image</th>
                                                        <th>Title & Subtitle</th>
                                                        <th>Price & Quantity</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    $listdata = Selectdata("Select * from product_link 
                                                    JOIN products on products.p_id = product_link.p_id where product_link.u_id= '$sid'");

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
                                                                    <?php text($row['pl_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
                                                                </td>

                                                                <td>
                                                                    <?php image($row['p_logo'], '70', '70', 'cover'); ?>
                                                                </td>

                                                                <td>
                                                                    <?php text($row['p_title'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
                                                                    <br>
                                                                    <?php text($row['p_sub'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = ''); ?>
                                                                </td>

                                                                <td>
                                                                    <?php text('₹ ' . $row['p_price'], $strong = true, $small = true, $badge = false, $lighten = false, $outline = false, 'success'); ?>
                                                                    <br>
                                                                    <?php text($row['p_quantity'] . ' Items', $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = ''); ?>
                                                                </td>

                                                                <td>
                                                                    <?php statusactive($row['pl_id'], $row['p_status'], 'M,A,D,E,O'); ?>
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
                                    $listdata = Selectdata("Select * FROM product_link WHERE pl_id=$eid");
                                    foreach ($listdata as $row) {
                                        ?>

                                        <form class="form" method="post" enctype="multipart/form-data">
                                            <div class="col-lg-12">

                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4>Personal Details</h4>
                                                        <div class="row">

                                                            <?php

                                                            $productList = Selectdata("SELECT * FROM products ORDER BY p_id ASC");
                                                            formselect('Products Title', 'p_ids', $productList, '', '6', $row['p_ids'], 'p_id', 'p_title'. $mydb);

 


                                                            forminput('Quantity', 'p_quantity', $row['p_quantity'], 'required', '4', 'text');

                                                            forminput('Original Price', '', '', '', '4', 'text');

                                                            forminput('Price', 'p_price', $row['p_price'], 'required', '4', 'text'); ?>

                                                        </div>
                                                        <button name="update" class="btn btn-primary" type="submit">Save
                                                            Profile</button>
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

                            <?php

                            $productList = Selectdata("SELECT * FROM products ORDER BY p_id ASC");
                            
                            formselect('Products Title', 'p_id', $productList, '', '12', '', 'p_id', 'p_title');
                            
                            ?>

                                                            
                            <div class="col-6">
                                <div id="show"></div>
                            </div>


                        <?php
                            
                            
                            ?>


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

            <script>
				$(document).ready(function() {

					//SubCategory
					var cate = $('.p_id').val();
			 

					$('.p_id').change(function() {
						$.ajax({
							url: "show-price.php?cate=" + cate,
							cache: true,
							success: function(result) {
								$("#show").html(result);
								//$('#sc_id').select2();
							}
						});

					});
 


				});
			</script>

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