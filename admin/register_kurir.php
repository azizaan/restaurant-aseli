<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Ambil data yang dikirimkan melalui Ajax
   $kurirName = $_POST['kurirName'];
   $kurirEmail = $_POST['kurirEmail'];
   $kurirDomisili = $_POST['kurirDomisili'];
   $kurirNumber = $_POST['kurirNumber'];
   $kurirPassword = password_hash($_POST['kurirPassword'], PASSWORD_DEFAULT); // Hash password

   // Query untuk menambahkan kurir ke database
   $insert_kurir = $conn->prepare("INSERT INTO kurir (name, email, domisili, number, password) VALUES (?, ?, ?, ?, ?)");
   $insert_kurir->execute([$kurirName, $kurirEmail, $kurirDomisili, $kurirNumber, $kurirPassword]);

   if ($insert_kurir) {
      echo "Kurir berhasil ditambahkan!";
   } else {
      echo "Gagal menambahkan kurir.";
   }
} else {
   // Jika bukan metode POST, berikan pesan error
   echo "Invalid request method.";
}
