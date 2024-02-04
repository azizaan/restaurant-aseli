<?php

include '../components/connect.php';

session_start();

$kurir_id = $_SESSION['kurir_id'];

if (!isset($kurir_id)) {
   header('location:kurir_login.php');
}

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   // Other fields
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_EMAIL);

   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);

   $domisili = $_POST['domisili'];
   $domisili = filter_var($domisili, FILTER_SANITIZE_STRING);

   if (!empty($name)) {
      $select_name = $conn->prepare("SELECT * FROM `kurir` WHERE name = ?");
      $select_name->execute([$name]);

      if ($select_name->rowCount() > 0) {
         $message[] = 'Username already taken!';
      } else {
         // Update other fields
         $update_profile = $conn->prepare("UPDATE `kurir` SET name = ?, email = ?, number = ?, domisili = ? WHERE kurir_id = ?");
         $update_profile->execute([$name, $email, $number, $domisili, $kurir_id]);

         $message[] = 'Profile updated successfully!';
      }
   }

   // Password update
   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_old_pass = $conn->prepare("SELECT password FROM `kurir` WHERE kurir_id = ?");
   $select_old_pass->execute([$kurir_id]);
   $fetch_prev_pass = $select_old_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];

   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);

   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);

   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if ($old_pass != $empty_pass) {
      if ($old_pass != $prev_pass) {
         $message[] = 'Old password not matched!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'Confirm password not matched!';
      } else {
         if ($new_pass != $empty_pass) {
            $update_pass = $conn->prepare("UPDATE `kurir` SET password = ? WHERE kurir_id = ?");
            $update_pass->execute([$confirm_pass, $kurir_id]);
            $message[] = 'Password updated successfully!';
         } else {
            $message[] = 'Please enter a new password!';
         }
      }
   }
}

// Fetch the updated profile data
$select_profile = $conn->prepare("SELECT * FROM `kurir` WHERE kurir_id = ?");
$select_profile->execute([$kurir_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Kurir Profile Update</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/kurir_style.css">

</head>

<body>

   <?php include '../components/kurir_header.php' ?>

   <!-- kurir profile update section starts  -->

   <section class="form-container">

      <form action="" method="POST">
         <h3>Update Profile</h3>
         <input type="text" name="name" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" placeholder="<?= $fetch_profile['name']; ?>">
         <input type="text" name="email" maxlength="50" class="box" placeholder="<?= $fetch_profile['email']; ?>">
         <input type="text" name="number" maxlength="20" class="box" placeholder="<?= $fetch_profile['number']; ?>">
         <input type="text" name="domisili" maxlength="50" class="box" placeholder="<?= $fetch_profile['domisili']; ?>">
         <input type="password" name="old_pass" maxlength="20" placeholder="Enter your old password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="new_pass" maxlength="20" placeholder="Enter your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="confirm_pass" maxlength="20" placeholder="Confirm your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="Update Now" name="submit" class="btn">
         <a href="kurir_dashboard.php" class="delete-btn">Back</a>
      </form>

   </section>

   <!-- kurir profile update section ends -->

   <!-- custom js file link  -->
   <script src="../js/kurir_script.js"></script>

</body>

</html>