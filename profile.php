<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:home.php');
};

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .profile-picture-container {
         width: 120px;
         /* Sesuaikan dengan ukuran yang diinginkan */
         height: 120px;
         /* Sesuaikan dengan ukuran yang diinginkan */
         overflow: hidden;
         border-radius: 50%;
         margin: 0 auto;
      }

      .profile-picture {
         width: 100%;
         height: 100%;
         object-fit: cover;
         border-radius: 50%;
      }
   </style>

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="user-details">

      <div class="user">
         <?php
         // Menghubungkan ke database
         include 'components/connect.php';

         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
            <div class="profile-picture-container">
               <img src="<?= $fetch_profile['profile_picture']; ?>" alt="" class="profile-picture">
            </div>
            <p><i class="fas fa-user"></i><span><span><?= $fetch_profile['name']; ?></span></span></p>
            <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number']; ?></span></p>
            <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email']; ?></span></p>
            <p>
               <i class="fas fa-wallet"></i>
               <span>
                  Saldo Anda:
                  <?php
                  echo ($fetch_profile['saldo'] !== null && $fetch_profile['saldo'] != '') ? 'Rp ' . $fetch_profile['saldo'] : 'Rp 0';
                  ?>
               </span>
            </p>

            <a href="update_profile.php" class="btn">update info</a> <a href="saldo.php" class="btn">Top up Saldo</a>
            <!-- <button class="btn" id="set-alamat-btn">Set Alamat</button> -->
            <p class="address"><i class="fas fa-map-marker-alt"></i><span><?php if ($fetch_profile['address'] == '') {
                                                                              echo 'please enter your address';
                                                                           } else {
                                                                              echo $fetch_profile['address'];
                                                                           } ?></span></p>
            <a href="update_address.php" class="btn">update address</a>
         <?php
         } else {
         ?>
            <p>Profile not found!</p>
         <?php
         }
         ?>
      </div>

   </section>

   <?php include 'components/footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <!-- <script>
      function setAlamat() {
         var deferred = $.Deferred();

         if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
               function(position) {
                  var lat = position.coords.latitude;
                  var lng = position.coords.longitude;

                  // Kirim ke OpenStreetMap Nominatim API
                  var nominatimApiUrl = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&addressdetails=1';

                  $.ajax({
                     url: nominatimApiUrl,
                     method: 'GET',
                     dataType: 'json',
                     success: function(data) {
                        // Ambil alamat dari hasil Geocoding
                        var address = data.display_name;
                        // Ambil informasi lebih rinci
                        var addressDetails = data.address;

                        // Menambahkan informasi dusun, desa, RT, dan RW
                        var additionalDetails = {
                           dusun: addressDetails.village,
                           desa: addressDetails.town,
                           rt: addressDetails.suburb,
                           rw: addressDetails.hamlet
                        };

                        // Menyatukan semua informasi
                        var completeAddress = {
                           address: address,
                           details: addressDetails,
                           additional: additionalDetails
                        };

                        // Resolve deferred object dengan alamat dan informasi lebih rinci
                        deferred.resolve(completeAddress);
                        console.log(completeAddress);
                     },
                     error: function(error) {
                        console.error('Error:', error);
                        // Reject deferred object jika terjadi error
                        deferred.reject();
                     }
                  });
               },
               function(error) {
                  console.error('Error getting geolocation:', error);
                  // Reject deferred object jika terjadi error
                  deferred.reject();
               }
            );
         } else {
            console.error('Geolocation tidak didukung oleh browser ini.');
            // Reject deferred object jika geolocation tidak didukung
            deferred.reject();
         }

         return deferred.promise();
      }

      // Handler acara ketika tombol "Set Alamat" diklik
      $(document).ready(function() {
         $("#set-alamat-btn").on("click", function() {
            setAlamat().done(function(result) {
               $('#alamat').val(result.address);
               $('#alamat').show();
               $('.p-action').hide(400);

               // Menampilkan informasi lebih rinci di console
               console.log('Informasi Lebih Rinci:', result.details);
               console.log('Informasi Tambahan:', result.additional);
            });
         });
      });
   </script> -->

</body>

</html>