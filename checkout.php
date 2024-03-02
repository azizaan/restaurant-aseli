<?php

include 'components/connect.php';

session_start();

function getNoMaster($id_primary, $table, $kode)
{
   $size_code = strlen($kode);
   $maxid = '';

   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "food_db";

   try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $query = "SELECT MAX(SUBSTRING($id_primary, $size_code + 1)) AS max_id FROM $table WHERE $id_primary LIKE '$kode%'";
      $stmt = $conn->prepare($query);
      $stmt->execute();
      $data = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($data['max_id'] === null) {
         $maxid = $kode . "0001";
      } else {
         $maxid = $kode . str_pad((int)$data['max_id'] + 1, 4, "0", STR_PAD_LEFT);
      }
   } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
   } finally {
      $conn = null;
   }

   return $maxid;
}


if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:home.php');
}

if (isset($_POST['submit'])) {
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if ($check_cart->rowCount() > 0) {
      if ($address == '') {
         $message[] = 'please add your address!';
      } else {
         // Generate a unique order ID
         $order_id = getNoMaster('id', 'orders', 'ORD');

         // Check if the selected payment method is 'saldo'
         if ($method == 'saldo') {
            // Get user's current saldo
            $user_saldo_query = $conn->prepare("SELECT saldo FROM users WHERE id = ?");
            $user_saldo_query->execute([$user_id]);
            $user_saldo = $user_saldo_query->fetchColumn();

            // Check if user's saldo is sufficient
            if ($user_saldo >= $total_price) {
               // Deduct total price from user's saldo
               $new_saldo = $user_saldo - $total_price;
               $update_saldo_query = $conn->prepare("UPDATE users SET saldo = ? WHERE id = ?");
               $update_saldo_query->execute([$new_saldo, $user_id]);

               // Proceed with order placement
               $insert_order = $conn->prepare("INSERT INTO `orders`(id, user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?,?)");
               $insert_order->execute([$order_id, $user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

               // Clear user's cart
               $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
               $delete_cart->execute([$user_id]);

               $message[] = 'Order placed successfully!';
            } else {
               // Insufficient saldo, display error message
               $message[] = 'Insufficient saldo. Please top up your saldo.';
            }
         } else {
            // Payment method other than 'saldo', proceed with regular order placement
            $insert_order = $conn->prepare("INSERT INTO `orders`(id, user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?,?)");
            $insert_order->execute([$order_id, $user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

            // Clear user's cart
            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->execute([$user_id]);

            $message[] = 'Order placed successfully!';
         }
      }
   } else {
      $message[] = 'Your cart is empty';
   }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .message-container {
         margin-top: 10px;
         padding: 10px;
         background-color: #f2ff03;
         border: 1px solid #ccc;
         border-radius: 5px;
         font-size: 14px;
         color: #333;
         display: none;
      }
   </style>


</head>

<body>

   <!-- header section starts  -->
   <?php include 'components/user_header.php'; ?>
   <!-- header section ends -->

   <div class="heading">
      <h3>checkout</h3>
      <p><a href="home.php">home</a> <span> / checkout</span></p>
   </div>

   <section class="checkout">

      <h1 class="title">order summary</h1>

      <form action="" method="post">

         <div class="cart-items">
            <h3>cart items</h3>
            <?php
            $grand_total = 0;
            $cart_items[] = '';
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if ($select_cart->rowCount() > 0) {
               while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                  $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
                  $total_products = implode($cart_items);
                  $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
            ?>
                  <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price">Rp <?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?></span></p>
            <?php
               }
            } else {
               echo '<p class="empty">your cart is empty!</p>';
            }
            ?>
            <p class="grand-total"><span class="name">grand total :</span><span class="price">Rp <?= $grand_total; ?></span></p>
            <a href="cart.php" class="btn">view cart</a>
         </div>

         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
         <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
         <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
         <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
         <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>">

         <div class="user-info">
            <h3>your info</h3>
            <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
            <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?></span></p>
            <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
            <!-- Tambahkan tampilan saldo di sini -->
            <?php if ($fetch_profile['saldo'] !== null) : ?>
               <p><i class="fas fa-wallet"></i><span>Saldo Anda: Rp <?= $fetch_profile['saldo'] ?></span></p>
            <?php else : ?>
               <p><i class="fas fa-wallet"></i><span>Saldo Anda: Rp 0</span></p>
            <?php endif; ?>
            <a href="update_profile.php" class="btn">update info</a>
            <!-- Tombol Top Up Saldo -->
            <a href="saldo.php" class="btn">Top Up Saldo</a>
            <h3>delivery address</h3>
            <p><i class="fas fa-map-marker-alt"></i><span><?php if ($fetch_profile['address'] == '') {
                                                               echo 'please enter your address';
                                                            } else {
                                                               echo $fetch_profile['address'];
                                                            } ?></span></p>
            <a href="update_address.php" class="btn">update address</a>

            <select name="method" class="box" required>
               <option value="" disabled selected>select payment method --</option>
               <option value="cash on delivery">cash on delivery</option>
               <option value="saldo">gunakan saldo</option> <!-- Tambahkan opsi penggunaan saldo -->
               <option value="dana">dana</option>
               <option value="qris">qris</option>
               <option value="shoppepay">shoppepay</option>
               <option value="gopay">gopay</option>
            </select>
            <div class="message-container"></div>
            <input type="submit" value="place order" class="btn <?php if ($fetch_profile['address'] == '') {
                                                                     echo 'disabled';
                                                                  } ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit">
         </div>

      </form>

   </section>

   <!-- footer section starts  -->
   <?php include 'components/footer.php'; ?>
   <!-- footer section ends -->

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         var paymentMethodSelect = document.querySelector('select[name="method"]');
         var messageContainer = document.querySelector('.message-container');

         paymentMethodSelect.addEventListener('change', function() {
            var selectedOption = paymentMethodSelect.value;

            if (selectedOption === 'saldo') {
               // Tambahkan logika untuk memberikan alert jika saldo tidak mencukupi saat checkout
               var saldo = <?= $fetch_profile['saldo'] ?>;
               var total_price = <?= $grand_total ?>;
               if (saldo < total_price) {
                  messageContainer.innerHTML = 'Maaf, saldo Anda tidak mencukupi untuk melakukan pembayaran!';
               } else {
                  messageContainer.innerHTML = '';
               }
            } else {
               messageContainer.innerHTML = '';
            }
         });
      });
   </script>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         var paymentMethodSelect = document.querySelector('select[name="method"]');
         var messageContainer = document.querySelector('.message-container');

         paymentMethodSelect.addEventListener('change', function() {
            var selectedOption = paymentMethodSelect.value;

            if (selectedOption === 'dana' || selectedOption === 'qris' || selectedOption === 'shoppepay' || selectedOption === 'gopay') {
               messageContainer.innerHTML = 'Pesan khusus untuk metode pembayaran ' + selectedOption + '.<br>pembayaran akan dilaksanakan pada kurir masing-masing ';
            } else {
               messageContainer.innerHTML = ''; // Hapus pesan jika metode pembayaran tidak 'dana' atau 'qris'
            }
         });
      });
   </script>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         var paymentMethodSelect = document.querySelector('select[name="method"]');
         var messageContainer = document.querySelector('.message-container');

         paymentMethodSelect.addEventListener('change', function() {
            var selectedOption = paymentMethodSelect.value;

            if (selectedOption === 'dana' || selectedOption === 'qris' || selectedOption === 'shoppepay' || selectedOption === 'gopay') {
               messageContainer.style.display = 'block'; // Tampilkan pesan jika metode pembayaran 'dana' atau 'qris'
            } else {
               messageContainer.style.display = 'none'; // Sembunyikan pesan jika metode pembayaran tidak 'dana' atau 'qris'
            }
         });
      });
   </script>


   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>