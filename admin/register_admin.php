<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   // Ambil data dari permintaan Ajax
   $adminName = $_POST['adminName'];
   $adminPassword = sha1($_POST['adminPassword']);

   // Tambah data admin ke database
   $insertAdmin = $conn->prepare("INSERT INTO `admin` (name, password) VALUES (?, ?)");
   if ($insertAdmin->execute([$adminName, $adminPassword])) {
      echo "Admin berhasil ditambahkan!";
   } else {
      echo "Gagal menambahkan admin!";
   }
} else {
   echo "Metode permintaan tidak valid!";
}
?>
