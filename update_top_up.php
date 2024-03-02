<?php
include 'components/connect.php';

// Start the session
session_start();

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    // Handle the case where user_id is not set in the session
    echo 'User session not found. Please log in.';
    exit; // Stop further execution
}

// Retrieve the user_id from the session
$user_id = $_SESSION['user_id'];

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture the values of saldo and payment_method from the form
    $saldo = $_POST['saldo'];
    $payment_method = $_POST['payment_method'];

    // Validate the saldo
    if ($saldo <= 0) {
        echo 'Jumlah top up harus lebih besar dari 0!';
    } else {
        try {
            // Execute the SQL query to insert top up data into the database
            $insert_top_up = $conn->prepare("INSERT INTO top_up (user_id, saldo, method, status) VALUES (?, ?, ?, 'pending')");
            // Check if the query is executed successfully
            if ($insert_top_up->execute([$user_id, $saldo, $payment_method])) {
                echo 'Top Up berhasil!';
            } else {
                echo 'Terjadi kesalahan saat melakukan top up!';
                error_log("Gagal mengeksekusi query: " . print_r($insert_top_up->errorInfo(), true)); // Log the error message
            }
        } catch (PDOException $e) {
            echo 'Terjadi kesalahan saat melakukan top up!';
            error_log("Kesalahan database: " . $e->getMessage()); // Log the error message
        }
    }
} else {
    echo 'Permintaan tidak valid!';
}
