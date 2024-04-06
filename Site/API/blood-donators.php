<?php
session_start();
include ('includes/config.php');
error_reporting(0);
include 'includes/language.php';
include 'includes/dbhelp.php';
include 'includes/formdata.php';
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {



    //ADD
    if (isset($_POST['submit'])) {

        $file_name = Uploadimage('blood', 'image');
        $field['b_name'] = addslashes($_POST['b_name']);
        $field['b_age'] = addslashes($_POST['b_age']);
        $field['b_grp'] = addslashes($_POST['b_grp']);
        $field['b_hospital'] = addslashes($_POST['b_hospital']);
        $field['b_desc'] = addslashes($_POST['b_desc']);
        $field['b_city'] = addslashes($_POST['b_city']);
        $field['bd_name'] = addslashes($_POST['bd_name']);
        $field['bd_phone'] = addslashes($_POST['bd_phone']);
        $field['bd_city'] = addslashes($_POST['bd_city']);
        $field['b_phone'] = addslashes($_POST['b_phone']);
        $field['b_image'] = $file_name;

        $res = Insertdata("blood", $field);
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
        $oldimage = $_POST['oldimage'];
        if ($_FILES['image']['name'] == '') {
            $file_name = $oldimage;
        } else {
            $file_name = Uploadimage('blood', 'image');
        }

        $field['b_name'] = addslashes($_POST['b_name']);
        $field['b_age'] = addslashes($_POST['b_age']);
        $field['b_grp'] = addslashes($_POST['b_grp']);
        $field['b_hospital'] = addslashes($_POST['b_hospital']);
        $field['b_desc'] = addslashes($_POST['b_desc']);
        $field['b_city'] = addslashes($_POST['b_city']);
        $field['bd_name'] = addslashes($_POST['bd_name']);
        $field['bd_phone'] = addslashes($_POST['bd_phone']);
        $field['bd_city'] = addslashes($_POST['bd_city']);
        $field['b_phone'] = addslashes($_POST['b_phone']);
        $field['b_image'] = $file_name;

        $res = Updatedata("blood", $field, "b_id =$eid");
        if ($res != 0) {

            $msg = "Success ";
        } else {
            $error = "something error ";
        }
    }


    //delete
    if ($_GET['action'] == 'del' && $_GET['scid']) {
        $id = intval($_GET['scid']);
        $res = Deletedata("delete from blood where b_id='$id'");
        header("location: " . $_SERVER['PHP_SELF']);
        $msg = "Deleted";
    }


    //Active
    if ($_GET['action'] == 'status' && $_GET['scid']) {
        $scid = intval($_GET['scid']);
        $val = $_GET['val'];
        $field['b_status'] = $val;
        $res = Updatedata("blood", $field, "b_id=$scid");
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
                                            <li class="breadcrumb-item active">Blood Requirements</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">
                                        Manage Blood Requirements
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



                                <button type="button" class="btn btn-primary m-2" data-toggle="modal"
                                    data-target="#signup-modal">Add New</button>

                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <table data-order='[[ 0, "desc" ]]' id="basic-datatable"
                                                class="table dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Image</th>
                                                        <th>Name & phone</th>
                                                        <th>Blood Grp & Age</th>
                                                        <th>Donor Details</th>
                                                        <th>Status</th>
                                                        <th>
                                                            <?php mylan("Action ", "عمل"); ?>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    $listdata = Selectdata("Select * FROM blood");

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
                                                                    <?php text($row['b_id'], $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
                                                                </td>

                                                                <td>
                                                                    <?php image($row['b_image'], '90', '70', 'cover'); ?>
                                                                </td>

                                                                <td>
                                                                    <?php text($row['b_name'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
                                                                    <br>
                                                                    <?php text($row['b_phone'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = 'mdi mdi-cellphone'); ?>
                                                                </td>

                                                                <td>
                                                                    <?php text('Group : ' . $row['b_grp'], $strong = false, $small = true, $badge = true, $lighten = false, $outline = false, 'info'); ?>
                                                                    <br>
                                                                    <?php text('Age : ' . $row['b_age'], $strong = false, $small = true, $badge = true, $lighten = false, $outline = false, 'info'); ?>
                                                                </td>

                                                                <td>
                                                                    <?php text($row['bd_name'], $strong = true, $small = false, $badge = false, $lighten = false, $outline = false, ''); ?>
                                                                    <br>
                                                                    <?php text($row['bd_phone'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = 'mdi mdi-cellphone'); ?>
                                                                    <br>
                                                                    <?php text($row['bd_city'], $strong = false, $small = true, $badge = false, $lighten = false, $outline = false, '', $icon = 'mdi mdi-map-marker-down'); ?>
                                                                </td>
                                                                
                                                                <td>
                                                                    <?php
                                                                    $url = $_SERVER['PHP_SELF'] . '?scid=' . $row['b_id'] .
                                                                        '&&action=status&val=';
                                                                    if ($row['b_status']) {
                                                                        echo '<a href="' . $url . '0"><span
                                                                            class="badge badge-outline-success">Donated</span></a>';
                                                                    } else {
                                                                        echo '<a href="' . $url . '1"><span
                                                                            class="badge badge-outline-danger">Not Donated</span></a>';
                                                                    }
                                                                    ?>
                                                                </td>



                                                                <td>
                                                                    <?php action($row['b_id'], 'E,D'); ?>
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
                                    $listdata = Selectdata("Select * FROM blood WHERE b_id=$eid");
                                    foreach ($listdata as $row) {
                                        ?>

                                        <form class="form" method="post" enctype="multipart/form-data">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">

                                                            <?php forminput('Name', 'b_name', $row['b_name'], 'required', '6', 'text'); ?>
                                                            <?php forminput('Blood group', 'b_grp', $row['b_grp'], 'required', '6', 'text'); ?>
                                                            <?php forminput('Age', 'b_age', $row['b_age'], 'required', '6', 'text'); ?>
                                                            <?php forminput('Hospital', 'b_hospital', $row['b_hospital'], 'required', '6', 'text'); ?>
                                                            <?php forminput('City', 'b_city', $row['b_city'], 'required', '6', 'text'); ?>
                                                            <?php forminput('Phone', 'b_phone', $row['b_phone'], 'required', '6', 'text'); ?>
                                                            <?php forminput('Donor Name', 'bd_name', $row['bd_name'], '', '6', 'text'); ?>
                                                            <?php forminput('Donor Phone', 'bd_phone', $row['bd_phone'], '', '6', 'text'); ?>
                                                            <?php forminput('Donor City', 'bd_city', $row['bd_city'], '', '6', 'text'); ?>
                                                            <?php formtextarea('Description', 'b_desc', $row['b_desc'], 'required', '6', 'text'); ?>

                                                            <?php formimage('Image', 'image', $row['b_image'], '', '6', 'oldimage', '80', '50', 'cover'); ?>

                                                        </div>
                                                        <button name="update" class="btn btn-primary" type="submit">Update</button>

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


                            <?php forminput('Name', 'b_name', '', 'required', '6', 'text'); ?>
                            <?php forminput('Blood group', 'b_grp', '', 'required', '6', 'text'); ?>


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