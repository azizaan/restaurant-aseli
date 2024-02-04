<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:home.php');
};

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);

   if (!empty($name)) {
      $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $user_id]);
   }

   if (!empty($email)) {
      $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_email->execute([$email]);
      if ($select_email->rowCount() > 0) {
         $message[] = 'email already taken!';
      } else {
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $user_id]);
      }
   }

   if (!empty($number)) {
      $select_number = $conn->prepare("SELECT * FROM `users` WHERE number = ?");
      $select_number->execute([$number]);
      if ($select_number->rowCount() > 0) {
         $message[] = 'number already taken!';
      } else {
         $update_number = $conn->prepare("UPDATE `users` SET number = ? WHERE id = ?");
         $update_number->execute([$number, $user_id]);
      }
   }

   if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
      $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
      $file_info = pathinfo($_FILES['profile_picture']['name']);
      $file_extension = strtolower($file_info['extension']);

      if (in_array($file_extension, $allowed_extensions)) {
         $upload_dir = 'users/'; // Specify the directory where you want to store uploaded images
         $upload_file = $upload_dir . basename($_FILES['profile_picture']['name']);

         if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_file)) {
            // Save the file path or do additional processing if needed
            $profile_picture_path = $upload_file;
            // Save $profile_picture_path in the database along with other user details
            $update_picture = $conn->prepare("UPDATE `users` SET profile_picture = ? WHERE id = ?");
            $update_picture->execute([$profile_picture_path, $user_id]);
         } else {
            $message[] = 'Failed to upload profile picture.';
         }
      } else {
         $message[] = 'Invalid file format. Allowed formats: jpg, jpeg, png, gif.';
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_prev_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
   $select_prev_pass->execute([$user_id]);
   $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if ($old_pass != $empty_pass) {
      if ($old_pass != $prev_pass) {
         $message[] = 'old password not matched!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'confirm password not matched!';
      } else {
         if ($new_pass != $empty_pass) {
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $user_id]);
            $message[] = 'password updated successfully!';
         } else {
            $message[] = 'please enter a new password!';
         }
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .form-container form {
         display: flex;
         flex-direction: column;
         align-items: flex-start;
      }

      .form-container img {
         max-width: 100px;
         margin-bottom: 20px;
      }

      .form-container form .box {
         width: 100%;
         margin-bottom: 15px;
      }

      .form-container form .btn {
         margin-right: 0;
      }
   </style>

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="form-container update-form">

      <form action="" method="post" enctype="multipart/form-data">
         <h3>update profile</h3>
         <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" class="box" maxlength="50">
         <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="number" name="number" placeholder="<?= $fetch_profile['number']; ?>" class="box" min="0" max="9999999999" maxlength="10">
         <input type="file" name="profile_picture" accept="image/*" class="box" autocomplete="off">
         <img src="<?= $fetch_profile['profile_picture']; ?>" alt="Profile Picture">
         <input type="password" name="old_pass" placeholder="enter your old password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="new_pass" placeholder="enter your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="confirm_pass" placeholder="confirm your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="update now" name="submit" class="btn">
         <a href="profile.php" class="delete-btn">Back</a>
      </form>

   </section>










   <?php include 'components/footer.php'; ?>






   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>