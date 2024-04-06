<?php
session_start();
include('includes/config.php');
//error_reporting(0);
include 'includes/language.php';
include 'includes/dbhelp.php';
include 'includes/formdata.php';
include 'includes/country.php';
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {

    if (isset($_POST['openscreen'])) {

        //$_SESSION['sid']=$sid;
        //	$_SESSION['sname']=$sname;
        // $uid = addslashes($_POST['uid']);

        $uid =  addslashes($_POST['uid']);
        $name =  addslashes($_POST['name']);

        $_SESSION['sid'] = $uid;
        $_SESSION['sname'] = $name;
        $_SESSION['lastpage'] = 'payout-shop.php';
        header("location: payouts.php");
    }








    //Screen Operations
    $edit = 0;
    $eid = '';
    if ($_GET['edit'] == 1) {
        $edit = 1;
        $eid = intval($_GET['eid']);
        $_SESSION['pid'] = $eid;
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

    <body class="loading" data-layout-config='{"leftSideBarTheme":"<?= $menumode; ?>","layoutBoxed":false, "leftSidebarCondensed":<?= $iconmode; ?>, "leftSidebarScrollable":false,"darkMode":<?= $bodymode; ?>, "showRightSidebarOnStart": false}'>
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
                                            <li class="breadcrumb-item active">Payouts</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Manage Payouts</h4>
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
                                    <div class="card">
                                        <div class="card-body">
                                            <table data-order='[[ 5, "desc" ]]' id="basic-datatable" class="table dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Image</th>
                                                        <th>Shop</th>
                                                        <th>Contact</th>
                                                        <th>Paid</th>
                                                        <th>Pending</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    $listdata = Selectdata("Select * FROM user WHERE shop_name IS NOT NULL AND shop_name!=''");

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
                                                                <?php text($row['u_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
                                                            </td>

                                                            <td>
                                                                <?php image($row['profile_pic'], '60', '60', 'cover'); ?>
                                                            </td>

                                                            <td>
                                                                <?php text($row['shop_name'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?><br>
                                                                <?php text($projectcode . 'S0' . $row['u_id'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, ''); ?><br>
                                                            </td>

                                                            <td>
                                                                <?php text($row['name'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = 'mdi mdi-account'); ?><br>
                                                                <?php text($row['phone'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = 'mdi mdi-cellphone'); ?>

                                                                <br>
                                                                <?php text(ArrayToName($row['city'], $statelist, 'id', 'name'), $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = 'mdi mdi-map-marker-down'); ?>
                                                            </td>

                                                            <?php

                                                                $getcid = $row['u_id'];

                                                                $bookingList = Selectdata("SELECT cart.* FROM cart WHERE book_id!='0' AND cart.s_id='$getcid'");

                                                                $shopshare = 0;
                                                                $adminshare = 0;
                                                                $bookingcount = 0;
                                                                $bookingcost = 0;
                                                                $bookingtax = 0;
                                                                $sharepercentage = 0;


                                                                $shopshare_past = 0;
                                                                $adminshare_past = 0;
                                                                $bookingcount_past = 0;
                                                                $bookingcost_past = 0;
                                                                $bookingtax_past = 0;
                                                                $sharepercentage_past = 0;


                                                                for ($x = 0; $x < sizeof($bookingList); $x++) {

                                                                    if ($bookingList[$x]['admin_paid'] == 0) {
                                                                        $bookingcount = $bookingcount + 1;
                                                                        $shopshare = $shopshare + doubleval($bookingList[$x]['shop_share']);
                                                                        $adminshare = $adminshare + doubleval($bookingList[$x]['admin_share']);
                                                                        $bookingcost = $bookingcost + doubleval($bookingList[$x]['shop_share']) + doubleval($bookingList[$x]['admin_share']);
                                                                        $bookingtax = 0;
                                                                        $sharepercentage = $bookingList[$x]['shop_percentage'];
                                                                    } else {

                                                                        $bookingcount_past = $bookingcount_past + 1;
                                                                        $shopshare_past = $shopshare_past + doubleval($bookingList[$x]['shop_share']);
                                                                        $adminshare_past = $adminshare_past + doubleval($bookingList[$x]['admin_share']);
                                                                        $bookingcost = $bookingcost + doubleval($bookingList[$x]['shop_share']) + doubleval($bookingList[$x]['admin_share']);
                                                                        $bookingtax_past = 0;
                                                                        $sharepercentage_past = $bookingList[$x]['shop_percentage'];
                                                                    }
                                                                }


                                                            ?>


                                                            <td>

                                                                <small>
                                                                    <b class="text-info"><?= $shopshare_past . $currency; ?></b><br>
                                                                    <?= $bookingcount_past . ' Bookings'; ?>

                                                                </small>
                                                            </td>

                                                            <td>
                                                                <small>
                                                                    <b class="text-success"><?= $shopshare . $currency; ?></b><br>
                                                                    <?= $bookingcount . ' Bookings'; ?>

                                                                </small>
                                                            </td>

                                                            <td>

                                                                <form method="post" enctype="multipart/form-data">

                                                                    <input type="hidden" class="form-control" name="uid" value='<?= $row['u_id']; ?>'>
                                                                    <input type="hidden" class="form-control" name="name" value='<?= $row['shop_name']; ?>'>

                                                                    <button class="btn btn-light mt-2" type="submit" name="openscreen">View</button>


                                                                </form>

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