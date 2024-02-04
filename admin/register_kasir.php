<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Ambil data yang dikirimkan melalui Ajax
   $kasirName = $_POST['kasirName'];
   $kasirEmail = $_POST['kasirEmail'];
   $kasirPassword = password_hash($_POST['kasirPassword'], PASSWORD_DEFAULT); // Hash password

   // Query untuk menambahkan kasir ke database
   $insert_kasir = $conn->prepare("INSERT INTO kasir (name, email, password) VALUES (?, ?, ?)");
   $insert_kasir->execute([$kasirName, $kasirEmail, $kasirPassword]);

   if ($insert_kasir) {
      echo "kasir berhasil ditambahkan!";
   } else {
      echo "Gagal menambahkan kasir.";
   }
} else {
   // Jika bukan metode POST, berikan pesan error
   echo "Invalid request method.";
}
