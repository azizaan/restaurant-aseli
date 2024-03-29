<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_GET['process'])) {
   $id = $_GET['process'];

   // Ambil data top up berdasarkan id
   $queryTopUp = "SELECT * FROM top_up WHERE id = :id";
   $stmtTopUp = $conn->prepare($queryTopUp);
   $stmtTopUp->execute([':id' => $id]);
   $topUpData = $stmtTopUp->fetch(PDO::FETCH_ASSOC);

   // Ambil saldo yang ingin ditambahkan
   $saldoToAdd = $topUpData['saldo'];
   $userId = $topUpData['user_id'];

   // Update saldo di tabel users
   $queryUpdateSaldo = "UPDATE users SET saldo = COALESCE(saldo, 0) + :saldoToAdd WHERE id = :userId";
   $stmtUpdateSaldo = $conn->prepare($queryUpdateSaldo);
   $stmtUpdateSaldo->execute([':saldoToAdd' => $saldoToAdd, ':userId' => $userId]);

   // Ubah status menjadi completed
   $queryUpdateStatus = "UPDATE top_up SET status = 'completed' WHERE id = :id";
   $stmtUpdateStatus = $conn->prepare($queryUpdateStatus);
   $stmtUpdateStatus->execute([':id' => $id]);

   // Redirect kembali ke halaman admin setelah proses selesai
   header("Location: amount.php");
   exit();
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

   <title>Amount user management</title>

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
         <li class="nav-item active">
            <a class="nav-link" href="amount.php">
               <i class="fas fa-fw fa-file-alt"></i>
               <span>Amount</span></a>
         </li>
         <hr class="sidebar-divider">
         <!-- Nav Item - laporan -->
         <li class="nav-item">
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

               <!-- DataTales Example -->
               <div class="card shadow mb-4">
                  <div class="card-header py-3">
                     <div class="row align-items-center">
                        <div class="col">
                           <h6 class="m-0 font-weight-bold text-primary">Data Saldo User</h6>
                        </div>
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                           <thead>
                              <tr>
                                 <th>NO</th>
                                 <th>User Id</th>
                                 <th>Saldo</th>
                                 <th>Status</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              $query = "SELECT * FROM top_up ORDER BY CASE WHEN status = 'pending' THEN 1 ELSE 2 END, status";
                              $stmt = $conn->query($query);
                              $no = 1;

                              while ($res1 = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                 $id = $res1['id'];
                                 $user_id = $res1['user_id'];
                                 $saldo = $res1['saldo'];
                                 $status = $res1['status'];
                              ?>
                                 <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $user_id ?></td>
                                    <td><?php echo $saldo ?></td>
                                    <td><?php echo $status ?></td>
                                    <td>
                                       <?php if ($status === 'pending') : ?>
                                          <!-- Mengubah link "delete" menjadi "process" -->
                                          <a href="?process=<?= $id; ?>" class="btn btn-primary" onclick="return confirm('Process this top up?');">Process</a>
                                       <?php else : ?>
                                          <!-- Tombol akan disabled jika status sudah completed -->
                                          <button class="btn btn-success" disabled>Completed</button>
                                       <?php endif; ?>
                                    </td>
                                 </tr>
                              <?php
                              }
                              ?>

                           </tbody>
                        </table>
                     </div>
                  </div>

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
            <!-- Modal -->
            <div class="modal fade" id="addAdminModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Admin</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <div class="modal-body">
                        <!-- Form untuk menambah data admin -->
                        <form id="addAdminForm">
                           <div class="form-group">
                              <label for="adminName">Nama Admin</label>
                              <input type="text" class="form-control" id="adminName" name="adminName" required>
                           </div>
                           <div class="form-group">
                              <label for="adminPassword">Password</label>
                              <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
                           </div>
                           <!-- ... (Tambah field sesuai kebutuhan) ... -->
                           <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
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


</body>

</html>