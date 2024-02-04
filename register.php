<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
   $select_user->execute([$email, $number]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if ($select_user->rowCount() > 0) {
      $msg_email[]  = true;
   } else {
      if ($pass != $cpass) {
         $msg_pass[]  = true;
      } else {
         // Handle file upload for profile picture
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
                  $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password, profile_picture) VALUES(?,?,?,?,?)");
                  $insert_user->execute([$name, $email, $number, $cpass, $profile_picture_path]);
               } else {
                  $fail_upload[] = true;
               }
            } else {
               $format_eror[] = true;
            }
         } else {
            // If no file is uploaded, insert user data without profile picture
            $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?,?,?,?)");
            $insert_user->execute([$name, $email, $number, $cpass]);
         }

         $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
         $select_user->execute([$email, $pass]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);
         if ($select_user->rowCount() > 0) {
            $_SESSION['user_id'] = $row['id'];
            header('location:home.php');
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
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <!-- SweetAlert CSS -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.6.1/dist/sweetalert2.min.css">

</head>

<body>

   <!-- header section starts  -->
   <?php include 'components/user_header.php'; ?>
   <!-- header section ends -->

   <section class="form-container">

      <form action="" method="post" enctype="multipart/form-data">
         <h3>register now</h3>

         <input type="text" name="name" required placeholder="enter your name" class="box" maxlength="50" autocomplete="off">
         <input type="email" name="email" required placeholder="enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" autocomplete="off">
         <input type="text" name="number" required placeholder="enter your telephone number" class="box" min="0" max="9999999999" maxlength="10" autocomplete="off">
         <input type="file" name="profile_picture" accept="image/*" class="box" autocomplete="off">
         <input type="password" name="pass" required placeholder="enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" autocomplete="off">
         <input type="password" name="cpass" required placeholder="confirm your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" autocomplete="off">
         <input type="submit" value="register now" name="submit" class="btn">
         <p>already have an account? <a href="login.php">login now</a></p>
      </form>

   </section>

   <?php include 'components/footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

   <!-- SweetAlert JavaScript -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.6.1/dist/sweetalert2.min.js"></script>
   <script>
      <?php if (isset($msg_email) && $msg_email) { ?>
         Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Email or telephone number already exists!',
         });
      <?php } ?>
   </script>
   <script>
      <?php if (isset($msg_pass) && $msg_pass) { ?>
         Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Confirm password not matched!',
         });
      <?php } ?>
   </script>
   <script>
      <?php if (isset($fail_upload) && $fail_upload) { ?>
         Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Failed to upload profile picture.',
         });
      <?php } ?>
   </script>
   <script>
      <?php if (isset($format_eror) && $format_eror) { ?>
         Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Invalid file format. Allowed formats: jpg, jpeg, png, gif.',
         });
      <?php } ?>
   </script>

</body>

</html>