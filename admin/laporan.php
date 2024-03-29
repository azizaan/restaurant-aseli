<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Laporan</title>

    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Yum Yum</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item ">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Management Account</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Management Account</h6>
                        <a class="collapse-item" href="users_accounts.php">Users</a>
                        <a class="collapse-item" href="admin_accounts.php">Admin</a>
                        <a class="collapse-item" href="kurir_accounts.php">Kurir</a>
                        <a class="collapse-item" href="kasir_accounts.php">Kasir</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="products.php">
                    <i class="fas fa-fw fa-utensils"></i>
                    <span>Menu</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="placed_orders.php">
                    <i class="fas fa-fw fa-shopping-basket"></i>
                    <span>Orders</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="pembayaran.php">
                    <i class="fas fa-fw fa-money-check"></i>
                    <span>Pembayaran</span></a>
            </li>
            <hr class="sidebar-divider">

            <!-- Nav Item - Charts -->
            <li class="nav-item ">
                <a class="nav-link" href="amount.php">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Amount</span></a>
            </li>

            <hr class="sidebar-divider">

            <li class="nav-item active">
                <a class="nav-link" href="laporan.php">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Laporan</span></a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>



        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php
                                $select_profile = $conn->prepare("SELECT * FROM admin WHERE id = ?");
                                $select_profile->execute([$admin_id]);

                                if ($select_profile->rowCount() > 0) {
                                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                                    echo '<span class="mr-2 d-none d-lg-inline text-gray-600 small">' . $fetch_profile['name'] . '</span>';
                                } else {
                                    // Default text if no user profile is found
                                    echo '<span class="mr-2 d-none d-lg-inline text-gray-600 small">User</span>';
                                }
                                ?>
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->



                <div class="container-fluid">

                    <h1 class="h3 mb-4 text-gray-800">Laporan</h1>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="bulan">Pilih Bulan:</label>
                            <select class="form-control" id="bulan" name="bulan">
                                <?php
                                // Ambil nomor bulan saat ini
                                $bulan_sekarang = date('m');

                                // Daftar nama bulan dalam bahasa Indonesia
                                $nama_bulan = [
                                    '01' => 'Januari',
                                    '02' => 'Februari',
                                    '03' => 'Maret',
                                    '04' => 'April',
                                    '05' => 'Mei',
                                    '06' => 'Juni',
                                    '07' => 'Juli',
                                    '08' => 'Agustus',
                                    '09' => 'September',
                                    '10' => 'Oktober',
                                    '11' => 'November',
                                    '12' => 'Desember'
                                ];

                                // Loop untuk menampilkan opsi bulan
                                foreach ($nama_bulan as $nomor_bulan => $nama) {
                                    // Tentukan apakah bulan ini adalah bulan yang dipilih saat ini
                                    $selected = ($nomor_bulan == $bulan_sekarang) ? 'selected' : '';

                                    // Tampilkan opsi bulan
                                    echo "<option value=\"$nomor_bulan\" $selected>$nama</option>";
                                }
                                ?>

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="tahun">Pilih Tahun:</label>
                            <select class="form-control" id="tahun" name="tahun">
                                <?php
                                // Ambil tahun saat ini
                                $tahun_sekarang = date('Y');

                                // Buat daftar tahun untuk 10 tahun ke depan dan 10 tahun ke belakang dari tahun saat ini
                                $tahun_list = range($tahun_sekarang - 5, $tahun_sekarang + 5);

                                // Loop untuk menampilkan setiap tahun dalam opsi dropdown
                                foreach ($tahun_list as $tahun_option) {
                                    // Tentukan apakah tahun ini adalah tahun yang dipilih sebelumnya
                                    $selected = ($tahun_option == $tahun_sekarang) ? 'selected' : '';

                                    // Tampilkan opsi tahun
                                    echo "<option value=\"$tahun_option\" $selected>$tahun_option</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>


                    <button style="margin-top: 1rem;" class="btn btn-primary" onclick="generateExcel()">Generate Excel</button>
                </div>



                <!-- Logout Modal-->
                <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                <a class="btn btn-primary" href="../components/admin_logout.php">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


</body>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>



<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../js/sb-admin-2.min.js"></script>

<script>
    function generateExcel() {
        var bulan = document.getElementById("bulan").value;
        var tahun = document.getElementById("tahun").value;
        window.location.href = "generate_excel.php?bulan=" + bulan + "&tahun=" + tahun;
    }
</script>

</body>

</html>