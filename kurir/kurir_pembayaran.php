<?php
include '../components/connect.php';

session_start();

$kurir_id = $_SESSION['kurir_id'];

if (!isset($kurir_id)) {
    header('location: kurir_login.php');
    exit();
}

if (isset($_POST['pay'])) {
    $order_id = $_POST['order_id'];
    $update_status = $conn->prepare("UPDATE `orders` SET payment_status = 'completed'");
    $update_status->execute([$order_id]);
    $message[] = 'Order taken and payment status updated!';
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

    <title>Pembayaran</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.8/datatables.min.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="kurir_dashboard.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">YUM-YUM</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">


            <li class="nav-item ">
                <a class="nav-link" href="kurir_dashboard.php">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Dashboard</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <li class="nav-item ">
                <a class="nav-link" href="placed_orders.php">
                    <i class="fas fa-fw fa-shopping-basket"></i>
                    <span>Orders</span></a>
            </li>

            <hr class="sidebar-divider">

            <li class="nav-item active">
                <a class="nav-link" href="kurir_pembayaran.php">
                    <i class="fas fa-wallet"></i>
                    <span>Pembayaran</span></a>
            </li>

            <!-- Divider -->
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
                                $select_profile = $conn->prepare("SELECT * FROM kurir WHERE kurir_id = ?");
                                $select_profile->execute([$kurir_id]);

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

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 font-weight-bold text-primary">Data Order</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Order ID</th>
                                            <th>User ID</th>
                                            <th>Placed On</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Number</th>
                                            <th>Address</th>
                                            <th>Total Products</th>
                                            <th>Total Price</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $select_orders = $conn->prepare("SELECT * FROM orders WHERE payment_status = 'delivery' AND kurir_id = :kurir_id AND method IN ('dana', 'shoppepay', 'qris', 'gopay')");
                                        $select_orders->bindParam(':kurir_id', $kurir_id);
                                        $select_orders->execute();
                                        $no = 1;

                                        while ($res1 = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                                            $order_id = $res1['id'];
                                            $user_id = $res1['user_id'];
                                            $placed_on = $res1['placed_on'];
                                            $name = $res1['name'];
                                            $email = $res1['email'];
                                            $number = $res1['number'];
                                            $address = $res1['address'];
                                            $total_products = $res1['total_products'];
                                            $total_price = $res1['total_price'];
                                            $payment_method = $res1['method'];
                                        ?>
                                            <tr>
                                                <td><?php echo $no++ ?></td>
                                                <td><?php echo $order_id ?></td>
                                                <td><?php echo $user_id ?></td>
                                                <td><?php echo $placed_on ?></td>
                                                <td><?php echo $name ?></td>
                                                <td><?php echo $email ?></td>
                                                <td><?php echo $number ?></td>
                                                <td><?php echo $address ?></td>
                                                <td><?php echo $total_products ?></td>
                                                <td><?php echo $total_price ?></td>
                                                <td><?php echo $payment_method ?></td>
                                                <td>
                                                    <form action="" method="POST">
                                                        <input type="hidden" name="order_id" value="<?= $order_id; ?>">
                                                        <button type="submit" class="btn btn-primary btn-pay" data-order-id="<?= $order_id; ?>" data-payment-method="<?= $payment_method; ?>">Pay</button>
                                                    </form>
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




                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Yum-Yum 2024</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

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
                        <a class="btn btn-primary" href="../components/kurir_logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tambahkan skrip jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Tambahkan skrip DataTable -->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

        <!-- Inisialisasi DataTable pada tabel -->
        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable();
            });
        </script>

        <!-- Letakkan kode SweetAlert di sini -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

        <!-- Kemudian di dalam loop while -->
        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable();

                // Tambahkan event click pada tombol "Pay"
                $('.btn-pay').click(function(e) {
                    e.preventDefault();
                    var order_id = $(this).data('order-id');
                    var payment_method = $(this).data('payment-method');
                    // console.log(payment_method);

                    // Tampilkan SweetAlert sesuai dengan metode pembayaran
                    if (payment_method === 'dana') {
                        Swal.fire({
                            title: 'Pembayaran dengan Dana',
                            imageUrl: '../vendor/qrcode/Dana.jpg',
                            imageWidth: 350,
                            imageHeight: 350,
                            imageAlt: 'QR Code',
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                processPayment(payment_method, order_id);
                            }
                        });
                    } else if (payment_method === 'shoppepay') {
                        Swal.fire({
                            title: 'Pembayaran dengan ShoppePay',
                            imageUrl: '../vendor/qrcode/Shoppepay.jpg',
                            imageWidth: 350,
                            imageHeight: 350,
                            imageAlt: 'QR Code',
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                processPayment(payment_method, order_id);
                            }
                        });
                    } else if (payment_method === 'gopay') {
                        Swal.fire({
                            title: 'Pembayaran dengan GoPay',
                            imageUrl: '../vendor/qrcode/Gopay.jpg',
                            imageWidth: 350,
                            imageHeight: 350,
                            imageAlt: 'QR Code',
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                processPayment(payment_method);
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Metode pembayaran tidak dikenali.',
                        });
                    }
                });

                // Fungsi untuk memproses pembayaran
                function processPayment(payment_method, order_id) {
                    $.ajax({
                        url: 'update_status.php',
                        method: 'POST',
                        data: {
                            payment_method: payment_method,
                            order_id: order_id
                        },
                        success: function(response) {
                            Swal.fire('Success!', response, 'success');
                            location.reload();
                        },
                        error: function() {
                            Swal.fire('Error!', 'Terjadi kesalahan saat memproses pembayaran.', 'error');
                        }
                    });
                }
            });
        </script>


        <!-- Bootstrap core JavaScript-->
        <!-- <script src="../vendor/jquery/jquery.min.js"></script> -->
        <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="../js/sb-admin-2.min.js"></script>


</body>

</html>