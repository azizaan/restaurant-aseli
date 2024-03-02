   <?php

   include 'components/connect.php';

   session_start();

   if (isset($_SESSION['user_id'])) {
      $user_id = $_SESSION['user_id'];
   } else {
      $user_id = '';
      header('location:home.php');
   };

   if (isset($_POST['completed'])) {
      $completed_id = $_POST['completed'];
      $completed_order = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
      $completed_order->execute(['completed', $completed_id]);
      header('location: orders.php');
   }

   ?>

   <!DOCTYPE html>
   <html lang="en">

   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>orders</title>

      <!-- font awesome cdn link  -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

      <!-- custom css file link  -->
      <link rel="stylesheet" href="css/style.css">



      <style>
         .completed-btn {
            margin-top: 2rem;
            padding: 15px 30px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
         }

         .completed-btn:hover {
            background-color: #45a049;
         }

         .message-container {
            margin-top: 10px;
            padding: 15px;
            background-color: #FE0101;
            border: 1px solid #FE0101;
            border-radius: 5px;
            font-size: 14px;
            color: #fff;
            transition: opacity 0.5s ease-in-out;
         }

         .box:hover .message-container {
            display: block;
            opacity: 1;
         }
      </style>

   </head>

   <body>

      <!-- header section starts  -->
      <?php include 'components/user_header.php'; ?>
      <!-- header section ends -->

      <div class="heading">
         <h3>orders</h3>
         <p><a href="home.php">home</a> <span> / orders</span></p>
      </div>

      <section class="orders">
         <h1 class="title">your orders</h1>

         <div class="box-container">
            <?php
            if ($user_id == '') {
               echo '<p class="empty">please login to see your orders</p>';
            } else {
               $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? AND (payment_status = 'delivery' OR payment_status = 'pending' OR payment_status = 'cooking')");
               $select_orders->execute([$user_id]);

               if ($select_orders->rowCount() > 0) {
                  while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
            ?>
                     <div class="box">
                        <p>placed on : <span><?= $fetch_orders['placed_on']; ?></span></p>
                        <p>name : <span><?= $fetch_orders['name']; ?></span></p>
                        <p>email : <span><?= $fetch_orders['email']; ?></span></p>
                        <p>number : <span><?= $fetch_orders['number']; ?></span></p>
                        <p>address : <span><?= $fetch_orders['address']; ?></span></p>
                        <p>payment method : <span><?= $fetch_orders['method']; ?></span></p>
                        <p>your orders : <span><?= $fetch_orders['total_products']; ?></span></p>
                        <p>total price : <span>Rp <?= $fetch_orders['total_price']; ?>/-</span></p>
                        <p> Payment status:
                           <span style="color: <?php
                                                $statusColor = '';
                                                switch ($fetch_orders['payment_status']) {
                                                   case 'pending':
                                                      $statusColor = 'red';
                                                      break;
                                                   case 'cooking':
                                                      $statusColor = 'yellow';
                                                      break;
                                                   case 'completed':
                                                   case 'delivery':
                                                      $statusColor = 'green';
                                                      break;
                                                   case 'cancel':
                                                      $statusColor = 'red';
                                                      break;
                                                   default:
                                                      $statusColor = 'black'; // You can set a default color for unknown status
                                                }
                                                echo $statusColor;
                                                ?>;">
                              <?= $fetch_orders['payment_status']; ?>
                           </span>
                        </p>

                        <?php
                        if ($fetch_orders['payment_status'] == 'delivery') {
                        ?>
                           <div class="message-container">Tolong klik tombol "Completed" ketika Anda telah menerima pesanan Anda!</div>
                           <form class="completed-form" method="POST">
                              <input type="hidden" name="completed" value="<?= $fetch_orders['id']; ?>">
                              <input type="submit" value="completed" class="completed-btn">
                              <!-- <div class="message-container">tolong klik tombol completed ketika anda telah menerima pesanan anda!</div> -->

                           </form>
                        <?php } ?>
                     </div>
            <?php
                  }
               } else {
                  echo '<p class="empty">no orders placed yet!</p>';
               }
            }
            ?>
         </div>
      </section>












      <!-- footer section starts  -->
      <?php include 'components/footer.php'; ?>
      <!-- footer section ends -->





      <script>
         function markCompleted(button) {
            var form = button.closest('.completed-form');
            var orderId = form.querySelector('[name="completed"]').value;

            // Use AJAX to send a request to update the order status
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
               if (xhr.readyState == 4 && xhr.status == 200) {
                  // Handle the response, you can update the UI if needed
                  console.log(xhr.responseText);
               }
            };
            xhr.open("GET", "orders.php?completed=" + orderId, true);
            xhr.send();
         }
      </script>
      <!-- custom js file link  -->
      <script src="js/script.js"></script>

   </body>

   </html>