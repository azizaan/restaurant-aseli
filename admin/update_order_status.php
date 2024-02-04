<?php
include '../components/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $updateStatus = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
    $updateStatus->execute([$status, $id]);

    echo 'Order status updated to completed.';
} else {
    echo 'Invalid request method.';
}
?>
