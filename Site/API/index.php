<?php

session_start();
include 'includes/config.php';
include 'includes/language.php';
include 'includes/dbhelp.php';
include 'includes/formdata.php';
if (isset($_POST['login'])) {


    $uname = $_POST['username'];
    $password = $_POST['password'];

    $sql = mysqli_query($con, "SELECT * FROM admin WHERE (username='$uname' || admin_email='$uname')");
    $num = mysqli_fetch_array($sql);
    if ($num > 0) {
        $hashpassword = $num['password'];

        if (password_verify($password, $hashpassword)) {

            $_SESSION['username'] = $_POST['username'];
            $_SESSION['adminname'] = $num['admin_name'];
            $_SESSION['adminemail'] = $num['admin_email'];
            $_SESSION['userid'] = $num['id'];
            $_SESSION['login'] = $num['id'];
            $_SESSION['profile'] = $num['admin_image'];
            $_SESSION['power'] = $num['power'];

            $uid = $_SESSION['login'];


            AdminLog($num['admin_name'], 'Admin Panel Login');

            echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
        } else {
            echo "<script>alert('Wrong Password');</script>";

        }
    }
    //if username or email not found in database
    else {
        echo "<script>alert('Username not found');</script>";
    }

}
?>



<!DOCTYPE html>
<!-- Mirrored from coderthemes.com/hyper/saas/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 12 Apr 2020 02:49:47 GMT -->

<head>
    <meta charset="utf-8" />
    <title>Admin Log In |
        <?= $projectname; ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?= $projectname; ?> Super Admin Panel for Mobile Application" name="description" />
    <meta content="<?= $projectname; ?>" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/logo.png">

    <!-- App css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="light-style" />
    <link href="assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />

</head>

<body class="loading authentication-bg"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card">

                        <!-- Logo -->
                        <div class="card-header pt-3 pb-3 text-center">

                            <span><img src="assets/images/tce_logo.jpg" alt="" height="90"></span>

                            <!--   <h3 class="text-center font-weight-bold text-white"><?= $projectname; ?></h3>-->

                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <p class="text-muted">Enter your username and password to access master admin panel.
                                </p>

                            </div>


                            <form method="post">

                                <?php
                                forminput('User Name', 'username', '', '', '12', 'text', $placeholder = 'Enter user name');
                                forminput('Password', 'password', '', '', '12', 'password', $placeholder = 'Enter password');
                                ?>




                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary" name="login" type="submit"> Continue </button>
                                </div>



                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->
                    <br>
                   
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        <?= date('Y', strtotime("-1 year", time())); ?> -
        <?= date('Y'); ?> ©
        <?= $projectname; ?>
    </footer>

    <!-- bundle -->
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>

</body>

</html>