<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
};



if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/' . $fetch_delete_image['image']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:products.php');
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

   <title>Products menu</title>

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
            <div class="sidebar-brand-text mx-3">Yum-Yum</div>
         </a>

         <!-- Divider -->
         <hr class="sidebar-divider my-0">


         <!-- Nav Item - Pages Collapse Menu -->
         <li class="nav-item">
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
         <li class="nav-item active">
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
         <li class="nav-item">
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
                           <h6 class="m-0 font-weight-bold text-primary">Product Data</h6>
                        </div>
                        <div class="col text-right">
                           <button class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">Add Product</button>
                        </div>
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                           <thead>
                              <tr>
                                 <th>ID</th>
                                 <th>Name</th>
                                 <th>Category</th>
                                 <th>Price</th>
                                 <th>Image</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              $query = "SELECT * FROM products";
                              $stmt = $conn->query($query);
                              $no = 1;

                              while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                 $id = $res['id'];
                                 $name = $res['name'];
                                 $category = $res['category'];
                                 $price = $res['price'];
                                 $image = $res['image'];
                              ?>
                                 <tr>
                                    <td><?php echo $id ?></td>
                                    <td><?php echo $name ?></td>
                                    <td><?php echo $category ?></td>
                                    <td>Rp <?php echo number_format($price, 0, ',', '.'); ?></td>
                                    <td>
                                       <?php if (!empty($image)) : ?>
                                          <img src="../uploaded_img/<?php echo $image ?>" alt="" width="50" height="50">
                                       <?php else : ?>
                                          <img src="../placeholder_image.jpg" alt="" width="50" height="50">
                                       <?php endif; ?>
                                    </td>
                                    <td>
                                       <!-- Update Product Modal Trigger -->
                                       <a href="#" class="btn btn-primary btn-update" data-toggle="modal" data-target="#updateProductModal" data-product-id="<?php echo $id; ?>">Update</a>
                                       <a href="products.php?delete=<?php echo $id; ?>" class="btn btn-danger" onclick="return confirm('Delete this product?');">Delete</a>
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


            <!-- Add Product Modal -->
            <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <div class="modal-body">
                        <!-- Add Product Form -->
                        <form action="process_add_product.php" method="post" enctype="multipart/form-data">
                           <div class="form-group">
                              <label for="productName">Product Name</label>
                              <input type="text" class="form-control" id="productName" name="productName" required>
                           </div>
                           <div class="form-group">
                              <label for="productCategory">Category</label>
                              <select name="category" class="form-control" required>
                                 <option value="" selected disabled>Select Category</option>
                                 <option value="main dish">Main Dish</option>
                                 <option value="fast food">Fast Food</option>
                                 <option value="drinks">Drinks</option>
                                 <option value="desserts">Desserts</option>
                              </select>
                           </div>
                           <div class="form-group">
                              <label for="productPrice">Price</label>
                              <input type="number" class="form-control" id="productPrice" name="productPrice" required>
                           </div>
                           <div class="form-group">
                              <label for="productImage">Image</label>
                              <input type="file" class="form-control-file" id="productImage" name="productImage">
                           </div>
                           <button type="submit" class="btn btn-primary" name="add_product">Add Product</button>

                        </form>
                        <!-- End Add Product Form -->
                     </div>
                  </div>
               </div>
            </div>

            <!-- Update Product Modal -->
            <div class="modal fade" id="updateProductModal" tabindex="-1" role="dialog" aria-labelledby="updateProductModalLabel" aria-hidden="true">
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="updateProductModalLabel">Update Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <div class="modal-body">
                        <!-- Update Product Form -->
                        <form id="updateProductForm" action="proses_update_products.php" method="post" enctype="multipart/form-data">
                           <input type="hidden" name="updateProductID" id="updateProductID">
                           <div class="form-group">
                              <label for="updateProductName">Product Name</label>
                              <input type="text" class="form-control" id="updateProductName" name="updateProductName" required>
                           </div>
                           <div class="form-group">
                              <label for="updateProductCategory">Category</label>
                              <select id="updateProductCategory" name="updateProductCategory" class="form-control" required>
                                 <option value="" selected disabled>Select Category</option>
                                 <option value="main dish">Main Dish</option>
                                 <option value="fast food">Fast Food</option>
                                 <option value="drinks">Drinks</option>
                                 <option value="desserts">Desserts</option>
                              </select>

                           </div>
                           <div class="form-group">
                              <label for="updateProductPrice">Price</label>
                              <input type="number" class="form-control" id="updateProductPrice" name="updateProductPrice" required>
                           </div>
                           <div class="form-group">
                              <label for="updateProductImage">Image</label>
                              <input type="file" class="form-control-file" id="updateProductImage" name="updateProductImage">
                           </div>
                           <div class="form-group">
                              <img id="existingProductImage" src="" alt="Existing Image" width="100" height="100">
                           </div>
                           <button type="submit" class="btn btn-primary" name="update_product">Update Product</button>
                        </form>
                        <!-- End Update Product Form -->
                     </div>
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

</body>
<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<script>
   // Menambahkan event listener untuk setiap tombol "Update"
   document.querySelectorAll('.btn-update').forEach(function(button) {
      button.addEventListener('click', function() {
         var productId = this.dataset.productId;

         $.ajax({
            url: 'get_data_products.php',
            method: 'GET',
            data: {
               product_id: productId
            },
            dataType: 'json',
            success: function(response) {
               console.log(response); // Check the response in the browser console
               $('#updateProductName').val(response.name);
               $('#updateProductPrice').val(response.price);
               $('#updateProductID').val(productId);

               // Clear existing selected option
               $('#updateProductCategory option').prop('selected', false);

               // Select the correct option based on the response.category
               $('#updateProductCategory').val(response.category);

               // Display the existing image below the input field
               var existingImage = response.image ? '../uploaded_img/' + response.image : '../placeholder_image.jpg';
               $('#existingProductImage').attr('src', existingImage);

               $('#updateProductModal').modal('show');
            },



            error: function(error) {
               console.error('Error getting product data:', error);
            }
         });
      });
   });

   // Menambahkan event listener untuk tombol "Update" di dalam modal
   // $('#updateProductForm').submit(function(event) {
   //    event.preventDefault();

   //    // Serialize form data using jQuery
   //    var formData = new FormData($(this)[0]);
   //    console.log(formData);

   //    $.ajax({
   //       url: 'proses_update_products.php',
   //       method: 'POST',
   //       data: formData,
   //       contentType: false,
   //       cache: false,
   //       processData: false,
   //       success: function(response) {
   //          // Handle response from the server
   //          console.log(response);
   //          // Close the modal after successful update
   //          $('#updateProductModal').modal('hide');
   //          // Refresh the page after successful update
   //          // location.reload(true);
   //       },
   //       error: function(xhr, status, error) {
   //          console.error('Error updating product:', error);
   //       }
   //    });
   // });
</script>





<!-- Custom scripts for all pages-->
<script src="../js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="../vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="../js/demo/chart-area-demo.js"></script>
<script src="../js/demo/chart-pie-demo.js"></script>

</body>

</html>