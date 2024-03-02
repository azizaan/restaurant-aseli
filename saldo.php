<?php
include 'components/connect.php';

session_start();

$message = []; // Inisialisasi $message sebagai array kosong

// Periksa session 'user_id' dengan benar
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Top Up Saldo</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <!-- SweetAlert2 library -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <style>
      /* CSS untuk membuat form tetap berada di tengah */
      .top-up {
         display: flex;
         justify-content: center;
         align-items: center;
         flex-direction: column;
         /* height: 100vh; */
      }

      .top-up form {
         width: 300px;
         padding: 20px;
         border: 1px solid #ccc;
         border-radius: 5px;
      }

      .top-up form h1 {
         text-align: center;
         font-size: 36px;
         margin-bottom: 1rem;
      }

      .top-up form .box {
         width: 100%;
         margin-bottom: 10px;
         padding: 10px;
         border: 1px solid #ccc;
         border-radius: 5px;
         box-sizing: border-box;
      }

      .top-up form .btn {
         width: 100%;
         padding: 10px;
         border: none;
         border-radius: 5px;
         background-color: #fed330;
         color: #fff;
         cursor: pointer;
      }

      .tabs {
         margin-bottom: 20px;
         text-align: center;
      }

      .tabs button {
         padding: 10px 20px;
         border: none;
         background-color: #f0f0f0;
         cursor: pointer;
         transition: background-color 0.3s ease;
      }

      .tabs button.active {
         background-color: #fed330;
         color: #fff;
      }
   </style>
</head>

<body>

   <!-- header section starts  -->
   <?php include 'components/user_header.php'; ?>
   <!-- header section ends -->

   <div class="heading">
      <h3>Top Up Saldo</h3>
      <p><a href="home.php">Home</a> / <a href="profile.php">Profile</a> <span> / Top Up Saldo</span></p>
   </div>

   <!-- Top up section starts  -->

   <section class="top-up">
      <div class="tabs">
         <button class="tab-btn active" data-tab="dana">Dana</button>
         <button class="tab-btn" data-tab="shoppepay">ShoppePay</button>
         <button class="tab-btn" data-tab="gopay">GoPay</button>
      </div>

      <form action="" method="post" id="topUpForm">
         <h1>Isi Saldo</h1>
         <?php if (isset($message)) {
            foreach ($message as $msg) {
               echo "<p class='message'>$msg</p>";
            }
         } ?>
         <input type="text" name="saldo" min="0" class="box" placeholder="Masukkan jumlah saldo" required autocomplete="off">
         <input type="hidden" name="payment_method" id="paymentMethod" value="dana"> <!-- Tambahkan input hidden untuk menyimpan metode pembayaran yang dipilih -->
         <input type="submit" value="Top Up" name="top_up" class="btn">
      </form>
   </section>


   <!-- Top up section ends -->

   <!-- footer section starts  -->
   <?php include 'components/footer.php'; ?>
   <!-- footer section ends -->

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

   <script>
      // Fungsi untuk menampilkan SweetAlert sesuai dengan metode pembayaran yang dipilih
      function showPaymentAlert(paymentMethod) {
         var topUpSaldo = document.querySelector('input[name="saldo"]').value;

         console.log(topUpSaldo);

         var title = ''; // Judul SweetAlert
         var text = ''; // Isi pesan SweetAlert

         // Tentukan judul dan isi pesan SweetAlert sesuai dengan metode pembayaran yang dipilih
         switch (paymentMethod) {
            case 'dana':
               title = 'Top Up diPesan!';
               text = 'Isi saldo sebesar Rp ' + topUpSaldo + '. Silahkan scan QR diatas untuk melanjutkan transaksi berdasarkan nominal yang anda isikan';
               break;
            case 'shoppepay':
               title = 'Top Up diPesan!';
               text = 'Isi saldo sebesar Rp ' + topUpSaldo + '. Silahkan gunakan metode pembayaran ShoppePay yang tersedia';
               break;
            case 'gopay':
               title = 'Top Up diPesan!';
               text = 'Isi saldo sebesar Rp ' + topUpSaldo + '. Silahkan gunakan metode pembayaran GoPay yang tersedia';
               break;
            default:
               title = 'Error';
               text = 'Metode pembayaran tidak dikenali.';
               break;
         }

         // Tampilkan SweetAlert
         Swal.fire({
            icon: "success",
            title: title,
            text: text,
            imageUrl: 'vendor/qrcode/' + paymentMethod + '.jpg',
            imageWidth: 400,
            imageHeight: 400,
            imageAlt: 'QR Code',
            showCancelButton: true,
            confirmButtonText: 'OK',
            cancelButtonText: 'Batal'
         }).then((result) => {
            if (result.isConfirmed) {
               // Lakukan pengiriman data ke update_top_up.php
               var xhr = new XMLHttpRequest();
               xhr.open('POST', 'update_top_up.php', true);
               xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
               xhr.onreadystatechange = function() {
                  if (xhr.readyState === XMLHttpRequest.DONE) {
                     if (xhr.status === 200) {
                        // Tanggapan dari update_top_up.php
                        Swal.fire('Sukses!', xhr.responseText, 'success');
                     } else {
                        Swal.fire('Error', 'Terjadi kesalahan saat mengirim data.', 'error');
                     }
                  }
               };
               xhr.send('payment_method=' + paymentMethod + '&saldo=' + topUpSaldo);
            }
         });

      }

      document.addEventListener('DOMContentLoaded', function() {
         var tabButtons = document.querySelectorAll('.tab-btn'); // Ambil semua tombol tab
         var paymentMethodInput = document.getElementById('paymentMethod'); // Input hidden untuk menyimpan metode pembayaran

         // Tambahkan event click pada setiap tombol tab
         tabButtons.forEach(function(button) {
            button.addEventListener('click', function() {
               // Hapus kelas active dari semua tombol tab
               tabButtons.forEach(function(btn) {
                  btn.classList.remove('active');
               });
               // Tambahkan kelas active ke tombol tab yang diklik
               button.classList.add('active');
               // Simpan metode pembayaran yang dipilih ke input hidden
               paymentMethodInput.value = button.dataset.tab;
            });
         });

         // Tambahkan event submit pada form
         var topUpForm = document.getElementById('topUpForm');
         topUpForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Cegah pengiriman formulir default

            // Ambil metode pembayaran yang dipilih dari input hidden
            var paymentMethod = paymentMethodInput.value;
            // Tampilkan SweetAlert sesuai dengan metode pembayaran yang dipilih
            showPaymentAlert(paymentMethod);
         });
      });
   </script>
</body>

</html>