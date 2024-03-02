<?php
include '../components/connect.php';

session_start();

$kasir_id = $_SESSION['kasir_id'];

if (!isset($kasir_id)) {
    header('location: kasir_login.php');
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

    <title>Pembayaran</title>

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
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="kasir_dashboard.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">YUM-YUM</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">


            <li class="nav-item ">
                <a class="nav-link" href="kasir_dashboard.php">
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
            <!-- Divider -->
            <hr class="sidebar-divider">


            <li class="nav-item active">
                <a class="nav-link" href="pembayaran.php">
                    <i class="fas fa-fw fa-money-check"></i>
                    <span>Pembayaran</span></a>
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
                                $select_profile = $conn->prepare("SELECT * FROM kasir WHERE kasir_id = ?");
                                $select_profile->execute([$kasir_id]);

                                if ($select_profile->rowCount() > 0) {
                                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                                    echo '<span class="mr-2 d-none d-lg-inline text-gray-600 small">' . $fetch_profile['username'] . '</span>';
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
                                    <h6 class="m-0 font-weight-bold text-primary">Data Pembayaran</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>ID Order</th>
                                            <th>Nama Menu</th>
                                            <th>Harga</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $select_orders = $conn->query("SELECT * FROM `orders` WHERE payment_status = 'cooking'");

                                        if ($select_orders->rowCount() > 0) {
                                            $no = 1;
                                            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $no; ?></td>
                                                    <td><?php echo $fetch_orders['id']; ?></td>
                                                    <td><?php echo $fetch_orders['total_products']; ?></td>
                                                    <td>Rp <?php echo number_format($fetch_orders['total_price'], 0, ',', '.'); ?></td>
                                                    <td>
                                                        <button class="btn btn-primary print-button" data-id="<?php echo $fetch_orders['id']; ?>">print</button>
                                                    </td>
                                                </tr>
                                        <?php
                                                $no++;
                                            }
                                        } else {
                                            echo '<tr><td colspan="5" class="text-center">No orders placed yet!</td></tr>';
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
                                    <a class="btn btn-primary" href="../components/kasir_logout.php">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- jQuery -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <!-- Bootstrap JS -->
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

                <!-- Tambahkan skrip DataTable -->
                <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
                <script src="https://cdn.datatables.net/v/dt/dt-1.13.8/datatables.min.js"></script>

                <script>
                    $(document).ready(function() {
                        $('#ordersTable').DataTable();

                        $('.print-button').on('click', function() {
                            var orderId = $(this).data('id');
                            console.log(orderId);
                            printReceipt(orderId);

                        });
                    });

                    function printReceipt(orderId) {
                        $.ajax({
                            type: 'GET',
                            url: 'fetch_order_details.php',
                            data: {
                                id: orderId
                            },
                            dataType: 'json',
                            success: function(data) {
                                if (data.success) {
                                    var orderDetails = data.orderDetails;

                                    var id = orderId;
                                    var placed_on = orderDetails.placed_on;
                                    var total_products = orderDetails.total_products;
                                    var total_price = orderDetails.total_price;
                                    var method = orderDetails.method;
                                    var kasir_id = orderDetails.kasir_id;

                                    var params = {
                                        id: id,
                                        placed_on: placed_on,
                                        total_products: total_products,
                                        total_price: total_price,
                                        method: method,
                                        kasir_id: kasir_id,
                                    };
                                    // console.log(params);

                                    var url = 'struk_pembayaran.php?' + $.param(params);
                                    // console.log(url);
                                    var newWindow = window.open(url, '_blank');

                                    newWindow.onload = function() {
                                        newWindow.print();

                                        // Setelah mencetak, update status pesanan menjadi completed
                                        updateOrderStatus(orderId);
                                    };
                                    // Optionally, close the new window after printing
                                    newWindow.onafterprint = function() {
                                        newWindow.close();
                                    };
                                } else {
                                    alert('Failed to fetch order details. Please try again.');
                                }
                            },
                            error: function() {
                                alert('Failed to fetch order details. Please try again.');
                            }
                        });
                    }

                    // Fungsi untuk mengupdate status pesanan
                    function updateOrderStatus(orderId) {
                        $.ajax({
                            type: 'POST', // Ganti method menjadi POST
                            url: 'update_order_status.php', // Ganti nama file PHP sesuai kebutuhan
                            data: {
                                id: orderId,
                                status: 'Ready' // Set status pesanan menjadi completed
                            },
                            success: function(response) {
                                console.log(response); // Tampilkan response dari server
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', xhr.responseText);
                                alert('Failed to update order status. Please try again.');
                            }
                        });
                    }
                </script>

</body>

</html>